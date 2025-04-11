import 'cypress-xpath'

describe('Login Page UI Checks', () => {
  beforeEach(() => {
    cy.visit('/#/login') // baseUrl should be set in cypress.config.ts
  })

  it('Should display logo image', () => {
    cy.get('img[alt="logo-full"]').should('be.visible')
  })

  it('Should show "Login to continue" text', () => {
    cy.contains('Login to continue').should('be.visible')
  })

  it('Should have email label and input field', () => {
    cy.xpath('//label[@for="username" and text()="Email"]').should('exist')
    cy.xpath('//input[@id="uname" and @formcontrolname="userName"]').should('exist').and('be.visible')
  })

  it('Should have password label and input field', () => {
    cy.xpath('//label[@for="password" and text()="Password"]').should('exist')
    cy.xpath('//input[@id="pass" and @formcontrolname="password"]').should('exist').and('be.visible')
  })

  it('Should have Forgot Password link', () => {
    cy.get('a.forget-password-btn').should('be.visible').and('have.attr', 'href').and('include', '/forgot-password')
  })

  it('Should have Login button', () => {
    cy.xpath('//button[@id="loginbtn" and contains(.,"Login")]')
      .should('be.visible')
      .and('have.attr', 'type', 'submit')
  })

  it('Should allow typing into email and password', () => {
    cy.get('#uname').type('testuser@example.com').should('have.value', 'testuser@example.com')
    cy.get('#pass').type('securepassword').should('have.value', 'securepassword')
  })
})


describe('Login Failed Test', () => {
  it('Should show error unauthorized', () => {
    cy.visit('/#/login') 
    cy.get('#uname').type('nidhunofficial@gmail.com')
    cy.get('#pass').type('abcd')
    cy.get('#loginbtn').click()
    cy.contains('Unauthorized')
  })
})

describe('Login Success Test', () => {
  it('Should log in and show dashboard', () => {
    cy.visit('/#/login') 
    cy.get('#uname').type('nidhunofficial@gmail.com')
    cy.get('#pass').type('fm123')
    cy.get('#loginbtn').click()
    cy.url().should('include', '/dashboard')
    cy.contains('Dashboard')
  })
})

describe('Forgot Password Navigation', () => {
  it('Should naviagate to the forgot password page', () => {
    cy.visit('/#/login') 
    cy.xpath('/html/body/app-root/app-root/app-login/div/div/div[2]/div/div/div/div/div/div[2]/form/div[3]/a').click()
    cy.url().should('include', '/forgot-password')
  })
})

describe('Login Page Form Check', () => {
  it('Should contain the login form', () => {
    cy.visit('/#/login')
    cy.xpath('/html/body/app-root/app-root/app-login/div/div/div[2]/div/div/div/div/div/div[2]/form')
      .should('exist')
  })
})