# Blog Management System

## Setup Steps

1. **Upload Files:** Upload all the files inside this repository to your web server (e.g., inside the `htdocs` or `public_html` folder).
2. **Database Setup:** 
   - Open phpMyAdmin or your database management tool.
   - Create a new database named `blog_db` (or any other name).
   - Import the `blog_db.sql` file provided in this repository to create the required tables (`users`, `categories`, `blogs`).
3. **Database Configuration:**
   - Open `db.php` and update the database connection details:
     ```php
     $host = 'localhost';
     $dbname = 'blog_db'; // Change if your DB name is different
     $user = 'root'; // Change to your database username
     $pass = ''; // Change to your database password
     ```
4. **Make Directories Writable:** Ensure the `uploads/` folder has writable permissions (e.g., `chmod 777`) so images can be saved.

## Features
- **Frontend**
  - Responsive Blog Listing Page
  - Full Blog Detail view
  - AJAX + jQuery based Search by Title/Content
  - AJAX + jQuery based Category filtering (without page reload)
  - AJAX + jQuery based Date sorting
- **Backend (Admin Panel)**
  - Secure Login (password-hashed)
  - Add, Edit, Delete Blogs
  - Image uploading capabilities
  - Category assignment

## Technologies Used
- PHP (Core MVC-like Procedural setup for zero-dependency hosting)
- MySQL Database
- HTML/CSS (Custom responsive CSS)
- jQuery/AJAX

## Admin Login Credentials
- **Username:** admin
- **Password:** password

## Requirements Checklist Addressed
- ✅ Frontend + Backend (Core PHP)
- ✅ Database dynamic fetching
- ✅ Search + AJAX Filter by Category & Date
- ✅ Responsive Design
- ✅ Admin Panel (Add/Edit/Delete)