# 🕌 SadaqahFlow - Religious Organization Fund Management System

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/TailwindCSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

**A comprehensive web-based fund management system designed for religious organizations, featuring member management, donation tracking, multi-user role-based access, and detailed financial reporting.**

[Features](#-features) • [Demo](#-demo) • [Installation](#-installation) • [Documentation](#-documentation) • [Screenshots](#-screenshots) • [Contributing](#-contributing)

</div>

---

## 📋 Overview

**SadaqahFlow** is a full-featured fund management system built for religious and spiritual organizations. Originally developed for a Sufi organization in Bangladesh, this application streamlines the entire donation collection workflow—from member registration to fund disbursement—with complete Bengali language support.

The system enables organizations to:
- 📊 Track member contributions across multiple programs and events
- 👥 Manage members (devotees) with detailed profiles
- 💰 Process donation collections with approval workflows
- 📈 Generate comprehensive financial reports
- 🔐 Control access with role-based permissions

---

## ✨ Features

### 🎯 Core Modules

| Module | Description |
|--------|-------------|
| **Dashboard** | Real-time statistics, charts, and recent activity overview |
| **Member Management** | Complete CRUD with image upload, blood type tracking, and status management |
| **Donation Collection** | Record multiple donation types (Khedmot, Manat, Kalyan, Rent) per program |
| **Fund Management** | Receive & Pay modules with approval workflows |
| **User Management** | Role assignment and member-to-collector mapping |
| **Reports** | User-wise and program-wise reports with PDF export |
| **Settings** | Program types, roles, and permission management |

### 🔐 Security & Access Control

- **Role-Based Access Control (RBAC)** using Spatie Laravel Permission
- **Hierarchical Roles**: Super Admin → Admin → Staff/Collector
- **Granular Permissions**: 20+ configurable permissions
- **Login Activity Tracking**: IP address and timestamp logging
- **CSRF Protection** on all forms

### 📱 Modern UI/UX

- **Progressive Web App (PWA)** support for mobile installation
- **Responsive Design** with TailwindCSS
- **Interactive Components**: DataTables, Select2, SweetAlert2
- **Visual Analytics** with ApexCharts
- **Bengali Language Interface** (fully localized)

---

## 🛠️ Technology Stack

| Backend | Frontend | Packages |
|---------|----------|----------|
| Laravel 10.x | Blade Templates | Spatie Permission |
| PHP 8.1+ | TailwindCSS 3.x | Intervention Image 3.x |
| MySQL 8.0+ | Alpine.js 3.x | mPDF (PDF Generation) |
| Eloquent ORM | Vite 4.x | Laravel Sanctum |

---

## 📊 Database Schema

```
┌──────────────────────────────────────────────────────────────────┐
│                        ENTITY RELATIONSHIPS                       │
├──────────────────────────────────────────────────────────────────┤
│                                                                   │
│   ┌──────────┐         ┌───────────────┐         ┌──────────┐    │
│   │  Users   │◄───────►│ MemberAssigns │◄───────►│ Members  │    │
│   └────┬─────┘         └───────────────┘         └────┬─────┘    │
│        │                                               │          │
│        │ collects                            has many  │          │
│        ▼                                               ▼          │
│   ┌──────────────────────────────────────────────────────┐       │
│   │                      Khedmots                         │       │
│   │   (date, amount, program_id, is_collected)           │       │
│   └──────────────────────────────────────────────────────┘       │
│        │                                                          │
│        │ submitted via                                            │
│        ▼                                                          │
│   ┌──────────┐         ┌──────────────┐         ┌─────────────┐  │
│   │ Receives │         │     Pays     │         │ ProgramTypes│  │
│   │(pending) │         │ (disburse)   │         │  (events)   │  │
│   └──────────┘         └──────────────┘         └─────────────┘  │
│                                                                   │
└──────────────────────────────────────────────────────────────────┘
```

### Key Entities

| Entity | Purpose |
|--------|---------|
| `users` | System users (admins, staff, collectors) |
| `members` | Organization members/devotees |
| `khedmots` | Individual donation records |
| `receives` | Fund collection submissions (with approval status) |
| `pays` | Fund disbursement records |
| `program_types` | Religious programs/events |
| `member_assigns` | User-to-member assignment (many-to-many) |
| `user_logs` | Login activity tracking |

---

## 🔄 Workflow

```
┌─────────────────────────────────────────────────────────────────────────┐
│                         DONATION COLLECTION WORKFLOW                     │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                          │
│  1️⃣ SETUP                                                                │
│     Admin creates Programs → Creates Members → Assigns Members to Staff  │
│                                                                          │
│  2️⃣ COLLECTION                                                           │
│     Staff visits Members → Records Khedmot (donation) → Status: PENDING  │
│                                                                          │
│  3️⃣ SUBMISSION                                                           │
│     Staff creates Receive request → Selects collected Khedmots           │
│                                                                          │
│  4️⃣ APPROVAL                                                             │
│     Admin reviews → Approves/Cancels → Khedmots marked COLLECTED         │
│                                                                          │
│  5️⃣ DISBURSEMENT                                                         │
│     Admin creates Pay record → Funds allocated → Balance updated         │
│                                                                          │
└─────────────────────────────────────────────────────────────────────────┘
```

---

## 🚀 Installation

### Prerequisites

- PHP >= 8.1
- Composer
- Node.js >= 18.x & npm
- MySQL >= 8.0

### Step-by-Step Setup

```bash
# 1. Clone the repository
git clone https://github.com/yourusername/sadaqahflow.git
cd sadaqahflow

# 2. Install PHP dependencies
composer install

# 3. Install Node.js dependencies
npm install

# 4. Create environment file
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Configure database in .env
# DB_DATABASE=sadaqahflow
# DB_USERNAME=your_username
# DB_PASSWORD=your_password

# 7. Run migrations and seeders
php artisan migrate --seed

# 8. Create storage symbolic link
php artisan storage:link

# 9. Build frontend assets
npm run build

# 10. Start the development server
php artisan serve
```

### Default Credentials

| Role | Email | Password |
|------|-------|----------|
| Super Admin | admin@example.com | password |

> ⚠️ **Important**: Change default credentials immediately after first login!

---

## 📁 Project Structure

```
sadaqahflow/
├── app/
│   ├── Http/
│   │   ├── Controllers/Admin/    # Admin panel controllers
│   │   ├── Middleware/           # Custom middleware
│   │   └── Requests/             # Form request validation
│   ├── Models/                   # Eloquent models
│   ├── Listeners/                # Event listeners
│   └── Providers/                # Service providers
├── database/
│   ├── migrations/               # Database schema
│   └── seeders/                  # Initial data seeders
├── resources/
│   ├── views/
│   │   ├── admin/                # Admin panel views
│   │   ├── components/           # Reusable Blade components
│   │   └── layouts/              # Layout templates
│   ├── css/                      # Stylesheets
│   └── js/                       # JavaScript files
├── routes/
│   ├── web.php                   # Web routes
│   └── api.php                   # API routes
└── public/
    └── assets/                   # Static assets
```

---

## 📖 API Endpoints

### Authentication
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/login` | User login |
| POST | `/logout` | User logout |
| POST | `/register` | User registration |

### Members
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/members` | List all members |
| POST | `/members` | Create new member |
| GET | `/members/{id}` | View member details |
| PUT | `/members/{id}` | Update member |
| DELETE | `/members/{id}` | Delete member |
| POST | `/members/search` | Search members |

### Khedmots (Donations)
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/khedmots` | List all donations |
| POST | `/khedmots` | Create donation record |
| GET | `/khedmots/{id}/edit` | Edit donation |
| PUT | `/khedmots/{id}` | Update donation |
| DELETE | `/khedmots/{id}` | Delete donation |

### Fund Management
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/fund_collections/receive` | List received funds |
| POST | `/fund_collections/receive/store` | Submit collection |
| POST | `/fund_collections/receive/collect/{id}` | Approve collection |
| GET | `/fund_collections/pay` | List disbursements |
| POST | `/fund_collections/pay/store` | Record payment |

---

## 🎨 Screenshots

<details>
<summary>📊 Dashboard</summary>

The dashboard provides a comprehensive overview with:
- Total funds collected
- Pending vs. collected amounts
- Recent donation activities
- Visual charts and statistics

</details>

<details>
<summary>👥 Member Management</summary>

Features include:
- Member listing with search and pagination
- Profile management with image upload
- Blood type and contact information
- Active/Inactive status toggle

</details>

<details>
<summary>💰 Fund Collection</summary>

Workflow includes:
- Recording individual donations
- Submitting collection requests
- Admin approval process
- Automatic status updates

</details>

---

## 🔧 Configuration

### Environment Variables

```env
# Application
APP_NAME="SadaqahFlow"
APP_ENV=production
APP_DEBUG=false

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sadaqahflow
DB_USERNAME=root
DB_PASSWORD=

# SMS (Optional - for notification support)
SMS_API_KEY=your_sms_api_key
SMS_SENDER_ID=your_sender_id
```

### Roles & Permissions

Default roles can be configured in the database seeder:

```php
// database/seeders/RoleSeeder.php
$roles = ['Super Admin', 'Admin', 'Staff'];

$permissions = [
    'view member', 'create member', 'update member', 'delete member',
    'view khedmot', 'create khedmot', 'update khedmot', 'delete khedmot',
    'view user', 'create user', 'update user', 'delete user',
    'view fund-collection', 'view report', 'view setting',
    // ... more permissions
];
```

---

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific test suite
php artisan test --testsuite=Feature
```

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Guidelines

- Follow PSR-12 coding standards
- Write meaningful commit messages
- Add tests for new features
- Update documentation as needed

---

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - The PHP Framework for Web Artisans
- [Spatie](https://spatie.be) - For the excellent Laravel Permission package
- [TailwindCSS](https://tailwindcss.com) - For the utility-first CSS framework
- All contributors who helped improve this project

---

## 📞 Contact & Support

<div align="center">

**Built with ❤️ for religious communities**

[Report Bug](../../issues) • [Request Feature](../../issues) • [Documentation](../../wiki)

</div>

---

<div align="center">

⭐ **If you find this project useful, please consider giving it a star!** ⭐

</div>

