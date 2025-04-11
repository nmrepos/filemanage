import 'cypress-xpath'

describe('Document Categories Page UI Test', () => {
  beforeEach(() => {
    cy.visit('/#/login')
    cy.get('#uname').type('nidhunofficial@gmail.com')
    cy.get('#pass').type('fm123')
    cy.get('#loginbtn').click()
    cy.url().should('include', '/dashboard')
    cy.xpath("//a[.//span[contains(text(),'Document Categories')]]").click({ force: true })
    cy.url().should('include', '/categories')
  })

  it('Should show Document Categories title in header', () => {
    cy.xpath("//span[contains(text(),'Document Categories')]").should('exist').and('be.visible')
  })

  it('Should display Add Document Category button', () => {
    cy.xpath("//a//span[contains(text(),'Add Document Category')]").should('exist').and('be.visible')
  })

  it('Should display Action and Name headers in table', () => {
    cy.contains('th', 'Action').should('exist')
    cy.contains('th', 'Name').should('exist')
  })

  it('Should display Edit button in table rows', () => {
    cy.xpath("//button[.//span[contains(text(),'Edit')]]").should('exist')
  })

  it('Should display Delete button in table rows', () => {
    cy.xpath("//button[.//span[contains(text(),'Delete')]]").should('exist')
  })

  it('Should display chevron icon for expanding child categories', () => {
    cy.xpath("//i-feather//*[name()='svg' and contains(@class,'feather-chevrons-right')]").should('exist')
  })

  it('Should show "Child Categories" section when expanded', () => {
    cy.xpath("//i-feather//*[name()='svg' and contains(@class,'feather-chevrons-right')]").click({multiple: true, force: true })
    cy.contains('h2', 'Child Categories').should('exist')
  })

  it('Should display Add Child Category button when expanded', () => {
    cy.xpath("//i-feather//*[name()='svg' and contains(@class,'feather-chevrons-right')]").click({multiple: true, force: true })
    cy.contains('button', 'Add Child Category').should('exist')
  })

  it('Should show Action and Name headers for child category table', () => {
    cy.xpath("//i-feather//*[name()='svg' and contains(@class,'feather-chevrons-right')]").click({multiple: true, force: true })
    cy.contains('th', 'Action').should('exist')
    cy.contains('th', 'Name').should('exist')
  })

})

describe('Document Category CRUD Test', () => {
    const dummyCategory = 'Test Category';
    const updatedCategory = 'Updated Test Category';
  
    before(() => {
      cy.visit('/#/login');
      cy.get('#uname').type('nidhunofficial@gmail.com');
      cy.get('#pass').type('fm123');
      cy.get('#loginbtn').click();
      cy.url().should('include', '/dashboard');
      cy.xpath("//a[.//span[contains(text(),'Document Categories')]]").click({ force: true });
      cy.url().should('include', '/categories');
    });
  
    it('Should add a new document category', () => {
      // Click 'Add Document Category' button
      cy.xpath("//span[contains(text(),'Add Document Category')]/parent::a").click({ force: true });
  
      // Wait for dialog to appear
      cy.get('mat-dialog-container', { timeout: 10000 }).should('be.visible');
  
      // Fill out the form
      cy.get('input#categoryName').should('be.visible').type(dummyCategory);
      cy.get('textarea[name="description"]').should('be.visible').type('This is a dummy category');
  
      // Click Save
      cy.xpath(
        "/html/body/div[2]/div[2]/div/mat-dialog-container/div/div/app-manage-category/mat-dialog-content/form/div[3]/div/button[1]",
        { timeout: 10000 }
      ).click();
  
      // Confirm category was added
      cy.contains('td', dummyCategory, { timeout: 10000 }).should('exist');
    });
  
    it('Should edit the added category', () => {
      // Reload and login fresh
      cy.visit('/');
      cy.get('#uname').type('nidhunofficial@gmail.com');
      cy.get('#pass').type('fm123');
      cy.get('#loginbtn').click();
      cy.url().should('include', '/dashboard');
      cy.xpath("//a[.//span[contains(text(),'Document Categories')]]").click({ force: true });
      cy.url().should('include', '/categories');
  
      // Find the row with original category name and click Edit
      cy.contains('td', dummyCategory, { timeout: 10000 }).should('be.visible')
        .parent('tr')
        .within(() => {
          cy.xpath(".//span[contains(text(),'Edit')]/ancestor::button").click({ force: true });
        });
  
      // Wait for dialog and edit
      cy.get('mat-dialog-container', { timeout: 10000 }).should('be.visible');
      cy.get('input#categoryName').clear().type(updatedCategory);
      cy.get('textarea[name="description"]').clear().type('Updated description');
  
      // Click Save
      cy.xpath(
        "/html/body/div[2]/div[2]/div/mat-dialog-container/div/div/app-manage-category/mat-dialog-content/form/div[3]/div/button[1]",
        { timeout: 10000 }
      ).click();
  
      // Verify update
      cy.contains('td', updatedCategory, { timeout: 10000 }).should('exist');
    });
  });
  