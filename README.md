# WellCure Network Hospital - Deployment Guide

This guide provides instructions for deploying the WellCure Network Hospital website to a web server.

## Table of Contents
- [Prerequisites](#prerequisites)
- [Local Development Setup](#local-development-setup)
- [Production Deployment](#production-deployment)
- [Database Setup](#database-setup)
- [Configuration](#configuration)
- [Troubleshooting](#troubleshooting)

## Prerequisites

To deploy this website, you'll need:

- Web server with PHP 7.0+ support (Apache or Nginx recommended)
- MySQL 5.7+ or MariaDB 10.2+
- PHP with MySQLi extension enabled
- FTP client or SSH access to your server

## Local Development Setup

### Using XAMPP (Windows)

1. Install [XAMPP](https://www.apachefriends.org/index.html) if you haven't already
2. Clone or copy this repository to `C:\xampp\htdocs\hospitizer\`
3. Start Apache and MySQL services from the XAMPP Control Panel
4. Open your browser and navigate to `http://localhost/hospitizer/`

### Using MAMP (Mac)

1. Install [MAMP](https://www.mamp.info/) if you haven't already
2. Clone or copy this repository to `/Applications/MAMP/htdocs/hospitizer/`
3. Start the MAMP servers
4. Open your browser and navigate to `http://localhost:8888/hospitizer/`

## Production Deployment

### Shared Hosting

1. Download the entire project as a ZIP file
2. Extract the files on your local computer
3. Upload all files to your web hosting account using FTP
   - Upload to the public_html, www, or httpdocs directory (depending on your hosting provider)
4. Set up the database (see [Database Setup](#database-setup))
5. Update the database configuration (see [Configuration](#configuration))
6. Uncomment the HTTPS redirect in the .htaccess file if your site uses SSL

### VPS or Dedicated Server

1. Connect to your server via SSH
2. Navigate to your web server's document root (e.g., `/var/www/html/`)
3. Clone the repository or upload the files
4. Set proper permissions:
   ```
   chmod -R 755 /var/www/html/hospitizer
   chmod -R 777 /var/www/html/hospitizer/logs  # If you create a logs directory
   ```
5. Set up the database (see [Database Setup](#database-setup))
6. Update the database configuration (see [Configuration](#configuration))
7. Configure your web server (Apache/Nginx) to serve the site

## Database Setup

### Automatic Setup (Recommended)

1. Create a new MySQL database (e.g., `contact_db`)
2. Create a database user and grant all privileges on the database
3. Update the database connection parameters in `db_config.php` (see [Configuration](#configuration))
4. Run the deployment script to automatically create the required tables:
   ```
   php deploy.php
   ```
   This script will:
   - Check your database connection
   - Create the required tables if they don't exist
   - Verify file permissions
   - Check PHP version and extensions
   - Provide a deployment readiness summary

### Manual Setup

If you prefer to set up the database manually:

1. Create a new MySQL database (e.g., `contact_db`)
2. Create a database user and grant all privileges on the database
3. Import the SQL files to create the required tables:
   - Using phpMyAdmin:
     - Open phpMyAdmin and select your database
     - Click on the "Import" tab
     - Upload and import `contact_form.sql`
     - Upload and import `ambulance_bookings.sql`
   - Using MySQL command line:
     ```
     mysql -u username -p contact_db < contact_form.sql
     mysql -u username -p contact_db < ambulance_bookings.sql
     ```

## Configuration

### Database Configuration

1. Open `db_config.php`
2. Update the database connection parameters:
   ```php
   $db_host = 'localhost';     // Your database host
   $db_user = 'your_username'; // Your database username
   $db_pass = 'your_password'; // Your database password
   $db_name = 'contact_db';    // Your database name
   ```

### Web Server Configuration

#### Apache

The included `.htaccess` file contains the necessary Apache configuration. If you're using a subdirectory, update the `RewriteBase` directive:

```
RewriteBase /your-subdirectory/
```

For production, uncomment the HTTPS redirect:

```
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

#### Nginx

If you're using Nginx, create a server block configuration:

```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/html/hospitizer;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$args;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

## Troubleshooting

### Database Connection Issues

- Verify your database credentials in `db_config.php`
- Ensure the MySQL server is running
- Check if the database and tables exist
- Verify that the database user has the necessary permissions

### 404 Errors

- Check if the .htaccess file is being processed (Apache needs mod_rewrite enabled)
- Verify the RewriteBase path in .htaccess matches your installation directory
- Ensure all files have the correct permissions

### PHP Errors

- Check your server's PHP error logs
- Temporarily enable error display for debugging:
  ```php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  ```

## Support

For additional help, please contact:
- Email: mathurtanmay03@gmail.com
- Phone: +919079778688

---

© 2025 WellCure Network by TanmayMatt. All Rights Reserved.
