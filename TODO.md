# TODO - X-LMS Development Tasks

> **Last Updated**: January 18, 2026  
> **Project**: X-LMS - Learning Management System với Laravel + Filament

---

## 🔥 Critical Priority (P0) - Must Do Before Launch

### Git & DevOps

- [x] Initialize Git repository and push to GitHub
- [ ] Setup CI/CD pipeline (GitHub Actions)
  - [ ] Automated testing on PR
  - [ ] Auto-deployment to staging environment
  - [ ] Code quality checks (PHPStan, Laravel Pint)

### Member Authentication UI

- [ ] **Login page** (`/login`)
  - [ ] Email + password authentication
  - [ ] "Remember me" functionality
  - [ ] Error validation messages
- [ ] **Registration page** (`/register`)
  - [ ] Form validation (email, password confirmation)
  - [ ] Terms & conditions checkbox
  - [ ] Auto-login after successful registration
- [ ] **Password Reset** flow
  - [ ] Forgot password page with email input
  - [ ] Reset password email template
  - [ ] Reset password page with token validation
- [ ] **Email Verification**
  - [ ] Verification email template
  - [ ] Email verification confirmation page
  - [ ] Resend verification email functionality

### API Documentation

- [ ] Install Laravel Scribe hoặc Scramble
- [ ] Document all public API endpoints
- [ ] Add authentication examples
- [ ] Create Postman/Insomnia collection
- [ ] Write API usage examples

### Testing & Quality

- [ ] Write Feature tests cho enrollment flow
- [ ] Write Feature tests cho progress tracking
- [ ] Write Unit tests cho Models
- [ ] Setup test database seeder
- [ ] Achieve >70% code coverage

---

## 📋 High Priority (P1) - Phase 1 Development

### Member Dashboard

- [ ] Dashboard overview page (`/dashboard`)
  - [ ] Enrolled courses list
  - [ ] Learning progress overview
  - [ ] Recent lesson activity
  - [ ] Upcoming deadlines (if applicable)
- [ ] Profile management page
  - [ ] Edit profile information
  - [ ] Change password
  - [ ] Profile avatar upload
  - [ ] Delete account option

### Quiz & Assessment System

- [ ] **Database Design**
  - [ ] Create `quizzes` table (title, description, course_id, pass_score)
  - [ ] Create `questions` table (quiz_id, type, content, points)
  - [ ] Create `question_options` table (question_id, content, is_correct)
  - [ ] Create `quiz_attempts` table (member_id, quiz_id, score, completed_at)
  - [ ] Create `quiz_answers` table (attempt_id, question_id, answer)
- [ ] **Backend Implementation**
  - [ ] Quiz model với relationships
  - [ ] Question model với multiple choice/true-false support
  - [ ] Quiz attempt tracking
  - [ ] Auto-grading logic
  - [ ] API endpoints cho quiz taking
- [ ] **Frontend UI**
  - [ ] Quiz listing in course detail
  - [ ] Quiz taking interface
  - [ ] Question navigation (previous/next)
  - [ ] Timer display (optional)
  - [ ] Results page với score breakdown
  - [ ] Quiz history in member dashboard
- [ ] **Filament Admin**
  - [ ] QuizResource với form builder
  - [ ] Question management (repeater field)
  - [ ] Quiz attempt monitoring
  - [ ] Grade override functionality

### Email Notification System

- [ ] Setup email templates (Blade/Mailable)
  - [ ] Welcome email after registration
  - [ ] Course enrollment confirmation
  - [ ] Lesson completion notification
  - [ ] Quiz result notification
  - [ ] Newsletter email template
- [ ] Queue configuration cho async email sending
- [ ] Email preferences in member profile
- [ ] Notification log table để tracking
- [ ] Resend failed emails functionality

### Search & Filtering

- [ ] Advanced course search
  - [ ] Search by title, description
  - [ ] Filter by category/tag
  - [ ] Filter by difficulty level
  - [ ] Sort by popularity, newest, rating
- [ ] Laravel Scout integration (optional)
- [ ] Search results pagination
- [ ] Search history tracking

---

## 🎨 Medium Priority (P2) - Phase 2 Features

### Discussion & Comments

- [ ] **Database Design**
  - [ ] Create `comments` table (commentable_type, commentable_id, member_id, content)
  - [ ] Support nested comments (parent_id)
- [ ] Comment system cho Lessons
- [ ] Comment system cho Blog Posts
- [ ] Comment moderation in Filament admin
- [ ] Like/reaction system
- [ ] Comment notifications
- [ ] Report inappropriate comments

### Course Preview & Demo

- [ ] Free preview lessons configuration
- [ ] Course trailer video support
- [ ] "Try before you buy" functionality
- [ ] Preview mode UI với locked content indicator

### Learning Analytics

- [ ] **Member Analytics Dashboard**
  - [ ] Total learning hours
  - [ ] Courses completed vs in-progress
  - [ ] Quiz performance trends
  - [ ] Learning streak tracking
