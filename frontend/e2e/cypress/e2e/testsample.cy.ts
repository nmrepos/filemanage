import 'cypress-xpath';

describe('Roles Page E2E Test', () => {
    beforeEach(() => {
      cy.visit('/#/login');
      cy.url().should('include', '/login');
    });

    cy.url().then((currentUrl) => {
        cy.task('logToTerminal', currentUrl);
      });
})