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

          cy.get('#uname').should('exist').type('test@test.com');
          cy.get('#pass').should('exist').type('test');
          cy.get('#loginbtn').should('exist').click();
          
      });
})