# GitHub Copilot Instructions for X-LMS

## Project Overview
X-LMS is a Learning Management System built with **Laravel 12**, **Filament 5.0**, and **PostgreSQL 15**. The project uses a dual-authentication system with separate Admin (User) and Member contexts.

## Architecture Patterns

### Dual Authentication System
- **Admin Guard** (`web`): Uses `User` model for Filament admin panel (`/admin`)
- **Member Guard** (`member`): Uses `Member` model for student/learner access (`/member`)
- Both guards defined in `config/auth.php` with separate providers and password brokers
- Member authentication uses email verification (implements `MustVerifyEmail`)

### Flexible Post System (Ghost CMS-inspired)
Posts serve multiple content types via `post_type` field:
- `blog`: Blog articles
- `course`: Course containers
- `lesson`: Individual course lessons
- `news`: News articles
- `showcase`: Portfolio/showcase items

**Key relationships:**
- Courses link to lessons via `course_id` field in posts table
- Lessons ordered by `lesson_order` field
- Tags use many-to-many with `post_tag` pivot table (includes `sort_order`)

### Filament 5.0 Resource Pattern
**Critical API changes from Filament 3.x:**
```php
// Navigation icon MUST be a method, not property
public static function getNavigationIcon(): ?string {
    return 'heroicon-o-users';
}

// Forms use Schema, not Form
public static function form(Schema $schema): Schema {
    return $schema->schema([...]);
}

// Tables still use Table type
public static function table(Table $table): Table {
    return $table->columns([...]);
}
```

## Development Workflows

### Initial Setup
```bash
composer setup  # Install deps, copy .env, generate key, migrate, build assets
```

### Frontend Assets
- **Critical**: After any Tailwind/CSS changes, run `npm run build`
- Vite manifest required: `/public/build/manifest.json`
- Tailwind uses `@tailwindcss/postcss` (v4+), not direct `tailwindcss` plugin
- Config: `postcss.config.js` must reference `'@tailwindcss/postcss'`

### Database Migrations
- PostgreSQL 15+ with UUID primary keys on all tables
- Use `php artisan migrate:fresh --seed` for clean slate
- Soft deletes enabled on: posts, tags, members
- Multi-tenancy ready (member-scoped enrollments and progress)

### Running Locally
- **Herd**: Configured for Laravel Herd (`x-lms.test`)
- **Docker**: `docker-compose up -d` for full stack (see `DOCKER_README.md`)
- Admin panel: `/admin/login` (User model)
- Member area: `/member/login` (Member model)

## Code Conventions

### Model Auto-UUID Generation
All models with UUID fields use boot method:
```php
protected static function boot() {
    parent::boot();
    static::creating(function ($model) {
        if (empty($model->uuid)) {
            $model->uuid = Str::uuid();
        }
    });
}
```

### Translation System
- Bilingual support: Vietnamese (vi) and English (en)
- Translation files: `lang/vi.json`, `lang/en.json`
- Use `__('key')` helper in Blade templates
- All auth views should use translation helpers

### API Design
- Public content API: `/api/posts`, `/api/tags` (no auth)
- Member API: Protected by `auth:member` middleware
- Laravel Sanctum for API token authentication
- Follow RESTful conventions, return JSON responses

## Key Files Reference

### Critical Resources
- **Filament Resources**: `app/Filament/Resources/*Resource.php` (Member, Post, Tag, Newsletter)
- **Models**: `app/Models/{Post,Member,Tag,CourseEnrollment,LessonProgress}.php`
- **Auth Controller**: `app/Http/Controllers/Auth/MemberAuthController.php`
- **Routes**: `routes/web.php` (member auth), `routes/api.php` (public API)

### Configuration
- **Auth guards**: `config/auth.php` (web + member guards)
- **Database**: `config/database.php` (PostgreSQL default)
- **Filament**: `app/Providers/Filament/AdminPanelProvider.php`

## Common Pitfalls

1. **Filament 5.x**: Don't use `protected static ?string $navigationIcon` - use `getNavigationIcon()` method
2. **Form vs Schema**: Form methods now return `Schema`, not `Form`. Table methods still return `Table`
3. **Asset building**: Missing Vite manifest causes 500 errors - always `npm run build` after install
4. **PostCSS**: Must use `@tailwindcss/postcss` package, not direct `tailwindcss` in plugins
5. **Dual auth**: Always specify guard in middleware: `auth:member` vs `auth:web`
6. **Member verification**: Member model implements `MustVerifyEmail` - don't forget verification routes

## Testing Strategy
- Feature tests for enrollment and progress tracking workflows
- Unit tests for model relationships and scopes
- Use `RefreshDatabase` trait for isolated tests
- Target: >70% code coverage before production
- See `TODO.md` for test coverage requirements

## Documentation
- **TODO.md**: Task tracking with priority labels (P0-P3)
- **ROADMAP.md**: Feature development roadmap by quarter
- **CHANGELOG.md**: Version history and release notes
- **README.md**: Setup instructions and feature overview
