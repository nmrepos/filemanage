import 'cypress-xpath'

describe('Assigned Documents Page - UI Checks', () => {
    beforeEach(() => {
      cy.visit('/#/login')
      cy.get('#uname').type('nidhunofficial@gmail.com')
      cy.get('#pass').type('fm123')
      cy.get('#loginbtn').click()
  
      cy.xpath('//a[.//span[contains(text(),"Assigned Documents")]]').click({ force: true })

    })
  
    it('Should show the page title', () => {
      cy.contains('.page-title', 'Assigned Documents').should('exist')
    })
  
    it('Should show "Add Document" button', () => {
      cy.contains('a.btn-outline-success', 'Add Document').should('exist')
    })
  
    it('Should show "My Reminders" button', () => {
      cy.contains('a.btn-danger', 'My Reminders').should('exist')
    })
  
    it('Should have search input for name/description', () => {
      cy.get('input[placeholder="Search by name or description"]').should('exist')
    })
  
    it('Should have search input for meta tags', () => {
      cy.get('input[placeholder="Search by meta tags"]').should('exist')
    })
  
    it('Should have select dropdown for Category', () => {
      cy.contains('div.ng-placeholder', 'Select Category').should('exist')
    })
  
    it('Should have select dropdown for Storage', () => {
      cy.contains('div.ng-placeholder', 'Storage').should('exist')
    })
  
    it('Should show table with header: Action', () => {
      cy.get('th.mat-column-action').should('contain.text', 'Action')
    })
  
    it('Should show table with header: Name', () => {
      cy.get('th.mat-column-name').should('contain.text', 'Name')
    })
  
    it('Should show table with header: Category Name', () => {
      cy.get('th.mat-column-categoryName').should('contain.text', 'Category Name')
    })
  
    it('Should show table with header: Storage', () => {
      cy.get('th.mat-column-location').should('contain.text', 'Storage')
    })
  
    it('Should show table with header: Created Date', () => {
      cy.get('th.mat-column-createdDate').should('contain.text', 'Created Date')
    })
  
    it('Should show table with header: Expired Date', () => {
      cy.get('th.mat-column-expiredDate').should('contain.text', 'Expired Date')
    })
  
    it('Should show table with header: Created By', () => {
      cy.get('th.mat-column-createdBy').should('contain.text', 'Created By')
    })
  
    it('Should show paginator at the bottom', () => {
      cy.get('mat-paginator').should('exist')
    })
  })
  