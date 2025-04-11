import 'cypress-xpath';

describe('Dashboard Layout UI Test', () => {
  beforeEach(() => {
    cy.visit('/#/login');
    cy.get('#uname').type('nidhunofficial@gmail.com');
    cy.get('#pass').type('fm123');
    cy.get('#loginbtn').click();

    cy.url().should('include', '/dashboard');
  });

  it('Should show header with navbar and icons', () => {
    cy.get('app-header').should('exist').and('be.visible');
    cy.get('.navbar').should('exist').and('be.visible');

    cy.get('.menuBtn').should('exist'); // menu toggle
    cy.get('.nav-item.user_profile').should('exist'); // user icon
    cy.get('i-feather[name="bell"]').should('exist'); // bell icon
  });

  it('Should display sidebar with all menu items', () => {
    cy.get('app-sidebar').should('exist');
    cy.get('#leftsidebar').should('exist');

    const sidebarItems = [
      'Dashboard',
      'Assigned Documents',
      'All Documents',
      'Document Categories',
      'Documents Audit Trail',
      'Archived Documents',
      'Roles',
      'Users',
      'Role User',
      'Reminder',
      'Login Audits',
      'Settings'
    ];

    sidebarItems.forEach((label) => {
      cy.get('#sidebarnav').contains(label).should('exist');
    });
  });

  // ----------------- DASHBOARD CONTENT ------------------
  it('Should display dashboard widgets like charts and calendar', () => {
    cy.get('app-document-by-category-chart').should('exist');
    cy.get('app-calender-view').should('exist');
    cy.get('ngx-charts-pie-chart').should('exist');
    cy.get('mwl-calendar-month-view').should('exist');
  });

  it('Should navigate to Dashboard', () => {
    cy.xpath('//a[.//span[contains(text(),"Dashboard")]]').click({ force: true });
    cy.url().should('include', '/dashboard');
  });

  it('Should navigate to Assigned Documents', () => {
    cy.xpath('//a[.//span[contains(text(),"Assigned Documents")]]').click({ force: true });
    cy.url().should('match', /\/$/);
  });

  it('Should navigate to All Documents', () => {
    cy.xpath('//a[.//span[contains(text(),"All Documents")]]').click({ force: true });
    cy.url().should('include', '/documents');
  });

  it('Should navigate to Deep Search', () => {
    cy.xpath('//a[.//span[contains(text(),"Deep Search")]]').click({ force: true });
    cy.url().should('include', '/documents/deep-search');
  });

  it('Should navigate to Document Categories', () => {
    cy.xpath('//a[.//span[contains(text(),"Document Categories")]]').click({ force: true });
    cy.url().should('include', '/categories');
  });

  it('Should navigate to Documents Audit Trail', () => {
    cy.xpath('//a[.//span[contains(text(),"Documents Audit Trail")]]').click({ force: true });
    cy.url().should('include', '/document-audit-trails');
  });

  it('Should navigate to Archived Documents', () => {
    cy.xpath('//a[.//span[contains(text(),"Archived Documents")]]').click({ force: true });
    cy.url().should('include', '/archived-documents');
  });

  it('Should navigate to Roles', () => {
    cy.xpath('//a[.//span[contains(text(),"Roles")]]').click({ force: true });
    cy.url().should('include', '/roles');
  });

  it('Should navigate to Users', () => {
    cy.xpath('//a[.//span[contains(text(),"Users")]]').click({ force: true });
    cy.url().should('include', '/users');
  });

  it('Should navigate to Role User', () => {
    cy.xpath('//a[.//span[contains(text(),"Role User")]]').click({ force: true });
    cy.url().should('include', '/roles/users');
  });

  it('Should navigate to Reminder', () => {
    cy.xpath('//a[.//span[contains(text(),"Reminder")]]').click({ force: true });
    cy.url().should('include', '/reminders');
  });

  it('Should navigate to Login Audits', () => {
    cy.xpath('//a[.//span[contains(text(),"Login Audits")]]').click({ force: true });
    cy.url().should('include', '/login-audit');
  });
});
