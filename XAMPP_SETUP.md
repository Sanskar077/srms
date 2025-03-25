# XAMPP Setup Guide for MSBTE Diploma College Student Result Management System

Follow these steps to get the MSBTE Diploma College Student Result Management System working on your XAMPP installation:

## 1. Enable the MySQL PDO Extension

The error message `could not find driver` indicates that the PDO_MySQL extension is not enabled in your PHP configuration:

1. Open your XAMPP Control Panel
2. Click on the "Config" button for Apache
3. Select "PHP (php.ini)" from the dropdown menu
4. Find and uncomment this line by removing the semicolon: `;extension=pdo_mysql`
   - It should look like this after: `extension=pdo_mysql`
5. Save the file and restart Apache in XAMPP

## 2. Create and Import the Database

1. Start MySQL in XAMPP Control Panel
2. Click "Admin" button for MySQL to open phpMyAdmin
3. Create a new database named `srms`
4. Go to the "Import" tab
5. Click "Choose File" and select the `sql/srms.sql` file from the project
6. Click "Go" to import the database structure and sample data

## 3. Configure the Project

1. Extract all files from project.zip to your XAMPP's htdocs folder
   - Example: `C:\xampp\htdocs\project\`
2. Verify that includes/config.php has the correct database settings:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root'); // Default XAMPP MySQL username
   define('DB_PASS', '');     // Default XAMPP MySQL password (blank)
   define('DB_NAME', 'srms');
   define('DB_PORT', '3306');
   define('DB_TYPE', 'mysql');
   ```
3. Ensure the SITE_URL in config.php matches your folder structure:
   ```php
   define('SITE_URL', 'http://localhost/project');
   ```

## 4. Access the System

1. Open your web browser
2. Navigate to: `http://localhost/project`
3. Login using:
   - Username: `admin`
   - Password: `admin123`

## Troubleshooting

If you still encounter issues:

1. Check that you've restarted Apache after enabling the PDO_MySQL extension
2. Verify that MySQL is running in XAMPP
3. Make sure you've imported the SQL file correctly
4. Check that the project files are extracted to the correct location
5. Look for PHP error messages that may provide more information

For database connection issues, ensure the database username and password in config.php match your XAMPP MySQL credentials (usually "root" with a blank password).