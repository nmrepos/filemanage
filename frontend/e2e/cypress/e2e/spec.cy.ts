describe('Login and Dashboard Test', () => {
  it('Should log in and show dashboard', () => {
    cy.visit('/#/login') // baseUrl should be set!

    // Fill login form
    cy.get('#uname').type('nidhunofficial@gmail.com')
    cy.get('#pass').type('fm123')
    cy.get('#loginbtn').click()

    // Wait for dashboard route to load
    cy.url().should('include', '/dashboard')

    // Verify something on the dashboard
    cy.contains('Dashboard') //
  })
})
