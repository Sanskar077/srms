# MSBTE Diploma College Student Result Management System

A comprehensive PHP-based system for managing diploma student information, courses, subjects, examination results, and notices. Specifically designed for MSBTE (Maharashtra State Board of Technical Education) diploma colleges.

## Features

- Student Management (Add, Edit, Delete, Search, Export)
- Class Management (Add, Edit, Delete)
- Subject Management (Add, Edit, Delete)
- Result Management (Add, Edit, Delete, Search, Export)
- Notice Board for College Announcements and Updates
- Bulk Import for Students and Results
- Admin Password Change Functionality
- User Authentication
- Dashboard with Interactive Statistics and Quick Access Links
- MSBTE Diploma Course-specific Class and Subject Structure

## Requirements

- PHP 7.4 or higher
- MySQL/MariaDB or PostgreSQL database
- Web server (Apache, Nginx, etc.)

## Installation

1. **Extract the files**:
   Extract the contents of the ZIP file to your web server's document root directory.

2. **Configure Database Connection**:
   Open `includes/config.php` and update the database settings:

   ```php
   // For MySQL/MariaDB
   define('DB_HOST', 'localhost');
   define('DB_USER', 'your_username');
   define('DB_PASS', 'your_password');
   define('DB_NAME', 'srms');
   define('DB_TYPE', 'mysql'); // Change 'pgsql' to 'mysql'
   ```

   If you're using PostgreSQL, keep the current settings but update your credentials:
   ```php
   define('DB_HOST', 'your_postgres_host');
   define('DB_USER', 'your_postgres_user');
   define('DB_PASS', 'your_postgres_password');
   define('DB_NAME', 'your_postgres_database');
   define('DB_PORT', '5432');
   define('DB_TYPE', 'pgsql');
   ```

3. **Initialize Database**:
   
   For MySQL/MariaDB:
   - Create a database named 'srms' (or your chosen name)
   - Import the `sql/srms_msbte.sql` file into your database (recommended for MSBTE diploma colleges)
   - Or import `sql/srms.sql` for the basic schema without MSBTE-specific data

   For PostgreSQL:
   - Create a database with your chosen name
   - Import the `sql/srms_msbte.sql` file, which includes notice board and MSBTE-specific tables
   - The system will automatically adapt the schema as needed when first accessed

4. **Start the application**:
   - Access the application through your web browser
   - Login using the default credentials:
     - Username: `admin`
     - Password: `admin123`

## File Structure

- `/includes` - Core functions, database connection, and configuration
- `/assets` - CSS, JavaScript, and other assets
- `/sql` - Database schema
- `/uploads` - Temporary folder for file uploads

## Usage

### Student Management
- Add students individually or import in bulk using CSV
- View, edit, delete student information
- Export student data to CSV

### Result Management
- Add results individually or import in bulk using CSV
- View, edit, delete results
- Generate result reports
- Export result data to CSV

### Class & Subject Management
- Add, edit, or delete classes and subjects
- Link students to specific classes
- Link results to specific subjects

### Notice Board Management
- Add, edit, delete notices and announcements for students
- View detailed information about each notice
- Public notice board for students without requiring login
- Access all notices from the admin dashboard

## Troubleshooting

- **Database Connection Issues**: Check the database credentials in `includes/config.php`
- **Upload Errors**: Ensure the `/uploads` directory is writable
- **Missing Data**: If no students or results show up in management sections but counts appear on dashboard, check database connection and verify tables are properly created

## Security Notes

1. Change the default admin password immediately after installation
2. For production use, set `DEBUG_MODE` to `false` in `includes/config.php`