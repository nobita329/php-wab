# Deployment & Installation Guide

This guide details how to install and run the 3D Portfolio & CMS on **cPanel Shared Hosting** and in a **Local Development Environment**.

---

## Part 1: Quick Local Testing (Zero Dependencies)

The application comes pre-configured with support for both SQLite (zero setup) and MySQL.

1. **Start the PHP Built-in Server**:
   ```bash
   php -S 127.0.0.1:8000
   ```
2. **Access the Public Website**:
   Open [http://localhost:8000](http://localhost:8000) in your web browser.

3. **Access the Admin Panel**:
   Open [http://localhost:8000/admin/login.php](http://localhost:8000/admin/login.php)
   - **Default Admin Email**: `admin@cyberpulse.dev`
   - **Default Admin Password**: `Admin@Portfolio2026!`

4. *(Optional)* **Web Setup Wizard**:
   If starting from a fresh environment, visit `http://localhost:8000/install.php` to run the interactive installer.

---

## Part 2: cPanel Shared Hosting Deployment

### Step 1: Upload Files
1. Log in to your cPanel dashboard.
2. Open **File Manager** and navigate to your domain's document root (typically `public_html/`).
3. Upload all project files and folders to `public_html/`.
   - If deploying to a primary domain, upload the files directly into `public_html/`.
   - Ensure the `.htaccess` and `.env.example` files are uploaded (in cPanel File Manager, click **Settings** (top right) and check **Show Hidden Files (dotfiles)**).

### Step 2: Create MySQL Database in cPanel
1. In cPanel, navigate to **MySQL Databases** or **MySQL Database Wizard**.
2. **Create New Database**: e.g., `youruser_portfolio`.
3. **Create New User**: e.g., `youruser_dbuser` with a strong password.
4. **Add User to Database**: Select the user and database, and check **ALL PRIVILEGES**. Note down:
   - Database Name: `youruser_portfolio`
   - Database Username: `youruser_dbuser`
   - Database Password: `your_secure_password`

### Step 3: Import the Database Schema
1. In cPanel, click **phpMyAdmin**.
2. In the left sidebar, select your newly created database (`youruser_portfolio`).
3. Click the **Import** tab in the top navigation.
4. Click **Choose File** and select `database/database.sql` from your project files.
5. Click **Import** (or **Go** at the bottom).
6. All 15 tables and initial sample data are now imported!

### Step 4: Configure Environment (`.env`)
1. In cPanel File Manager, copy `.env.example` to `.env` (or edit the existing `.env`).
2. Update the database credentials to match your cPanel MySQL details:
   ```ini
   APP_NAME="Alex Vance | 3D Portfolio & CMS"
   APP_ENV=production
   APP_DEBUG=false
   APP_URL="https://yourdomain.com"
   APP_TIMEZONE="UTC"

   # Database (MySQL on cPanel)
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=youruser_portfolio
   DB_USERNAME=youruser_dbuser
   DB_PASSWORD=your_secure_password

   # Security
   SESSION_LIFETIME=7200
   SESSION_SECURE=true
   MAX_LOGIN_ATTEMPTS=5
   LOGIN_LOCKOUT_MINUTES=15
   MAX_FILE_SIZE=10485760
   ```
3. Save the `.env` file.

### Step 5: Verify Permissions
Set the following recommended Linux permissions:
- Directories: `755`
- Standard files: `644`
- `storage/` directory and its subfolders: `755` (writable)
- `public/uploads/`: `755` (writable)
- `.env` file: `600` or `640` (protected from other users on shared servers)

The root `.htaccess` automatically denies public access to `.env`, `storage/`, `config/`, and `database/`.

### Step 6: Enforce HTTPS (SSL)
In cPanel, open **SSL/TLS Status** and ensure **AutoSSL** has issued a free Let's Encrypt / cPanel certificate for your domain.

---

## Part 3: Web-Based Setup Wizard (Alternative)

Instead of manually editing `.env` and importing via phpMyAdmin, you can use the built-in installer:

1. Create an empty database in cPanel MySQL.
2. In your browser, navigate to `https://yourdomain.com/install.php`.
3. The wizard will verify server requirements:
   - PHP 8.2+
   - PDO MySQL / SQLite
   - File permissions for `storage/` and `uploads/`
4. Enter your MySQL database credentials and desired Administrator email and password.
5. Click **Deploy Schema & Initialize Site**.
6. The installer will write `.env`, import the schema, create your administrator account, and generate `storage/installed.lock` to secure itself.

---

## Part 4: Admin Panel Credentials

- **URL**: `https://yourdomain.com/admin/login.php`
- **Default Email**: `admin@cyberpulse.dev`
- **Default Password**: `Admin@Portfolio2026!`

*(You can change your password immediately after login in **Admin Profile** menu #20).*

---

## Part 5: Troubleshooting & Verification

| Issue | Likely Cause | Solution |
|---|---|---|
| **White Screen / 500 Error** | PHP version lower than 8.2 | In cPanel **MultiPHP Manager**, set domain PHP version to PHP 8.2 or 8.3+. |
| **Database Connection Refused** | Incorrect DB name or password in `.env` | Verify DB name and user prefix (e.g. `cpaneluser_db`). |
| **Uploads Fail** | Folder permissions or max size | Ensure `public/uploads/` is chmod `755` and uploaded file is under 10MB. |
| **3D Canvas is Blank** | WebGL disabled in browser | The site will automatically fall back to 2D canvas particle constellation. Enable hardware acceleration in your browser settings. |
| **CSS / 404 on Subpages** | Apache `mod_rewrite` disabled | Check that `.htaccess` is present and `mod_rewrite` is enabled on the server. |

