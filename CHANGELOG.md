# Changelog

All notable changes to X-LMS project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [Unreleased]

### 🎯 Planned

- Member authentication UI (login, register, password reset, email verification)
- Member dashboard với enrolled courses overview
- Quiz and assessment system
- Email notification workflows
- Payment integration cho paid courses
- API documentation với Swagger/Scramble
- CI/CD pipeline setup

---

## [0.1.0] - 2026-01-18

### 🎉 Initial Release

#### ✨ Added

**Core Models & Database**

- User model với author profile fields (bio, avatar, social links, slug)
- Member model với separate authentication guard
- Post model với universal content type (blog, course, lesson, news, showcase)
- Tag model với SEO metadata và accent colors
- CourseEnrollment model cho enrollment tracking
- LessonProgress model với time tracking và completion status
- Newsletter model cho subscription management
- Database migrations với complete schema
- Soft deletes support cho User, Post, Tag models

**Course & Learning Features**

- Course enrollment system
  - Enrollment status tracking (enrolled, completed, dropped)
  - Enrolled date tracking
  - Progress percentage calculation
- Lesson progress tracking
  - Time spent tracking
  - Completion status
  - Started/Completed timestamps
  - Last accessed tracking
- Course-lesson relationship structure
- Multi-author support cho courses

**Content Management**

- Post management với flexible content types
- SEO metadata (meta_title, meta_description, og_image)
- Featured image support
- Published/draft status
- Scheduled publishing
- Slug-based URLs
- Tag categorization với many-to-many relationship
- Post-User pivot table cho multi-author

**Filament Admin Panel**

- MemberResource với full CRUD operations
  - Member listing với search và filters
  - Member creation và editing forms
  - Email verification status
- NewsletterResource
  - Subscription management
  - Email và name fields
  - Subscribed date tracking
- PostResource
  - Rich content editor
  - SEO fields
  - Author assignment
  - Tag selection
  - Featured image upload
  - Status management (draft/published)
- TagResource
  - Tag creation và management
  - Slug auto-generation
  - Accent color picker
  - SEO fields

**Public Web Interface**

- Home page (`/`)
- Blog listing page (`/blog`)
- Blog post detail page (`/blog/{slug}`)
- Course listing page (`/courses`)
- Course detail page (`/courses/{slug}`)
- Lesson detail page (`/lessons/{slug}`)
- Tag archive page (`/tags/{slug}`)
- Author profile page (`/authors/{slug}`)
- Responsive Blade layouts
- Header và footer partials
- Course card component
- Post card component

**RESTful API**

- Public Content API:
  - `GET /api/posts` - List posts với filters (type, tag, search)
  - `GET /api/posts/{slug}` - Single post detail
  - `GET /api/posts/search?q=` - Search functionality
  - `GET /api/courses/{id}/lessons` - Course lessons listing
  - `GET /api/tags` - List all tags
  - `GET /api/tags/{slug}` - Tag với associated posts
  - `POST /api/newsletter/subscribe` - Newsletter subscription
  - `POST /api/newsletter/unsubscribe` - Unsubscribe
- Authenticated Member API:
  - Progress tracking endpoints (start, update, complete lessons)
  - Course progress overview
- Laravel Sanctum integration cho API authentication
- JSON response formatting
- Pagination support

**Authentication & Authorization**

- Dual authentication system:
  - Admin users (default Laravel auth)
  - Members (separate guard)
- AuthenticateMember middleware
- RedirectIfMember middleware
- Member authentication backend (ready for UI)

**Development Environment**

- Docker Compose setup với:
  - PHP 8.2-FPM container
  - Nginx web server
  - PostgreSQL 15 database
  - pgAdmin database management
  - Redis caching
  - Mailhog email testing
- Makefile với common commands
- PostgreSQL setup script
- Environment configuration templates

**Developer Tools**

- Laravel 12.0 framework
- Filament 3.2 admin panel
- Tailwind CSS với PostCSS
- Vite build tool
- PHPUnit testing setup
- Laravel Pint code style
- Laravel Sanctum API authentication
- Doctrine DBAL cho schema management

#### 📝 Documentation

- README.md với project overview
- FEATURES.md với comprehensive feature list (295 lines)
- DOCKER_README.md với Docker setup instructions (434 lines)
- POSTGRESQL_SETUP.md với database configuration guide
- TODO.md với prioritized development tasks
- ROADMAP.md với quarterly milestone planning
- CHANGELOG.md cho version tracking
- .env.example với all configuration options

#### 🔧 Configuration

- PSR-12 coding standards
- Git initialized và pushed to GitHub
- .gitignore configured cho Laravel project
- .editorconfig cho consistent code formatting
- Composer dependencies installed
- NPM packages configured

#### 🎨 Frontend Assets

- Compiled CSS và JS assets
- Filament UI components
- Responsive Tailwind utilities
- Custom app.css và app.js
- Public assets (favicon, robots.txt)

---

## Release Notes

### Version 0.1.0 Highlights

**🚀 What's Working:**

- Complete backend infrastructure cho LMS
- Course enrollment và progress tracking logic
- Powerful admin panel với Filament
- RESTful API ready for consumption
- Docker development environment
- Multi-author content system
- SEO-friendly URLs và metadata

**⚠️ Known Limitations:**

- Member authentication UI chưa có (backend ready)
- Dashboard UI needs implementation
- Quiz/assessment system chưa có
- Payment integration chưa có
- Email notifications chưa automated
- Test coverage còn thấp (~20%)

**🎯 Next Steps:**
See [TODO.md](TODO.md) cho detailed development plan.  
Priority: Member authentication UI, quiz system, API documentation.

---

## Version History

| Version | Release Date | Status | Notes |
|---------|-------------|--------|-------|
| 0.1.0 | 2026-01-18 | ✅ Released | Initial project setup & core features |
| 0.2.0 | 2026-02-15 | 📅 Planned | Member auth UI, dashboard, tests |
| 0.3.0 | 2026-03-15 | 📅 Planned | MVP launch with production deployment |
| 1.0.0 | 2026-04-30 | 📅 Planned | Quiz system, email notifications |

---

## Contributing

When adding entries to this changelog:

1. **Add new entries at the top** under `[Unreleased]`
2. **Use clear, descriptive language**
3. **Group by category**: Added, Changed, Deprecated, Removed, Fixed, Security
4. **Reference issues/PRs** when applicable
5. **Move to versioned section** when releasing

### Categories

- **Added** for new features
- **Changed** for changes in existing functionality
- **Deprecated** for soon-to-be removed features
- **Removed** for now removed features
- **Fixed** for any bug fixes
- **Security** in case of vulnerabilities

---

**Maintained by**: X-Dev Asia Labs  
**Repository**: [github.com/xdev-asia-labs/x-lms](https://github.com/xdev-asia-labs/x-lms)  
**Documentation**: See [FEATURES.md](FEATURES.md) and [ROADMAP.md](ROADMAP.md)