- [ ] **Admin Analytics** (Filament widgets)
  - [ ] Total enrolled members
  - [ ] Popular courses chart
  - [ ] Completion rate statistics
  - [ ] Revenue analytics (if payments implemented)

### Content Management Enhancements

- [ ] Rich text editor improvements
  - [ ] Code syntax highlighting
  - [ ] Embed YouTube/Vimeo videos
  - [ ] File attachments in lessons
- [ ] Course prerequisites system
- [ ] Course difficulty levels
- [ ] Estimated completion time
- [ ] Course categories/taxonomies

---

## 🚀 Future Enhancements (P3) - Phase 3+

### Payment Integration

- [ ] Choose payment gateway (Stripe/VNPay/MoMo)
- [ ] Create `payments` table
- [ ] Create `course_pricing` table
- [ ] Paid course enrollment flow
- [ ] Payment webhook handling
- [ ] Invoice generation
- [ ] Refund functionality
- [ ] Subscription plans (optional)

### Certificate System

- [ ] Certificate template design
- [ ] Create `certificates` table
- [ ] Auto-generate certificate after course completion
- [ ] PDF certificate download
- [ ] Certificate verification page (public URL)
- [ ] Certificate revocation system
- [ ] Custom certificate templates per course

### Video Platform Integration

- [ ] Vimeo API integration
- [ ] YouTube embed support
- [ ] Custom video player (Video.js/Plyr)
- [ ] Video progress tracking
- [ ] Video playback speed control
- [ ] Video quality selector
- [ ] Subtitle/caption support

### Live Streaming

- [ ] Live class scheduling system
- [ ] Integration với Zoom/Google Meet API
- [ ] Live chat during streams
- [ ] Recording storage và playback
- [ ] Attendance tracking
- [ ] Live Q&A functionality

### Gamification

- [ ] Points/XP system
- [ ] Badges/achievements
- [ ] Leaderboard
- [ ] Daily login rewards
- [ ] Completion milestones
- [ ] Social sharing of achievements

### Mobile Optimization

- [ ] Responsive UI improvements
- [ ] Progressive Web App (PWA) support
- [ ] Offline content access
- [ ] Push notifications
- [ ] Mobile API optimization

### Advanced Features

- [ ] Multi-language support (i18n)
- [ ] Course collaboration (multiple instructors)
- [ ] Student groups/cohorts
- [ ] Assignment submission system
- [ ] Grade book
- [ ] Calendar integration
- [ ] Forum/community feature
- [ ] Course recommendation engine (AI)

---

## 🐛 Known Issues & Technical Debt

- [ ] Review và optimize database queries (N+1 problems)
- [ ] Add database indexes cho performance
- [ ] Implement caching strategy (Redis)
- [ ] Add rate limiting cho API endpoints
- [ ] Security audit (OWASP Top 10)
- [ ] Accessibility audit (WCAG 2.1)
- [ ] Performance testing (load testing)
- [ ] Setup error tracking (Sentry/Bugsnag)

---

## 📖 Documentation Tasks

- [ ] Update README.md với setup instructions
- [ ] API documentation (Swagger/OpenAPI)
- [ ] Architecture decision records (ADR)
- [ ] Database schema diagram
- [ ] User manual
- [ ] Admin panel guide
- [ ] Contributing guidelines
- [ ] Code of conduct

---

## 🔧 Development Guidelines

### Git Workflow

- **Main Branches**: `main` (production), `develop` (development)
- **Feature Branches**: `feature/quiz-system`, `feature/member-auth-ui`
- **Commit Convention**: Conventional Commits
  - `feat:` - New feature
  - `fix:` - Bug fix
  - `docs:` - Documentation changes
  - `refactor:` - Code refactoring
  - `test:` - Adding tests
  - `chore:` - Build process or auxiliary tool changes

### Code Standards

- Follow PSR-12 coding standards
- Use Laravel best practices
- Write meaningful variable names
- Add PHPDoc comments cho public methods
- Keep controllers thin (use Services)
- Use Form Requests cho validation
- Use API Resources cho consistent responses

### Testing Strategy

- Write tests cho new features
- Feature tests cho user flows
- Unit tests cho business logic
- Integration tests cho external APIs
- Maintain >70% code coverage

---

## 📊 Progress Tracking

**Overall Completion**: ~40%

- ✅ **Backend Core**: 80% complete
- ✅ **Frontend Views**: 60% complete  
- ⚠️ **Member Auth UI**: 0% complete
- ⚠️ **Quiz System**: 0% complete
- ⚠️ **Payment Integration**: 0% complete
- ⚠️ **Testing**: 20% complete

---

## 📝 Notes

- Refer to `FEATURES.md` for detailed feature specifications
- Check `ROADMAP.md` for milestone planning
- See `CHANGELOG.md` for version history
- Docker setup instructions in `DOCKER_README.md`
