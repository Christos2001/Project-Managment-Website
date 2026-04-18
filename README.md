# 📋 Project-Managment-Website
A modern project management platform that allows users to create projects, define steps, assign work, and collaborate with clear role-based permissions.

---

## 🚀 Tech Stack

This project is built using:

- **Frontend:** HTML, CSS, JavaScript  
- **Backend:** PHP  
- **Database:** SQL  
- **Email Service:** PHPMailer  
- **Security:** Cloudflare (bot protection & verification)  
- **Environment:** Docker  

---
## 🛡️Security
The application includes multiple defenses to protect user data and prevent unauthorized actions.

🧱Basic protection
- Uses parameterized SQL queries everywhere to prevent SQL Injection attacks.

🔄 Data Integrity & Transactions
  - I use BEGIN_TRANSACTION and COMMIT, along with ROLLBACK for queries along with file uploads/deletions when it's needed, ensuring all actions are treated as a single unit of work.
    
🔒 Authentication & Session Security
- Email Verification: Accounts remain inactive until the user verifies their email address.
- Hardened Sessions: Uses HttpOnly and Secure cookie flags to prevent XSS-based session theft and ensure cookies are only transmitted over HTTPS.
- Persistent Auth Checks: Every php page request triggers an active session validation to prevent unauthorized access.
- Cloudflare Turnstile is used during sign-up to prevent bot registrations.

👤 Authorization & Logic Gates
- Strict RBAC (Role-Based Access Control): Permissions are verified at the server level. A user can only view or interact with projects/steps they are assigned to.
  
📁 File & Upload Protection
- Uses finfo for server-side MIME-type validation. This prevents Fake Extension Hacks.
- Isolated Storage: Project uploaded files are stored outside the public web root to prevent direct URL access and execution.
- Implements server-side sanitization and validation for all user inputs to protect against injection and ensure data integrity before database insertion.
---

## 👤 User Profile

Identity Management: 
- view Username, Telephone, and Email.
- Edit  Username, Telephone, Email and Password.
- Permanently delete their account  

---

## 📁 Projects Dashboard

The main dashboard allows users to:

- View all projects in a card-based layout  
- Search projects by title  
- Navigate through pages using pagination  
- Create new projects  

Each project includes:
- Title  
- Creator  
- Creation date  

### Project Actions (Creator Only):
- Add steps to a project  
- Remove the project

### Preview
<img width="1900" height="917" alt="ourlabIMG1" src="https://github.com/user-attachments/assets/33f558ce-328e-4382-ac2c-c3f34e7d22e2" />

---

## 🧩 Project Steps System

The system utilizes a parent-child relationship where individual projects are broken down into **Steps**

A step contains:
- Title  
- Description
- Description file
- Assigned users 
- Status 
- Solution file

❗**Note**
- A Step status is **Pending** when is created.Assigned users transition the step **In progress** and then to **Completed**.
Once Completed, the creator has to approve or dissaprove the solution. If the step is approved is getting green flagged else
it's back to in progress position.

- Description and solution file allowed types:
🖼️ Images 🖼️
JPG / JPEG
PNG
GIF
WebP
BMP
TIFF / TIF
SVG 
📄
PDF📄
📝 Word Documents📝
DOC
DOCX
DOTX

  
### 🔄 Task Lifecycle
Initialization: Every step starts as Pending.

Execution: The assigned user updates the status to In Progress when they begin work.

Submission:
- Automatic: Uploading a solution file instantly marks the step as Completed.
- Manual: If no file is required, the user can manually set the status to Completed.
  - Review State: Once submitted, the status displays as Completed — Waiting for Creator's Approval.

Creator reviews the submission:
 - ✅ **Approve** → Marks the step as Comnpleted and step is locked.
 - ❌ **Disapprove** → Status resets to **In Progress**.
   
---

## 👥 Roles & Permissions

### 🛠️ Project Creator

Creator privileges include:

- Delete project  
- Create and delete steps  
- Assign users to steps
- View assigned members 
- Approve or disapprove completed steps

#### Step review - creator's view
<img width="1402" height="842" alt="image" src="https://github.com/user-attachments/assets/3d3573ea-e9ad-46b6-b05d-3603e406cfb4" />

  

### 👨‍💻 Assigned User

Assigned user privileges include:

- View assigned members
- Upload a solution file  
- Change step status:
  - In Progress
  - Completed  

❗ **Important**
- Can only interact and view the steps they are assigned to, not the entire project.
  
#### Step review - member's view
<img width="827" height="857" alt="image" src="https://github.com/user-attachments/assets/a2ebc977-31ef-4405-aab6-2e35fdf57cfe" />

---

## 🐳 Running with Docker
It uses PHP 8.2 (Apache) and MySQL 8.0.

Setup Features:
Environment Isolation: Two-container architecture (Application and Database).

Automated Dependency Management: Uses Composer to handle library PHPMailer.

Data Persistence: Uses a Docker volume (db_data) to ensure database records are saved even if containers are restarted.

Secure File Storage:
- /img: Mounted as Read-Only for UI.

- /files: Isolated directory for sensitive project uploads, stored outside the public web root.

Initialization: Automatically imports schema.sql on the first database boot.

---
## 🛠️ Planned Future Features
This is the "Backlog" of features I plan to implement when time permits.

### Quick Wins:
- Password/Username Recovery.
- Creator assigns themselves to steps to gain execution-level privileges.
- Creator assigns multiple users at once in a step.
- I have already a column in user table that is called expiry_date (that dicides if verification link works), I will add a cron job to delete expired users that
  requires to edit verificate_email.php on /phpPages.
- Real-time Notifications.
- Phone verification through SMS.
- Turn svgs imgs that may contain malicious scripts to png on the server-side.
- Turn javascript alerts into modals.


### Advanced Enhancements:
- Step-Specific Discussion: A focused communication channel for each step:
  - Assigned Users: Can post and edit a single "Question" to keep the focus on a blocker.
  - Creators: Can provide direct feedback or corrections during the disapproval phase.
- Error Logging: Centralized error.log tracking to monitor system health and detect unauthorized access attempts.
- RemoteIP Dependency: Once site-wide proxying is enabled, a2enmod remoteip will be required to ensure the server logs the visitor's IP instead of Cloudflare's.

## 🛠️ Setup & Installation
 - In the schema.sql, there are already 2 insertions for testing
    - username: bob1 , alice_dev
    - password : password123
 - Email (SMTP): Since the app uses PHPMailer, you need an "App Password" using Gmail you can create one [here](https://myaccount.google.com/apppasswords)
 - Cloudflare Turnstile Configuration: Log in to [Cloudflare Dashboard](https://dash.cloudflare.com/login). Navigate to Turnstile in the Application Security section which is on the side menu.Add a new site and get your Site Key and Secret Key.
 - Rename .env.example to .env
   - Fill the variables with the requested values.
 - Open Command Prompt, navigate to the project folder (ourlab), and run *docker compose up*
 - Then access: http://localhost:8080/html/main.html
