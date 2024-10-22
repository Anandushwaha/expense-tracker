# expense-tracker
#Installation:

PHP (version 7.4+)
MySQL (for database)
Composer (for dependency management)
XAMPP or any other local server environment

#RUN THIS COMMAND ON CONSOLE:

composer require phpmailer/phpmailer
composer require phpoffice/phpspreadsheet

#SET UP THE DATABASE:

Create a new MySQL database (e.g., expense_tracker).

Import the userdetails.sql file (located in the sql/ directory) into your database.
Update the database connection settings in the php/db_connection.php file:
php

$servername = "your-server-name";
$username = "your-database-username";
$password = "your-database-password";
$dbname = "expense_tracker";

#Configure PHPMailer:

In the php/forgot_password.php file, configure your PHPMailer settings for sending emails.
Make sure to provide your SMTP server details, including the email address and password for sending emails.
Run the application:

Open XAMPP (or your preferred local server) and start Apache and MySQL.
Navigate to http://localhost/expense-tracker/html/login.html to start using the application.
