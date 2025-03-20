module.exports = [
  {
    // TypeScript files configuration
    files: ["**/*.ts"],
    languageOptions: {
      parser: require("@typescript-eslint/parser")
    },
    rules: {
      "no-trailing-spaces": "error",
      "no-multi-spaces": "error",
      "no-multiple-empty-lines": [
        "error",
        { max: 1, maxEOF: 0, maxBOF: 0 }
      ]
    }
  },
  {
    // HTML files configuration for Angular templates
    files: ["**/*.html"],
    languageOptions: {
      parser: require("@angular-eslint/template-parser")
    },
    rules: {
      // You can add Angular-specific template rules here, or leave empty to disable linting errors
    }
  }
];
