import 'cypress-xpath';

describe('Documents Audit Trail Page E2E Test', () => {
  beforeEach(() => {
    cy.visit('/#/login');
    cy.get('#uname').type('nidhunofficial@gmail.com');
    cy.get('#pass').type('fm123');
    cy.get('#loginbtn').click();
    cy.url().should('include', '/dashboard');
    cy.xpath("//a[.//span[contains(text(),'Documents Audit Trail')]]").click({ force: true });
    cy.url().should('include', '/document-audit-trails');
  });

  it('Should show Documents Audit Trail title in header', () => {
    cy.xpath("//span[contains(text(),'Documents Audit Trail')]").should('exist').and('be.visible');
  });

  it('Should show Search by name input', () => {
    cy.xpath("//input[@placeholder='Search by name']").should('exist').and('be.visible');
  });

  it('Should show Category dropdown placeholder', () => {
    cy.contains('div.ng-placeholder', 'Category').should('exist').and('be.visible');
  });

  it('Should show Select User dropdown placeholder', () => {
    cy.contains('div.ng-placeholder', 'Select User').should('exist').and('be.visible');
  });

  it('Should display Action Date table header', () => {
    cy.contains('th', 'Action Date').should('exist');
  });

  it('Should display Name table header', () => {
    cy.contains('th', 'Name').should('exist');
  });

  it('Should display Category Name table header', () => {
    cy.contains('th', 'Category Name').should('exist');
  });

  it('Should display Operation table header', () => {
    cy.contains('th', 'Operation').should('exist');
  });

  it('Should display By Whom table header', () => {
    cy.contains('th', 'By Whom').should('exist');
  });

  it('Should display To Whom User table header', () => {
    cy.contains('th', 'To Whom User').should('exist');
  });

  it('Should display To Whom Role table header', () => {
    cy.contains('th', 'To Whom Role').should('exist');
  });

  it('Should display paginator at the bottom', () => {
    cy.get('mat-paginator').should('exist').and('be.visible');
    cy.contains('Items per page').should('exist');
  });
});
