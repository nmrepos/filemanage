import 'cypress-xpath';

describe('Roles Page E2E Test', () => {
  beforeEach(() => {
    cy.visit('/#/login');
    cy.get('#uname').type('nidhunofficial@gmail.com');
    cy.get('#pass').type('fm123');
    cy.get('#loginbtn').click();
    cy.url().should('include', '/dashboard');
    cy.xpath("//a[.//span[contains(text(),'Roles')]]").click({ force: true });
    cy.url().should('include', '/roles');
  });

  it('Should show Roles title in header', () => {
    cy.contains('span.page-title', 'Roles').should('exist').and('be.visible');
  });

  it('Should display Add Role button', () => {
    cy.contains('a.btn', 'Add Role').should('exist').and('be.visible');
  });

  it('Should display Action and Name headers in the roles table', () => {
    cy.contains('th', 'Action').should('exist');
    cy.contains('th', 'Name').should('exist');
  });

  it('Should show Edit button in table rows', () => {
    cy.xpath("//button[.//span[contains(text(),'Edit')]]").should('exist');
  });

  it('Should show Delete button in table rows', () => {
    cy.xpath("//button[.//span[contains(text(),'Delete')]]").should('exist');
  });

});
