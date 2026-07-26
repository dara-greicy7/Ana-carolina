"use client";

import { useId, useState } from "react";
import { Button } from "@/components/ui/button";

type SubmitState = "idle" | "sending" | "success" | "error";

const inputClassName =
  "w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-white/20 transition-all";

export function ContactForm() {
  const formId = useId();
  const [state, setState] = useState<SubmitState>("idle");
  const [errorMessage, setErrorMessage] = useState<string | null>(null);
  // Time-trap: the server rejects submissions that arrive implausibly fast.
  const [formStartedAt] = useState(() => Date.now());

  const fieldId = (name: string) => `${formId}-${name}`;

  async function handleSubmit(event: React.FormEvent<HTMLFormElement>) {
    event.preventDefault();
    if (state === "sending") {
      return;
    }

    const form = event.currentTarget;
    const data = new FormData(form);

    setState("sending");
    setErrorMessage(null);

    try {
      const response = await fetch("/api/contact", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          name: data.get("name"),
          email: data.get("email"),
          message: data.get("message"),
          company: data.get("company"),
          formStartedAt,
        }),
      });

      if (!response.ok) {
        const payload = (await response.json().catch(() => null)) as {
          error?: string;
        } | null;
        throw new Error(payload?.error ?? "The message could not be sent.");
      }

      form.reset();
      setState("success");
    } catch (error) {
      setState("error");
      setErrorMessage(
        error instanceof Error ? error.message : "The message could not be sent."
      );
    }
  }

  if (state === "success") {
    return (
      <div
        role="status"
        className="flex h-full min-h-[16rem] flex-col items-center justify-center gap-4 text-center"
      >
        <p className="text-2xl font-bold text-white">Message sent</p>
        <p className="text-white/70">
          Thank you for reaching out. We will get back to you shortly.
        </p>
        <Button
          type="button"
          variant="outline"
          onClick={() => setState("idle")}
          className="border-white/20 text-white hover:bg-white/10"
        >
          Send another message
        </Button>
      </div>
    );
  }

  return (
    <form className="space-y-6" onSubmit={handleSubmit} noValidate={false}>
      {/* Honeypot: hidden from users, attractive to bots. */}
      <div aria-hidden="true" className="absolute -left-[9999px] -top-[9999px] h-0 w-0 overflow-hidden">
        <label htmlFor={fieldId("company")} tabIndex={-1}>
          Company
        </label>
        <input
          id={fieldId("company")}
          name="company"
          type="text"
          tabIndex={-1}
          autoComplete="off"
        />
      </div>

      <div>
        <label
          htmlFor={fieldId("name")}
          className="block text-sm font-medium text-white/70 mb-2"
        >
          Name
        </label>
        <input
          id={fieldId("name")}
          name="name"
          type="text"
          required
          minLength={2}
          maxLength={100}
          autoComplete="name"
          className={inputClassName}
          placeholder="Your name"
        />
      </div>

      <div>
        <label
          htmlFor={fieldId("email")}
          className="block text-sm font-medium text-white/70 mb-2"
        >
          Email
        </label>
        <input
          id={fieldId("email")}
          name="email"
          type="email"
          required
          maxLength={254}
          autoComplete="email"
          className={inputClassName}
          placeholder="you@example.com"
        />
      </div>

      <div>
        <label
          htmlFor={fieldId("message")}
          className="block text-sm font-medium text-white/70 mb-2"
        >
          Message
        </label>
        <textarea
          id={fieldId("message")}
          name="message"
          rows={4}
          required
          minLength={10}
          maxLength={2000}
          className={inputClassName}
          placeholder="How can we help?"
        />
      </div>

      {state === "error" && errorMessage && (
        <p role="alert" className="text-sm text-red-300">
          {errorMessage}
        </p>
      )}

      <Button
        type="submit"
        disabled={state === "sending"}
        className="w-full py-6 text-lg font-bold"
      >
        {state === "sending" ? "Sending…" : "Send Message"}
      </Button>
    </form>
  );
}
