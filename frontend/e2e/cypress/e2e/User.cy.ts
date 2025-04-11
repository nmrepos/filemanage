import 'cypress-xpath';

describe('Users Page UI Test', () => {
  beforeEach(() => {
    cy.visit('/#/login');
    cy.get('#uname').type('nidhunofficial@gmail.com');
    cy.get('#pass').type('fm123');
    cy.get('#loginbtn').click();
    cy.url().should('include', '/dashboard');
    cy.xpath("//a[.//span[contains(text(),'Users')]]").click({ force: true });
    cy.url().should('include', '/users');
  });

  it('Should show Users title in header', () => {
    cy.xpath("//span[contains(text(),'Users')]").should('exist').and('be.visible');
  });

  it('Should display Add User button', () => {
    cy.xpath("//a//span[contains(text(),'Add User')]").should('exist').and('be.visible');
  });

  it('Should display Action column header', () => {
    cy.contains('th', 'Action').should('exist');
  });

  it('Should display Email column header', () => {
    cy.contains('th', 'Email').should('exist');
  });

  it('Should display First Name column header', () => {
    cy.contains('th', 'First Name').should('exist');
  });

  it('Should display Last Name column header', () => {
    cy.contains('th', 'Last Name').should('exist');
  });

  it('Should display Mobile Number column header', () => {
    cy.contains('th', 'Mobile Number').should('exist');
  });
});
