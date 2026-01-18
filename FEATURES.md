# Laravel LMS/CMS - Tài liệu Tính năng

## Tổng quan

Hệ thống LMS/CMS được xây dựng trên Laravel với Filament admin panel, hỗ trợ quản lý khóa học, bài viết, thành viên và newsletter.

## Tính năng đã triển khai

### 1. Quản lý Nội dung (Content Management)

#### Posts System

- **Loại nội dung**: Blog, Courses, Lessons, News, Showcase
- **Tính năng**:
  - SEO metadata (meta title, description, OG tags, Twitter cards)
  - Featured images với caption
  - Slug tự động
  - Soft deletes
  - Published/Draft status
  - Visibility control

#### Tags System

- Phân loại nội dung
- SEO metadata
- Accent colors
- Feature images

#### Authors (Users)

- Profile management
- Bio, avatar, cover image
- Social links (Facebook, Twitter)
- Author pages

### 2. Course & Learning Management System

#### Course Structure

- **Courses**: Khóa học chính với danh sách lessons
- **Lessons**: Bài học thuộc về một course
- **Features**:
  - Lesson ordering
  - Course preview
  - Related tags
  - Multiple authors

#### Enrollment System

- Model: `CourseEnrollment`
- **Tính năng**:
  - Enroll/Cancel enrollment
  - Track enrollment status (active, completed, cancelled)
  - Enrollment date tracking
  - Progress percentage

#### Progress Tracking

- Model: `LessonProgress`
- **Tính năng**:
  - Track lesson status (not_started, in_progress, completed)
  - Progress percentage per lesson
  - Time spent tracking
  - Auto-update course progress khi hoàn thành lessons
  - Metadata support (quiz scores, notes, bookmarks)

### 3. Member System

#### Member Authentication

- Separate authentication guard từ admin users
- Guards: `web` (admin), `member` (học viên)
- Middleware: `AuthenticateMember`, `RedirectIfMember`

#### Member Features

- Newsletter subscriptions
- Course enrollments
- Progress tracking
- Profile management

### 4. Newsletter System

- Multiple newsletters
- Member subscription management
- Auto-subscribe on signup option
- Subscription tracking với timestamps

### 5. API Endpoints

#### Content API (`/api`)

```php
GET  /api/posts                    // List all posts với filters
GET  /api/posts/{slug}             // Single post
GET  /api/posts/search             // Search posts
GET  /api/courses/{id}/lessons     // Get course lessons
GET  /api/tags                     // List tags
GET  /api/tags/{slug}              // Single tag với posts
POST /api/newsletter/subscribe     // Subscribe to newsletter
POST /api/newsletter/unsubscribe   // Unsubscribe
```

#### Progress API (requires auth:member)

```php
POST /progress/lessons/{slug}/start      // Start a lesson
PUT  /progress/lessons/{slug}            // Update progress
POST /progress/lessons/{slug}/complete   // Mark as completed
GET  /progress/courses/{slug}            // Get course progress
```

### 6. Web Routes

#### Public Routes

```php
GET /                        // Home page
GET /blog                    // Blog listing
GET /blog/{slug}             // Blog post
GET /courses                 // Courses listing
GET /courses/{slug}          // Course detail
GET /lessons/{slug}          // Lesson detail
GET /tag/{slug}              // Tag archive
GET /author/{slug}           // Author archive
```

#### Member Routes (requires auth:member)

```php
GET    /enrollments                      // Member's enrollments
POST   /enrollments/courses/{slug}       // Enroll in course
DELETE /enrollments/{id}                 // Cancel enrollment
```

## Database Schema

### New Tables

#### course_enrollments

- member_id
- course_id
- status (active, completed, cancelled)
- enrolled_at, completed_at
- progress_percentage
- metadata (JSON)

#### lesson_progress

- member_id
- lesson_id
- enrollment_id
- status (not_started, in_progress, completed)
- progress_percentage
- time_spent (seconds)
- started_at, completed_at
- metadata (JSON)

#### member_newsletter

- member_id
- newsletter_id
- subscribed_at

#### post_tag

- post_id
- tag_id
- sort_order

#### post_user (authors)

- post_id
- user_id
- sort_order

## Models & Relationships

### Post

- belongsToMany: tags, authors, enrolledMembers
- hasMany: lessons, enrollments, lessonProgress
- belongsTo: course (parent)

### Member

- hasMany: enrollments, lessonProgress
- belongsToMany: newsletters, enrolledCourses

### CourseEnrollment

- belongsTo: member, course
- hasMany: lessonProgress

### LessonProgress

- belongsTo: member, lesson, enrollment

## Views Structure

```
resources/views/
├── layouts/
│   └── app.blade.php           // Main layout
├── partials/
│   ├── header.blade.php        // Navigation
│   └── footer.blade.php        // Footer với newsletter
├── blog/
│   ├── index.blade.php         // Blog listing
│   └── show.blade.php          // Blog post
├── courses/
│   ├── index.blade.php         // Courses listing
│   └── show.blade.php          // Course detail
├── lessons/
│   └── show.blade.php          // Lesson detail với navigation
├── tag/
│   └── show.blade.php          // Tag archive
└── author/
    └── show.blade.php          // Author profile & posts
```

## Next Steps (Đề xuất)

### Tính năng cần phát triển thêm

1. **Member Authentication UI**
   - Login/Register pages
   - Password reset
   - Email verification
   - Member dashboard

2. **Quizzes & Assessments**
   - Quiz models và questions
   - Progress tracking với quiz scores
   - Certificates on completion

3. **Discussion & Comments**
   - Comments on lessons
   - Discussion forums
   - Q&A system

4. **Payment Integration**
   - Paid courses
   - Subscription plans
   - Payment gateway integration

5. **Notifications**
   - Email notifications
   - In-app notifications
   - Progress reminders

6. **Analytics**
   - Learning analytics
   - Course completion rates
   - Time spent analytics

7. **Advanced Features**
   - Video player integration
   - Live streaming
   - Certificates generation
   - Gamification (badges, points)

## Chạy Migration

```bash
php artisan migrate
```

## Seeding Data (nếu cần)

```bash
php artisan db:seed
```

## Testing

Các endpoints API có thể test bằng:

- Postman
- Insomnia
- curl

Example:

```bash
# Get all courses
curl http://localhost:8000/api/posts?type=course

# Subscribe to newsletter
curl -X POST http://localhost:8000/api/newsletter/subscribe \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com"}'
```
