# Clevora - Global BPO & Outsourcing Solutions

Clevora is a premium, modern BPO (Business Process Outsourcing) and offshoring portal. Established in 2011 and headquartered in Delhi, India, Clevora delivers world-class backend operations, content moderation, customer support, and IT engineering solutions.

This repository contains the complete frontend website and administrative panel for Clevora.

---

## 🌟 Key Features

- **Dynamic Homepage**: Features a premium visual design with a fully customizable BPO slide slider, statistics counter, dynamic gallery, and live customer reviews.
- **Multilingual Support**: Programmatic integration with Google Translate, allowing users to translate the entire site into Spanish, Chinese, French, Japanese, Russian, Hindi, Dutch, or Italian seamlessly without layout-breaking top bars.
- **Service Management**: 12 custom BPO services (Database Management, Content Moderation, Digital Marketing, Software Solutions, etc.) rendered with pixel-perfect modern SVG icons.
- **Responsive Layout**: Designed with responsive, clean vanilla CSS matching modern corporate branding aesthetics.
- **Admin Dashboard**: Secure backend panel (`/admin`) to manage homepage copy, service settings, client partnerships, workspace gallery, testimonials, and review user inquiries.
- **Leads & Contact System**: Seamless integration of contact forms and lead capture storing messages directly to a secure MySQL database.

---

## 🛠️ Technology Stack

- **Backend**: PHP 8.x
- **Database**: MySQL (PDO connection)
- **Frontend**: HTML5, Vanilla CSS, Alpine.js (for micro-interactions), Tailwind CSS (admin layouts)
- **Icons & Assets**: Custom SVG Vector Icons, High-Quality Stock assets

---

## 📂 Project Structure

```text
clevora/
├── admin/                  # Secure administrator dashboard
│   ├── middleware/         # Authentication checks
│   ├── sections/           # Individual dashboard sections (services, testimonials, etc.)
│   └── index.php           # Admin login portal
├── api/                    # API endpoints (e.g. contact form submit)
├── assets/
│   ├── css/                # Custom CSS stylesheet variables and layouts
│   ├── images/             # Gallery, testimonials, logos, and custom SVG icons
│   └── js/                 # Javascript slider and stat-counting observers
├── includes/               # Reusable headers, footers, database connectors, and sidebars
├── index.php               # Homepage
├── config.php              # Server configurations & credentials
└── schema.sql              # Database structure and seed queries
```

---

## 🚀 Local Setup & Installation

### 1. Prerequisites
- PHP 8.x installed locally (e.g., via XAMPP)
- MySQL database server (e.g., PHPMyAdmin/MariaDB)
- Git CLI

### 2. Database Setup
1. Open your MySQL client (like phpMyAdmin) and create a database named `clevora_db`:
   ```sql
   CREATE DATABASE clevora_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
2. Import the database tables and seed data using the provided `schema.sql` file:
   ```bash
   mysql -u root -p clevora_db < schema.sql
   ```

### 3. Server Configuration
Update your database connections and SMTP mail server configurations in [config.php](file:///c:/Users/manis/OneDrive/Desktop/clevora/config.php):
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'clevora_db');
define('DB_USER', 'root');
define('DB_PASS', 'YOUR_DATABASE_PASSWORD');
```

### 4. Running the Site Locally
Start a local PHP development server in the root of the workspace:
```bash
php -S localhost:8000
```
Open your browser and navigate to `http://localhost:8000/`.

- **Client Site**: `http://localhost:8000/`
- **Admin Portal**: `http://localhost:8000/admin` (Default credentials: `admin` / `admin`)

---

## 📄 License
This project is proprietary and built for Clevora Global Outsourcing. All assets, designs, and content are protected under copyright.
