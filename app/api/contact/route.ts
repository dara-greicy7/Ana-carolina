import fs from "node:fs/promises";
import path from "node:path";
import { NextRequest, NextResponse } from "next/server";
import { getClientKey, rateLimit } from "@/lib/rate-limit";

export const runtime = "nodejs";

const MAX_BODY_BYTES = 10 * 1024;
const MIN_FILL_TIME_MS = 2_000;
const MAX_FILL_TIME_MS = 24 * 60 * 60 * 1000;

const NAME_MIN = 2;
const NAME_MAX = 100;
const EMAIL_MAX = 254;
const MESSAGE_MIN = 10;
const MESSAGE_MAX = 2_000;

// Practical, deliberately conservative email check — full RFC 5322 validation
// is not a security boundary; mailbox existence is confirmed by delivery.
const EMAIL_PATTERN = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

const submissionsDirectory = path.join(
  process.cwd(),
  "data",
  "submissions"
);

type ContactPayload = {
  name: string;
  email: string;
  message: string;
};

function validatePayload(input: unknown): ContactPayload | null {
  if (typeof input !== "object" || input === null) {
    return null;
  }

  const { name, email, message } = input as Record<string, unknown>;

  if (
    typeof name !== "string" ||
    typeof email !== "string" ||
    typeof message !== "string"
  ) {
    return null;
  }

  const trimmed: ContactPayload = {
    name: name.trim(),
    email: email.trim(),
    message: message.trim(),
  };

  if (
    trimmed.name.length < NAME_MIN ||
    trimmed.name.length > NAME_MAX ||
    trimmed.email.length > EMAIL_MAX ||
    !EMAIL_PATTERN.test(trimmed.email) ||
    trimmed.message.length < MESSAGE_MIN ||
    trimmed.message.length > MESSAGE_MAX
  ) {
    return null;
  }

  return trimmed;
}

async function storeSubmission(payload: ContactPayload, clientKey: string) {
  await fs.mkdir(submissionsDirectory, { recursive: true });
  const month = new Date().toISOString().slice(0, 7);
  const filePath = path.join(submissionsDirectory, `contact-${month}.jsonl`);
  const record = JSON.stringify({
    receivedAt: new Date().toISOString(),
    clientKey,
    ...payload,
  });
  await fs.appendFile(filePath, record + "\n", "utf8");
}

async function forwardToWebhook(payload: ContactPayload): Promise<void> {
  const webhookUrl = process.env.CONTACT_WEBHOOK_URL;
  if (!webhookUrl) {
    return;
  }

  // The URL is operator-configured, but restrict to HTTPS so a misconfigured
  // value cannot leak submissions over plaintext.
  let parsed: URL;
  try {
    parsed = new URL(webhookUrl);
  } catch {
    console.error("[contact] CONTACT_WEBHOOK_URL is not a valid URL");
    return;
  }
  if (parsed.protocol !== "https:") {
    console.error("[contact] CONTACT_WEBHOOK_URL must use https:");
    return;
  }

  const controller = new AbortController();
  const timeout = setTimeout(() => controller.abort(), 5_000);
  try {
    const response = await fetch(parsed, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ source: "conativetime.com", ...payload }),
      signal: controller.signal,
    });
    if (!response.ok) {
      console.error(`[contact] webhook responded with ${response.status}`);
    }
  } catch (error) {
    console.error("[contact] webhook delivery failed:", error);
  } finally {
    clearTimeout(timeout);
  }
}

export async function POST(request: NextRequest) {
  const clientKey = getClientKey(request);

  const perClient = rateLimit(`contact:${clientKey}`, 5, 60 * 60 * 1000);
  const global = rateLimit("contact:__global__", 100, 60 * 60 * 1000);
  if (!perClient.allowed || !global.allowed) {
    return NextResponse.json(
      { error: "Too many requests. Please try again later." },
      {
        status: 429,
        headers: {
          "Retry-After": String(
            perClient.retryAfterSeconds || global.retryAfterSeconds
          ),
        },
      }
    );
  }

  const contentLength = Number(request.headers.get("content-length") ?? 0);
  if (!Number.isFinite(contentLength) || contentLength <= 0 || contentLength > MAX_BODY_BYTES) {
    return NextResponse.json({ error: "Invalid request." }, { status: 400 });
  }

  let body: unknown;
  try {
    body = await request.json();
  } catch {
    return NextResponse.json({ error: "Invalid request." }, { status: 400 });
  }

  if (typeof body !== "object" || body === null) {
    return NextResponse.json({ error: "Invalid request." }, { status: 400 });
  }

  const fields = body as Record<string, unknown>;

  // Honeypot: real users never see or fill this field. Pretend success so
  // bots learn nothing.
  if (typeof fields.company === "string" && fields.company.length > 0) {
    return NextResponse.json({ ok: true });
  }

  // Time-trap: submissions faster than a human can type, or implausibly old,
  // are rejected.
  const formStartedAt =
    typeof fields.formStartedAt === "number" ? fields.formStartedAt : NaN;
  const fillTime = Date.now() - formStartedAt;
  if (
    !Number.isFinite(fillTime) ||
    fillTime < MIN_FILL_TIME_MS ||
    fillTime > MAX_FILL_TIME_MS
  ) {
    return NextResponse.json({ error: "Invalid request." }, { status: 400 });
  }

  const payload = validatePayload(fields);
  if (!payload) {
    return NextResponse.json(
      { error: "Please check the highlighted fields and try again." },
      { status: 422 }
    );
  }

  try {
    await storeSubmission(payload, clientKey);
  } catch (error) {
    console.error("[contact] could not store submission:", error);
    return NextResponse.json(
      { error: "The message could not be delivered. Please try again later." },
      { status: 500 }
    );
  }

  // Delivery notification is best-effort; the stored submission is authoritative.
  await forwardToWebhook(payload);

  return NextResponse.json({ ok: true });
}
