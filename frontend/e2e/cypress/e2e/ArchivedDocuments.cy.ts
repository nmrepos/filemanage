import 'cypress-xpath'

describe('Archived Documents Page E2E Test', () => {
  beforeEach(() => {
    cy.visit('/#/login')
    cy.get('#uname').type('nidhunofficial@gmail.com')
    cy.get('#pass').type('fm123')
    cy.get('#loginbtn').click()
    cy.url().should('include', '/dashboard')
    cy.xpath("//a[.//span[contains(text(),'Archived Documents')]]").click({ force: true })
    cy.url().should('include', '/archived-documents')
  })

  it('Should show Archived Documents title in header', () => {
    cy.contains('span.page-title', 'Archived Documents').should('exist')
  })

  it('Should display search by name or description input', () => {
    cy.get('input[placeholder="Search by name or description"]').should('exist')
  })

  it('Should display search by meta tags input', () => {
    cy.get('input[placeholder="Search by meta tags"]').should('exist')
  })

  it('Should display Select Category dropdown', () => {
    cy.contains('.ng-select-container .ng-placeholder', 'Select Category').should('exist')
  })

  it('Should display Storage dropdown', () => {
    cy.contains('.ng-select-container .ng-placeholder', 'Storage').should('exist')
  })

  it('Should display Action header in table', () => {
    cy.contains('th', 'Action').should('exist')
  })

  it('Should display Name header in table', () => {
    cy.contains('th', 'Name').should('exist')
  })

  it('Should display Document Category header in table', () => {
    cy.contains('th', 'Document Category').should('exist')
  })

  it('Should display Storage header in table', () => {
    cy.contains('th', 'Storage').should('exist')
  })

  it('Should display Archived Date header in table', () => {
    cy.contains('th', 'Archived Date').should('exist')
  })

  it('Should display Archived By header in table', () => {
    cy.contains('th', 'Archived By').should('exist')
  })

  it('Should show "No Data Found" when there is no data', () => {
    cy.contains('b', 'No Data Found').should('exist')
  })

  it('Should display paginator with correct label', () => {
    cy.contains('.mat-mdc-paginator-range-label', '0 of 0').should('exist')
  })

  it('Should have paginator navigation buttons disabled', () => {
    cy.get('button[aria-label="Previous page"]').should('be.disabled')
    cy.get('button[aria-label="Next page"]').should('be.disabled')
  })
})
