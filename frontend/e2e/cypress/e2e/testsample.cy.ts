describe('Roles Page E2E Test', () => {
    beforeEach(() => {
      cy.visit('/#/login');
      cy.url().should('include', '/login');
    });
})