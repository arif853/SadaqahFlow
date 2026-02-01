# SadaqahFlow Documentation

Welcome to the SadaqahFlow documentation. This folder contains detailed technical documentation for developers and administrators.

## 📚 Documentation Index

| Document | Description |
|----------|-------------|
| [API.md](API.md) | Complete API endpoint documentation with request/response formats |
| [DATABASE.md](DATABASE.md) | Database schema, entity relationships, and migration details |
| [DEPLOYMENT.md](DEPLOYMENT.md) | Step-by-step deployment guides for various environments |

## 🔗 Quick Links

- [Main README](../README.md) - Project overview and quick start
- [Contributing Guide](../CONTRIBUTING.md) - How to contribute to the project
- [Security Policy](../SECURITY.md) - Security features and reporting vulnerabilities
- [Changelog](../CHANGELOG.md) - Version history and release notes

## 📖 Additional Resources

### For Developers

1. **Getting Started**
   - Clone the repository
   - Install dependencies with `composer install` and `npm install`
   - Copy `.env.example` to `.env` and configure
   - Run `php artisan migrate --seed`
   - Start with `php artisan serve`

2. **Architecture Overview**
   - Laravel 10.x MVC architecture
   - Blade templating with TailwindCSS
   - Spatie Permission for RBAC
   - Event-driven authentication logging

3. **Key Directories**
   - `app/Http/Controllers/Admin/` - Main admin controllers
   - `app/Models/` - Eloquent models
   - `resources/views/admin/` - Admin panel views
   - `database/migrations/` - Database schema

### For Administrators

1. **User Management**
   - Create users with appropriate roles
   - Assign members to collectors
   - Monitor login activity

2. **Fund Management**
   - Review pending collections
   - Approve or cancel submissions
   - Track disbursements

3. **Reporting**
   - Generate user-wise reports
   - Export to PDF
   - Filter by program and date range

## 🆘 Support

If you encounter issues not covered in the documentation:

1. Check the [GitHub Issues](../../issues) for known problems
2. Search the codebase for relevant comments
3. Open a new issue with detailed reproduction steps

---

*Last updated: January 2025*
