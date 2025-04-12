import 'cypress-xpath';

describe('Roles Page E2E Test', () => {
    beforeEach(() => {
      cy.visit('/#/login');
      cy.url().should('include', '/login');
    });

    it('Should show Roles title in header', () => {
        cy.xpath('/html/body/app-root/app-root/app-login/div/div/div[2]/div/div/div/div/div/div[2]/form/div[1]/div/input').type('test@test.com');
        cy.get('/html/body/app-root/app-root/app-login/div/div/div[2]/div/div/div/div/div/div[2]/form/div[2]/div/input').type('test');
        cy.get('#loginbtn').click();
        cy.url().should('include', '/dashboard');
    });
})