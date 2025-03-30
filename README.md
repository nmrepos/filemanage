# Filemanage System

Filemanage is a full‑stack file management system that streamlines document storage, access control, and collaboration. It leverages a modern Angular frontend, a robust Laravel backend, and a CI/CD pipeline for automated testing and deployment.

Link : http://filemanage-frontend.s3-website-us-east-1.amazonaws.com/login/
---
![CI](https://github.com/nmrepos/filemanage/actions/workflows/ci.yml/badge.svg)
## Table of Contents

- [Overview](#overview)
- [Architecture](#architecture)
  - [Frontend (Angular)](#frontend-angular)
  - [Backend (Laravel)](#backend-laravel)
  - [CI/CD Pipeline](#cicd-pipeline)
- [Features](#features)
  - [User Registration & Authentication](#user-registration--authentication)
  - [Password Reset & Two-Factor Authentication](#password-reset--two-factor-authentication)
  - [Core Document Management](#core-document-management)
- [Installation & Setup](#installation--setup)
  - [Frontend Setup](#frontend-setup)
  - [Backend Setup](#backend-setup)
- [Testing](#testing)
- [Deployment](#deployment)
- [Contributing](#contributing)
- [License](#license)
- [Contact](#contact)

---

## Overview

Filemanage is designed to help users and administrators efficiently manage a high volume of documents. The system supports multi-format document uploads, comprehensive access controls, audit trails, versioning, and integrations with cloud storage like Amazon S3. Additionally, it offers enhanced security through two-factor authentication and multilingual support for a global user base.

---

## Architecture

### Frontend (Angular)

- **Location**: `frontend` folder.
- **Responsibilities**:
  - Provides a responsive user interface.
  - Retrieves data from the backend API via HTTP calls.
  - Uses environment configurations to differentiate between local development and production (production builds use secrets, such as the EC2 host URL).
- **Testing**:
  - Unit and integration tests are implemented using Jasmine/Karma along with HttpClientTestingModule.

### Backend (Laravel)

- **Location**: `backend` folder.
- **Responsibilities**:
  - Exposes API endpoints to serve frontend requests.
  - Connects to a MySQL database hosted on AWS RDS.
  - Includes a migration for an applications table (seeded with sample data like the application name “Filemanage”).
  - Returns JSON data through endpoints such as `/api/application`.
- **Testing**:
  - Feature and unit tests are written with PHPUnit to ensure API reliability.

### CI/CD Pipeline

- **Tool**: GitHub Actions.
- **Jobs**:
  - **Frontend**: Builds and deploys the Angular app to an S3 bucket configured for static website hosting.
  - **Backend**: Deploys the Laravel application to an EC2 instance.
- **Deployment Process**:
  - Run tests to catch regressions.
  - Package and transfer the Laravel application using SCP.
  - Generate the production `.env` file using GitHub Secrets.
  - Install dependencies, run migrations, and restart Apache.

---

## Features

### User Login & Authentication

- **User Registration**:
  - Login page includes fields for username, email, and password.
  - Input validations ensure proper email format and password strength.
  - Confirmation emails are sent to verify new accounts.
  - Account activation occurs only after email verification.

- **User Authentication & Session Management**:
  - Secure login with valid credentials.
  - Proper session management with timeout mechanisms.
  - Passwords are securely stored (salted and hashed).
  - Clear error messaging for invalid login attempts and an easy logout option.

### Password Reset & Two-Factor Authentication

- **Password Reset**:
  - "Forgot Password" functionality on the login page.
  - Email with a secure, time-limited reset token is sent to users.
  - Allows users to set a new password securely, invalidating the token after use.

- **Two-Factor Authentication (2FA)**:
  - Users can enable/disable 2FA in account settings.
  - Supports 2FA via SMS or authenticator apps.
  - When enabled, login requires an additional verification code.

### Core Document Management

- **Manage Documents**:
  - Create, edit, and delete categories/sub-categories.
  - Documents can be assigned to one or multiple categories.
  - Designed to handle a high volume of documents efficiently.

- **Upload Documents**:
  - Supports various file types (PDF, DOC, Excel, PPT, audio, video, images, text, CSV).
  - Validates file type and size before upload.
  - Uploaded files become immediately available in the system.

- **Document Access Control**:
  - Set permissions for documents on a per-user or per-role basis.
  - Option to define time-based access restrictions and download permissions.

- **Audit Trails**:
  - Logs detailed actions (creation, editing, viewing, and permission assignments) with timestamps and user details.
  - Provides filtering options (by user, date, or action type) and ensures logs are secure and tamper-proof.

- **Document Preview & Meta Tags**:
  - Preview documents (audio, video, images, text, PDF, Microsoft Office) without downloading.
  - Add, edit, and remove meta tags to facilitate easy document searches.

- **Document Versioning**:
  - Maintains a version history for each document.
  - Users can view, compare, and restore previous versions.

- **Amazon S3 Cloud Support**:
  - Integrates with Amazon S3 for secure, scalable cloud storage.
  - Ensures proper access controls and consistent retrieval performance.

- **Collaboration Features**:
  - **Comments on Documents**: Users can add, edit, and delete comments with timestamp and user details.
  - **Send Email with Documents**: Allows users to send emails with documents attached, with confirmation of successful sending.
  - **Automated Reminders**: Supports scheduling reminders (daily, weekly, monthly, etc.) with email or in-app notifications.

- **Multilingual Support**:
  - Supports multiple languages (English, Spanish, Arabic, Russian, Japanese, Korean, Chinese).
  - Users can select their preferred language via the settings menu.

- **Access Management & Reporting**:
  - Manage user roles and permissions through a dedicated interface.
  - Provides real-time updates on permission changes.
  - A dashboard displays document statistics and a calendar with reminders, updated in real time or at scheduled intervals.
  - User and role management functionality includes creation, update, deletion, and role assignments, with audit logs for tracking changes.

---
