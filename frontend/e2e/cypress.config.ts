import { defineConfig } from "cypress";

export default defineConfig({
  video: false,
  screenshotOnRunFailure: false,
  e2e: {
    setupNodeEvents(on, config) {
      // implement node event listeners here
    },
    baseUrl: 'http://filemanage-frontend.s3-website-us-east-1.amazonaws.com',
    specPattern: 'cypress/e2e/*.cy.ts',
    supportFile: 'cypress/support/e2e.ts',
  },
});
