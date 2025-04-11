import 'cypress-xpath';

describe('Login Audit Logs Page', () => {
    beforeEach(() => {
        cy.visit('/#/login');
        cy.get('#uname').type('nidhunofficial@gmail.com');
        cy.get('#pass').type('fm123');
        cy.get('#loginbtn').click();
        cy.url().should('include', '/dashboard');
        cy.xpath("//a[.//span[contains(text(),'Login Audits')]]").click({ force: true });
        cy.url().should('include', '/login-audit');
    });

    it('should display Login Audit Logs table with data', () => {
        cy.contains('Login Audit Logs').should('be.visible');
        cy.get('table').should('exist');
        // Check headers
        cy.get('thead tr').first().within(() => {
        cy.contains('Date & Time').should('exist');
        cy.contains('Email').should('exist');
        cy.contains('IP Address').should('exist');
        cy.contains('Status').should('exist');
        cy.contains('Latitude').should('exist');
        cy.contains('Longitude').should('exist');
        });
    });

    it('should allow sorting by Date & Time', () => {
        cy.get('th').contains('Date & Time').click(); // First click sorts
        cy.wait(500); // Wait for sorting to take effect
        cy.get('th').contains('Date & Time').click(); // Second click reverses sort
        cy.wait(500);
    });
});