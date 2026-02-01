# Changelog

All notable changes to SadaqahFlow will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Planned
- Multi-language support (English/Bengali toggle)
- SMS notification integration for donation confirmations
- Mobile app with React Native
- Export reports to Excel format
- Audit log for all financial transactions

---

## [1.0.0] - 2025-01-10

### Added
- **Dashboard Module**
  - Real-time statistics display
  - ApexCharts integration for visual analytics
  - Recent khedmots listing
  - Role-based data visibility

- **Member Management**
  - Complete CRUD operations for members
  - Profile image upload with WebP conversion
  - Blood type tracking
  - Unique Kollan ID assignment
  - Active/Inactive status toggle
  - Search functionality with throttling

- **Donation Collection (Khedmot)**
  - Multiple donation types: Khedmot, Manat, Kalyan, Rent
  - Program-based donation tracking
  - Duplicate entry prevention
  - Collection status tracking
  - Date and collector-based filtering

- **Fund Management**
  - Receive module for collection submissions
  - Multi-stage approval workflow (Pending → Collected/Canceled)
  - Pay module for fund disbursements
  - Balance calculation

- **User Management**
  - User CRUD with role assignment
  - Member-to-collector assignment (many-to-many)
  - User status management
  - Login activity tracking

- **Role & Permission System**
  - Spatie Laravel Permission integration
  - Dynamic role creation
  - Granular permission assignment
  - Grouped permission display

- **Program Type Management**
  - Religious program/event definition
  - Date-based program scheduling
  - Status toggle

- **Reports**
  - User-wise report generation
  - Program-wise filtering
  - PDF export using mPDF

- **Profile Management**
  - User profile editing
  - Password change functionality
  - Account deletion

### Technical Features
- Laravel 10.x framework
- TailwindCSS 3.x for responsive design
- Alpine.js 3.x for interactivity
- Vite 4.x build system
- PWA support with service worker
- Bengali language interface
- Rate limiting on search endpoints
- CSRF protection on all forms
- Image optimization with Intervention Image 3.x

### Security
- Role-based access control (RBAC)
- Password hashing with bcrypt
- Authorization checks on all controllers
- Login activity logging (IP + timestamp)
- Input validation with custom Bengali error messages

---

## [0.9.0] - 2024-12-20

### Added
- Initial beta release
- Core member and khedmot functionality
- Basic user authentication with Laravel Breeze
- Foundation for role-based permissions

### Changed
- Migrated from Bootstrap to TailwindCSS
- Updated to Laravel 10.x from Laravel 9.x

---

## Version History Summary

| Version | Date | Highlights |
|---------|------|------------|
| 1.0.0 | 2025-01-10 | Full release with all core modules |
| 0.9.0 | 2024-12-20 | Beta release with core functionality |

---

## Upgrade Notes

### Upgrading to 1.0.0

1. Back up your database before upgrading
2. Run `composer update` to update dependencies
3. Run `php artisan migrate` to apply new migrations
4. Clear cache: `php artisan cache:clear && php artisan config:clear`
5. Rebuild assets: `npm run build`
