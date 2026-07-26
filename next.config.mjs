/**
 * Baseline response hardening, per the OWASP Secure Headers Project
 * (https://owasp.org/www-project-secure-headers/). The Content-Security-Policy
 * itself is applied per-request with a nonce in proxy.ts.
 *
 * Note: Strict-Transport-Security only takes effect when the site is served
 * over HTTPS; it is inert on plain-HTTP local development.
 */
const securityHeaders = [
  { key: "X-Content-Type-Options", value: "nosniff" },
  { key: "X-Frame-Options", value: "DENY" },
  { key: "Referrer-Policy", value: "strict-origin-when-cross-origin" },
  {
    key: "Permissions-Policy",
    value: "camera=(), microphone=(), geolocation=(), browsing-topics=()",
  },
  {
    key: "Strict-Transport-Security",
    value: "max-age=63072000; includeSubDomains; preload",
  },
];

/** @type {import('next').NextConfig} */
const nextConfig = {
  async headers() {
    return [
      {
        source: "/:path*",
        headers: securityHeaders,
      },
    ];
  },
};

export default nextConfig;
