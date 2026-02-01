# Contributing to SadaqahFlow

First off, thank you for considering contributing to SadaqahFlow! It's people like you that make this project such a great tool for religious organizations.

## 📋 Table of Contents

- [Code of Conduct](#code-of-conduct)
- [Getting Started](#getting-started)
- [How Can I Contribute?](#how-can-i-contribute)
- [Development Workflow](#development-workflow)
- [Style Guidelines](#style-guidelines)
- [Commit Messages](#commit-messages)

## 📜 Code of Conduct

This project and everyone participating in it is governed by our Code of Conduct. By participating, you are expected to uphold this code. Please report unacceptable behavior to the project maintainers.

## 🚀 Getting Started

### Prerequisites

- PHP >= 8.1
- Composer
- Node.js >= 18.x
- MySQL >= 8.0

### Setting Up Development Environment

1. Fork the repository on GitHub
2. Clone your fork locally:
   ```bash
   git clone https://github.com/YOUR_USERNAME/sadaqahflow.git
   cd sadaqahflow
   ```

3. Install dependencies:
   ```bash
   composer install
   npm install
   ```

4. Copy environment file and configure:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. Set up the database:
   ```bash
   php artisan migrate --seed
   ```

6. Start the development server:
   ```bash
   php artisan serve
   npm run dev
   ```

## 🤝 How Can I Contribute?

### Reporting Bugs

Before creating bug reports, please check existing issues to avoid duplicates. When creating a bug report, include as many details as possible:

- **Use a clear and descriptive title**
- **Describe the exact steps to reproduce the problem**
- **Provide specific examples** (code snippets, screenshots)
- **Describe the expected behavior**
- **Include your environment details** (OS, PHP version, browser)

### Suggesting Enhancements

Enhancement suggestions are tracked as GitHub issues. When creating an enhancement suggestion:

- **Use a clear and descriptive title**
- **Provide a detailed description** of the suggested enhancement
- **Explain why this enhancement would be useful**
- **List any alternative solutions** you've considered

### Pull Requests

1. Fork the repo and create your branch from `main`
2. Make your changes following our style guidelines
3. Add tests if applicable
4. Ensure the test suite passes
5. Make sure your code lints
6. Submit a pull request!

## 💻 Development Workflow

### Branch Naming Convention

- `feature/description` - New features
- `fix/description` - Bug fixes
- `docs/description` - Documentation updates
- `refactor/description` - Code refactoring
- `test/description` - Test additions or modifications

### Running Tests

```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific test file
php artisan test tests/Feature/MemberTest.php
```

### Linting

```bash
# PHP CS Fixer (if configured)
./vendor/bin/pint

# ESLint for JavaScript
npm run lint
```

## 🎨 Style Guidelines

### PHP

- Follow [PSR-12](https://www.php-fig.org/psr/psr-12/) coding standards
- Use meaningful variable and function names
- Add PHPDoc blocks for classes and methods
- Keep methods focused and concise

```php
/**
 * Store a new member in the database.
 *
 * @param  \App\Http\Requests\StoreMemberRequest  $request
 * @return \Illuminate\Http\RedirectResponse
 */
public function store(StoreMemberRequest $request): RedirectResponse
{
    // Implementation
}
```

### JavaScript

- Use ES6+ syntax
- Follow consistent indentation (2 or 4 spaces)
- Use meaningful variable names
- Add comments for complex logic

### Blade Templates

- Use components for reusable UI elements
- Keep logic minimal in views
- Use proper indentation

### CSS/TailwindCSS

- Use Tailwind utility classes when possible
- Create custom components for repeated patterns
- Follow responsive design principles

## 📝 Commit Messages

### Format

```
<type>(<scope>): <subject>

<body>

<footer>
```

### Types

- **feat**: New feature
- **fix**: Bug fix
- **docs**: Documentation changes
- **style**: Code style changes (formatting, semicolons, etc.)
- **refactor**: Code refactoring
- **test**: Adding or modifying tests
- **chore**: Maintenance tasks

### Examples

```
feat(members): add blood type filter to member list

- Added dropdown filter for blood types
- Updated member index query to support filtering
- Added unit tests for blood type filter

Closes #123
```

```
fix(khedmot): prevent duplicate entries for same member/program

Fixed an issue where collectors could accidentally create duplicate
khedmot entries for the same member and program combination.

Fixes #456
```

## 🙏 Thank You!

Your contributions help make SadaqahFlow better for religious organizations worldwide. Every contribution, no matter how small, is valued and appreciated!
