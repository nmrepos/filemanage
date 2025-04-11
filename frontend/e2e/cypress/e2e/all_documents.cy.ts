
import 'cypress-xpath'

describe('All Documents Page UI Test', () => {
  beforeEach(() => {
    cy.visit('/#/login')
    cy.get('#uname').type('nidhunofficial@gmail.com')
    cy.get('#pass').type('fm123')
    cy.get('#loginbtn').click()
    cy.url().should('include', '/dashboard')
    cy.xpath("//a[.//span[contains(text(),'All Documents')]]").click({ force: true })
    cy.url().should('include', '/documents')
  })

  it('Should show All Documents title in header', () => {
    cy.xpath("//span[contains(text(),'All Documents')]").should('exist').and('be.visible')
  })

  it('Should display Add Document button', () => {
    cy.xpath("//a[contains(@href,'/documents/add')]//span[contains(text(),'Add Document')]").should('exist').and('be.visible')
  })

  it('Should show search by name or description input', () => {
    cy.xpath("//input[@placeholder='Search by name or description']").should('exist').and('be.visible')
  })

  it('Should show search by meta tags input', () => {
    cy.xpath("//input[@placeholder='Search by meta tags']").should('exist').and('be.visible')
  })

  it('Should show Select Category dropdown', () => {
    cy.xpath("//div[contains(@class,'ng-placeholder') and contains(text(),'Select Category')]").should('exist')
  })

  it('Should show Storage dropdown', () => {
    cy.xpath("//div[contains(@class,'ng-placeholder') and contains(text(),'Storage')]").should('exist')
  })

  it('Should show Created Date input', () => {
    cy.xpath("//input[@placeholder='Created Date']").should('exist')
  })

  it('Should show document table with correct headers', () => {
    cy.contains('th', 'Action').should('exist')
    cy.contains('th', 'Name').should('exist')
    cy.contains('th', 'Document Category').should('exist')
    cy.contains('th', 'Storage').should('exist')
    cy.contains('th', 'Created Date').should('exist')
    cy.contains('th', 'Created By').should('exist')
  })

  it('Should have a working paginator at the bottom', () => {
    cy.get('mat-paginator').should('exist').and('be.visible')
    cy.contains('Items per page').should('exist')
  })
})
