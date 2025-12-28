Course Information
Course: INFO2180 – Web Development
Institution: The University of the West Indies, Mona Campus
Project: Project 2 – Dolphin CRM
Group Members: Christina Blye, Jonathon Jones, Shevaunie Senior & Tiejheannee Gibson

About the project - 
Dolphin CRM is a lightweight Customer Relationship Management (CRM) system developed as part of INFO2180 – Web Development.
The system allows an organization to manage users and contacts, assign contacts to users, and track customer relationships through a secure, role-based web interface.

Technologies Used –
•	PHP
•	MySQL (phpMyAdmin)
•	HTML/CSS
•	JavaScript
•	Apache (XAMPP)
How to set it up – 
1.	Clone the repository
2.	Move the project into htdocs
3.	Start Apache and MySQL in XAMPP
4.	Import schema.sql into phpMyAdmin
5.	Open http://localhost/INFO2180_Project2/login.php

Default Login –
Admin Login:
 - Email: admin@project2.com
 - Password: Password123!

Authentication
•	Users authenticate using email and password
•	Passwords are securely stored using password hashing
•	Invalid login attempts display user-friendly error messages
•	Sessions are used to manage authentication and access control
•	Users are redirected to the login page if not authenticated

User roles & permissions
•	Administrator
  o	Log in to the system
  o	Add users
  o	View users
  o	Add contacts
  o	View all contacts
  o	Assign contacts to users
  o	View contacts assigned to themselves
•	Regular user
  o	Log in to the system
  o	Add contacts
  o	View contacts
  o	View contacts assigned to themselves

User Management -
Administrators can:
•	Add new users with roles (administrator or user)
•	View a list of all registered users
•	View user details such as name, email, role, and creation date

Contact Management
Authenticated administrator users can:
•	Add new contacts with the following details:
  o	Title
  o	First name
  o	Last name
  o	Email
  o	Telephone
  o	Company
  o	Type (Sales Lead or Support)
  o	Assigned user
•	View all contacts in a structured dashboard table
•	View individual contact details

Dashboard & Filtering
The dashboard provides:
•	A unified view of all contacts
•	Filters to display:
  o	All contacts
  o	Sales Leads
  o	Support contacts
  o	Contacts assigned to the logged-in user
•	Visual badges to clearly distinguish contact types

User Interface
•	Modern dark UI with neon accents
•	Consistent layout across all pages (top navigation, sidebar, main content)
•	Responsive and clean design
•	Interactive elements such as:
  o	Hover effects
  o	Active navigation highlights
  o	Password visibility toggle

Database Design
The system uses a MySQL database with:
•	USERS table for user accounts
•	Contacts table for customer data
•	Notes table for future extensibility
•	Audit_Log table for tracking changes
•	Triggers to:
  o	Sanitize input
  o	Prevent duplicate contacts
  o	Log insert and update actions

Validation & Security
•	Server-side form validation
•	Email format validation
•	Role-based access control
•	Prepared SQL statements to prevent SQL injection
•	Session-based authorization

Known issues / assumptions
•	Requires MySQL
•	Requires schema.sql to be imported
•	Uses PHP sessions

