import 'cypress-xpath';

describe('Roles Page E2E Test', () => {
    beforeEach(() => {
      cy.visit('/login');
      cy.url().should('include', '/login');
    });

    it('Should show Roles title in header', () => {
        cy.url().then((currentUrl) => {
            cy.task('logToTerminal', currentUrl);
          });
          
      });
      
})

describe('Health Check', () => {
    it('should load the frontend', () => {
      cy.visit('/login');
      cy.contains('Email').should('be.visible');
    });
  
    it('should communicate with the backend', () => {
      cy.request('GET', 'http://127.0.0.1:8000/api/login')
        .its('status')
        .should('eq', 200);
    });
});