# Internship Task - Register / Login / Profile
Beautiful Bootstrap UI + AJAX + PHP (MySQL, MongoDB, Redis)
Prepared for XAMPP environment.

## Setup Instructions (XAMPP)

1. **Put project folder**
   - Copy the `internship_task` folder into `C:/xampp/htdocs/` (Windows).
   - So the path becomes: `C:/xampp/htdocs/internship_task`

2. **MySQL**
   - Start Apache and MySQL from XAMPP.
   - Open phpMyAdmin: http://localhost/phpmyadmin
   - Run the SQL in `sql/create_mysql.sql` (or create database & table manually):
     ```sql
     CREATE DATABASE internship_task;
     USE internship_task;
     CREATE TABLE users (
         id INT AUTO_INCREMENT PRIMARY KEY,
         name VARCHAR(100),
         email VARCHAR(100) UNIQUE,
         password VARCHAR(255),
         created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
     );
     ```

3. **MongoDB**
   - Install and start MongoDB locally (community edition).
   - Recommended DB name: `internship_task`
   - Collection: `profiles`
   - The PHP code uses the MongoDB PHP library. To install:
     - Install PHP MongoDB extension (pecl) OR use Composer.
     - From project root (if Composer installed):
       ```
       composer require mongodb/mongodb
       ```
     - The sample PHP code expects `vendor/autoload.php` if you use composer.

4. **Redis**
   - Install and run Redis server locally.
   - Install `phpredis` extension or enable appropriate PHP Redis library.

5. **PHP Requirements**
   - PHP 7.4+ recommended.
   - Enable extensions: `mysqli`, `redis`, `mongodb` (or use composer library).
   - For password hashing we use `password_hash()` and `password_verify()` (built-in).

6. **How to run**
   - Open in browser:
     - Registration: http://localhost/internship_task/register.html
     - Login: http://localhost/internship_task/login.html
     - Profile: http://localhost/internship_task/profile.html

7. **Notes**
   - All frontend calls use jQuery AJAX (no form submission).
   - MySQL queries use prepared statements.
   - The app stores basic login credentials in MySQL and profile details in MongoDB.
   - On login, a Redis key is written for the session and localStorage is used to keep the login flag on client side.

If you need, I can generate sample SQL/Mongo data and help you run composer / enable extensions.
