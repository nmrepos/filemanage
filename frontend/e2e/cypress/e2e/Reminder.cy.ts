import 'cypress-xpath';

describe('Reminders Page UI Test', () => {
  beforeEach(() => {
    cy.visit('/#/login');
    cy.get('#uname').type('nidhunofficial@gmail.com');
    cy.get('#pass').type('fm123');
    cy.get('#loginbtn').click();
    cy.url().should('include', '/dashboard');
    cy.xpath("//a[.//span[contains(text(),'Reminder')]]").click({ force: true });
    cy.url().should('include', '/reminders');
  });

  it('Should display the Reminders page title', () => {
    cy.xpath("//span[contains(text(),'Reminders')]").should('exist').and('be.visible');
  });

  it('Should display the Add Reminder button', () => {
    cy.xpath("//span[contains(text(),'Add Reminder')]/ancestor::a").should('exist').and('be.visible');
  });

  it('Should display Start Date column header', () => {
    cy.contains('th', 'Start Date').should('exist');
  });

  it('Should display End Date column header', () => {
    cy.contains('th', 'End Date').should('exist');
  });

  it('Should display Subject column header', () => {
    cy.contains('th', 'Subject').should('exist');
  });

  it('Should display Message column header', () => {
    cy.contains('th', 'Message').should('exist');
  });

  it('Should display Frequency column header', () => {
    cy.contains('th', 'Frequency').should('exist');
  });

  it('Should display Document column header', () => {
    cy.contains('th', 'Document').should('exist');
  });

  it('Should display Subject filter input', () => {
    cy.xpath("//input[@placeholder='Subject']").should('exist').and('be.visible');
  });

  it('Should display Message filter input', () => {
    cy.xpath("//input[@placeholder='Message']").should('exist').and('be.visible');
  });

  it('Should display Frequency dropdown filter', () => {
    cy.xpath("//ng-select[contains(@class, 'ng-select-single')]//div[contains(text(),'Frequency')]").should('exist');
  });

  it('Should show paginator', () => {
    cy.get('mat-paginator').should('exist').and('be.visible');
  });

  it('Should show "No Data Found" when no reminders exist', () => {
    cy.contains('b', 'No Data Found').should('exist').and('be.visible');
  });
});
