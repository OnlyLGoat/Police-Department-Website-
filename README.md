# APDProject - Police Department Management System

## Overview

APDProject is a web-based management system for a police department. It allows officers and civilians to submit, manage, and review various reports (activity, incident, impound, and civilian reports), manage cases, and handle user authentication. The project is built with PHP and uses Bootstrap for the frontend.

## Features

- Officer and civilian authentication and session management
- Activity, incident, impound, and civilian report creation and management
- Case management with priorities and notes
- Email notifications (using PHPMailer)
- Responsive UI with Bootstrap and Bootstrap Icons
- Role-based navigation and access control

## Folder Structure

- `APDProject/` - Main application source code
  - `activity_rapport.php` - Activity reports for officers
  - `civiliant_rapport.php` - Civilian report submission
  - `manage_civilliant_rapport.php` - Admin/Officer management of civilian reports
  - `create_activity.php`, `create_case.php`, `create_report.php`, etc. - Forms for creating new records
  - `header.php`, `header2.php`, `header3.php` - Navigation and layout includes
  - `mailer/` - Email sending functionality (uses PHPMailer)
- `mailer/phpmailer/phpmailer/` - [PHPMailer](https://github.com/PHPMailer/PHPMailer) library for sending emails

## Requirements

- PHP 7.0 or higher
- MySQL/MariaDB (for database)
- Web server (Apache recommended)
- Composer (for managing PHPMailer dependencies, if needed)

## Setup

1. **Clone or extract the project files** into your web server's root directory.
2. **Configure the database**:
   - Create a MySQL database and import your schema.
   - Update database credentials in `connect.php`.
3. **Install dependencies** (if using Composer for PHPMailer):
   ```sh
   composer install
   ```
4. **Set up mail sending**:
   - Configure SMTP settings in your mailer scripts (see `SignupCM.php` and `mail.php`).
5. **Access the application** via your browser at `http://localhost/APDProject/`.

## Usage

- Officers and civilians can log in and access their respective dashboards.
- Civilians can submit reports via `civiliant_rapport.php`.
- Officers can manage reports and cases via their dashboard.
- Admins can add officers and manage all records.

## License

- PHPMailer is licensed under LGPL 2.1. See [`mailer/phpmailer/phpmailer/LICENSE`](mailer/phpmailer/phpmailer/LICENSE).
- Other code: Add your own license information here.

## Credits

- [PHPMailer](https://github.com/PHPMailer/PHPMailer) for email functionality
- Bootstrap and Bootstrap Icons for UI

---

*This README is a template. Please update it with your specific project
