import { defineConfig } from "cypress";

export default defineConfig({
  video: false,
  screenshotOnRunFailure: false,
  e2e: {
    setupNodeEvents(on, config) {
      on('task', {
        logToTerminal(message: string) {
          console.log('💡 CYPRESS LOG:', message)
          return null
        }
      })
    },
    baseUrl: 'http://127.0.0.1:4200',
    specPattern: 'cypress/e2e/*.cy.ts',
    supportFile: 'cypress/support/e2e.ts',
  },
});
