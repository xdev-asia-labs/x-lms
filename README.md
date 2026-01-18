# X-LMS - Learning Management System

> A modern, scalable Learning Management System built with Laravel 12, Filament 3.2, and PostgreSQL.

[![Laravel](https://img.shields.io/badge/Laravel-12.0-red.svg)](https://laravel.com)
[![Filament](https://img.shields.io/badge/Filament-3.2-orange.svg)](https://filamentphp.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-15-blue.svg)](https://postgresql.org)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

---

## 📖 About X-LMS

X-LMS là một nền tảng quản lý học tập (LMS) hiện đại, được thiết kế để phục vụ các tổ chức giáo dục và doanh nghiệp. Hệ thống cung cấp đầy đủ các tính năng cần thiết để tạo, quản lý, và theo dõi các khóa học trực tuyến.

### ✨ Key Features

**📚 Course Management**
- Flexible post system (blog, courses, lessons, news, showcase)
- Multi-author support
- SEO-optimized content
- Tag categorization
- Rich content editor

**👥 User Management**
- Dual authentication system (Admin + Members)
- Course enrollment tracking
- Lesson progress monitoring
- Member dashboard (coming soon)

**📊 Progress Tracking**
- Time spent on lessons
- Completion status
- Learning analytics
- Enrollment history

**🎨 Admin Panel**
- Powerful Filament admin interface
- Member management
- Content management
- Newsletter subscriptions
- Analytics dashboard (coming soon)

**🔌 RESTful API**
- Public content API
- Authenticated member API
- Progress tracking endpoints
- Laravel Sanctum authentication

**🐳 Developer-Friendly**
- Docker development environment
- PostgreSQL + pgAdmin
- Mailhog for email testing
- Redis caching ready

---

## 🚀 Quick Start

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js & NPM
- PostgreSQL 15+ (or use Docker)
- Git

### Installation

1. **Clone the repository**
```bash
git clone git@github.com:xdev-asia-labs/x-lms.git
cd x-lms
```

2. **Install dependencies**
```bash
composer install
npm install
```

3. **Environment setup**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure database** (edit `.env`)
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=x_lms
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. **Run migrations**
```bash
php artisan migrate
```

6. **Seed database** (optional)
```bash
php artisan db:seed
```

7. **Build assets**
```bash
npm run build
```

8. **Start development server**
```bash
php artisan serve
```

Visit: `http://localhost:8000`  
Admin Panel: `http://localhost:8000/admin`

---

## 🐳 Docker Setup

For Docker-based development, see [DOCKER_README.md](DOCKER_README.md).

**Quick Docker start:**
```bash
make setup      # Initial setup
make up         # Start containers
make migrate    # Run migrations
```

Access:
- **App**: http://localhost:8000
- **pgAdmin**: http://localhost:5050
- **Mailhog**: http://localhost:8025

---

## 📚 Documentation

- **[FEATURES.md](FEATURES.md)** - Comprehensive feature list
- **[TODO.md](TODO.md)** - Development task tracking
- **[ROADMAP.md](ROADMAP.md)** - Quarterly milestone planning
- **[CHANGELOG.md](CHANGELOG.md)** - Version history
- **[DOCKER_README.md](DOCKER_README.md)** - Docker setup guide
- **[POSTGRESQL_SETUP.md](POSTGRESQL_SETUP.md)** - PostgreSQL configuration

---

## 🏗️ Technology Stack

**Backend:**
- [Laravel 12.0](https://laravel.com) - PHP framework
- [Filament 3.2](https://filamentphp.com) - Admin panel
- [Laravel Sanctum](https://laravel.com/docs/sanctum) - API authentication
- [PostgreSQL 15](https://postgresql.org) - Database

**Frontend:**
- [Blade](https://laravel.com/docs/blade) - Templating engine
- [Tailwind CSS](https://tailwindcss.com) - CSS framework
- [Alpine.js](https://alpinejs.dev) - JavaScript framework
- [Vite](https://vitejs.dev) - Build tool

**DevOps:**
- Docker & Docker Compose
- Nginx
- Redis
- Mailhog

---

## 🎯 Project Status

**Current Version:** 0.1.0  
**Status:** Active Development 🚧  
**Progress:** ~40% complete

### ✅ Implemented
- Course enrollment system
- Lesson progress tracking
- Content management (posts, tags, authors)
- Filament admin panel
- RESTful API endpoints
- Docker development environment

### 🔄 In Progress
- Member authentication UI
- Member dashboard
- API documentation

### 📅 Planned
- Quiz & assessment system
- Email notifications
- Payment integration
- Certificate generation
- Live streaming

See [ROADMAP.md](ROADMAP.md) for detailed timeline.

---

## 🤝 Contributing

We welcome contributions! To contribute:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'feat: add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

**Commit Convention:** Follow [Conventional Commits](https://www.conventionalcommits.org/)
- `feat:` - New features
- `fix:` - Bug fixes
- `docs:` - Documentation changes
- `refactor:` - Code refactoring
- `test:` - Adding tests
- `chore:` - Maintenance tasks

---

## 🔒 Security

If you discover a security vulnerability, please send an email to security@xdev.asia. All security vulnerabilities will be promptly addressed.

**Please do not** open public issues for security vulnerabilities.

---

## 📄 License

X-LMS is open-source software licensed under the [MIT license](LICENSE).

---

## 👥 Team

**Developed by**: [X-Dev Asia Labs](https://xdev.asia)  
**Maintained by**: Development Team  
**Repository**: [github.com/xdev-asia-labs/x-lms](https://github.com/xdev-asia-labs/x-lms)

---

## 🙏 Acknowledgments

Built with amazing open-source technologies:
- [Laravel](https://laravel.com) - The PHP Framework
- [Filament](https://filamentphp.com) - Admin Panel Builder
- [Tailwind CSS](https://tailwindcss.com) - CSS Framework
- [PostgreSQL](https://postgresql.org) - Database

---

## 📞 Support & Contact

- **Documentation**: See docs folder và markdown files
- **Issues**: [GitHub Issues](https://github.com/xdev-asia-labs/x-lms/issues)
- **Discussions**: [GitHub Discussions](https://github.com/xdev-asia-labs/x-lms/discussions)
- **Email**: support@xdev.asia

---

**Last Updated**: January 18, 2026  
**Version**: 0.1.0  
**Status**: Active Development 🚧

