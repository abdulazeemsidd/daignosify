# Diagnosify - Medical Diagnostic & Prescription Portal

 Diagnosify is a web-based healthcare platform designed to streamline patient records, diagnostic reporting, and prescription management for clinics and medical consultants.

[Live Demo](https://diagnosify.infinityfree.me) 

---

## Key Features

- **Authentication & Roles:** Secure login system for Admin, Doctors, and Staff.
- **Patient Management:** Create, update, and track patient medical histories.
- **Prescription & Diagnostic Portal:** Generate digital prescriptions and export diagnostic reports as PDF.
- **Department & Qualification Management:** Dynamic department assignment for medical staff.

---

## Tech Stack

- **Frontend:** HTML5, CSS3, JavaScript, Bootstrap
- **Backend:** Core PHP
- **Database:** MySQL
- **Hosting:** InfinityFree / Apache Server

---

## My Contribution & Role

As part of a 2-member team, my core responsibilities included:
- Designed and implemented the MySQL relational database schema.
- Developed backend PHP modules for authentication and session management.
- Integrated PDF generation for diagnostic reports.
- Handled live server deployment and environment configuration.

---

## Team & Contributors

- **Azeem Siddiqui** - *Backend & Database Architecture* ([GitHub Profile](https://github.com/abdulazeemsidd))
- **Alaina Ahmed** - *Frontend & UI Design* ([GitHub Profile](https://github.com/alainaahmed-coder))

---

## How to Run Locally

1. **Clone the repository:**
   ```bash
   https://github.com/abdulazeemsidd/daignosify.git
   
2. **Setup Local Database:**
   - Open phpMyAdmin (http://localhost/phpmyadmin).
   - Create a database named diagnosify_db.
   - Import the database/diagnosify_db.sql file into it.

3. **Configure Database Connection:**
   - Open auth/db-config.php in your editor.
   - Set local credentials ($servername = "localhost", $username = "root", $password = "", $dbname = "diagnosify_db").

5. **Run Project:**
   - Place the project folder in your htdocs directory.
   - Open browser and go to `http://localhost/diagnosify/`
   
