import nextVitals from "eslint-config-next/core-web-vitals";
import nextTypescript from "eslint-config-next/typescript";

const eslintConfig = [
  ...nextVitals,
  ...nextTypescript,
  {
    ignores: [
      "node_modules/**",
      ".next/**",
      "out/**",
      "public_html/**",
      "old_images/**",
      "scripts/**",
      "parse_*.js",
    ],
  },
];

export default eslintConfig;
