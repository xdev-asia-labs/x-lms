# PostgreSQL Setup Guide

## Cấu hình PostgreSQL cho Laravel LMS

### 1. Cài đặt PostgreSQL

**macOS (Homebrew):**

```bash
brew install postgresql@16
brew services start postgresql@16
```

**Ubuntu/Debian:**

```bash
sudo apt update
sudo apt install postgresql postgresql-contrib
sudo systemctl start postgresql
sudo systemctl enable postgresql
```

**Windows:**
Download từ <https://www.postgresql.org/download/windows/>

### 2. Tạo Database và User

```bash
# Truy cập PostgreSQL shell
psql postgres

# Hoặc với sudo trên Linux
sudo -u postgres psql
```

Trong PostgreSQL shell:

```sql
-- Tạo user
CREATE USER laravel_lms WITH PASSWORD 'your_secure_password';

-- Tạo database
CREATE DATABASE laravel_lms_db;

-- Grant quyền
GRANT ALL PRIVILEGES ON DATABASE laravel_lms_db TO laravel_lms;

-- PostgreSQL 15+ cần thêm quyền cho schema
\c laravel_lms_db
GRANT ALL ON SCHEMA public TO laravel_lms;
GRANT CREATE ON SCHEMA public TO laravel_lms;

-- Thoát
\q
```

### 3. Cập nhật file .env

Thay đổi các dòng sau trong file `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=laravel_lms_db
DB_USERNAME=laravel_lms
DB_PASSWORD=your_secure_password
```

### 4. Cài đặt PHP PostgreSQL Extension

**macOS:**

```bash
pecl install pgsql
```

**Ubuntu/Debian:**

```bash
sudo apt-get install php-pgsql
```

**Kiểm tra extension:**

```bash
php -m | grep pgsql
```

### 5. Chạy Migrations

```bash
# Clear cache
php artisan config:clear
php artisan cache:clear

# Chạy migrations
php artisan migrate

# Nếu cần fresh install
php artisan migrate:fresh

# Với seeder (nếu có)
php artisan migrate:fresh --seed
```

### 6. Verify Database

```bash
# Kết nối vào database
psql -U laravel_lms -d laravel_lms_db

# List tables
\dt

# Mô tả table
\d table_name

# Thoát
\q
```

## PostgreSQL vs MySQL Differences

### Các điểm khác biệt quan trọng

1. **ENUM Types**
   - PostgreSQL tạo enum type riêng biệt
   - Laravel tự động xử lý trong migrations

2. **Boolean**
   - PostgreSQL có kiểu BOOLEAN native
   - MySQL dùng TINYINT(1)

3. **JSON**
   - PostgreSQL: JSONB (binary, nhanh hơn)
   - Hỗ trợ indexing và querying tốt hơn

4. **Full Text Search**
   - PostgreSQL có full-text search mạnh mẽ built-in
   - Có thể dùng cho search features

5. **Case Sensitivity**
   - PostgreSQL: Case-sensitive cho string comparison
   - Dùng ILIKE thay vì LIKE cho case-insensitive

## Tips cho PostgreSQL

### 1. Indexes

```php
// Tạo GIN index cho JSON columns
$table->index('metadata', null, 'gin');

// Full-text search index
$table->rawIndex('to_tsvector(\'english\', title)', 'posts_title_fts');
```

### 2. Array Columns

```php
// PostgreSQL hỗ trợ array columns
$table->json('tags_array'); // Hoặc dùng native array
```

### 3. UUID Performance

```php
// Dùng UUID extension
Schema::create('table', function (Blueprint $table) {
    $table->uuid('id')->primary();
    // ...
});
```

### 4. Transactions

PostgreSQL có transaction handling tốt hơn MySQL cho complex operations.

## Troubleshooting

### Lỗi: "SQLSTATE[08006] Connection refused"

- Kiểm tra PostgreSQL service đang chạy
- Kiểm tra port 5432 không bị block

### Lỗi: "SQLSTATE[42501] insufficient privilege"

```sql
-- Cấp quyền đầy đủ
GRANT ALL PRIVILEGES ON DATABASE laravel_lms_db TO laravel_lms;
GRANT ALL ON SCHEMA public TO laravel_lms;
```

### Lỗi: "SQLSTATE[42P01] relation does not exist"

- Table chưa được tạo, chạy migrations
- Kiểm tra search_path trong config

### Performance Optimization

```sql
-- Analyze tables sau khi insert data
ANALYZE;

-- Vacuum database định kỳ
VACUUM ANALYZE;
```

## Backup & Restore

### Backup

```bash
# Backup toàn bộ database
pg_dump -U laravel_lms laravel_lms_db > backup.sql

# Chỉ backup schema
pg_dump -U laravel_lms -s laravel_lms_db > schema.sql

# Chỉ backup data
pg_dump -U laravel_lms -a laravel_lms_db > data.sql
```

### Restore

```bash
# Restore từ backup
psql -U laravel_lms laravel_lms_db < backup.sql

# Drop và recreate database trước khi restore
dropdb -U laravel_lms laravel_lms_db
createdb -U laravel_lms laravel_lms_db
psql -U laravel_lms laravel_lms_db < backup.sql
```

## Docker Setup (Optional)

```yaml
# docker-compose.yml
version: '3.8'
services:
  postgres:
    image: postgres:16-alpine
    environment:
      POSTGRES_DB: laravel_lms_db
      POSTGRES_USER: laravel_lms
      POSTGRES_PASSWORD: your_secure_password
    ports:
      - "5432:5432"
    volumes:
      - postgres_data:/var/lib/postgresql/data

volumes:
  postgres_data:
```

Chạy:

```bash
docker-compose up -d
```

## Monitoring

### Useful PostgreSQL Queries

```sql
-- Check database size
SELECT pg_size_pretty(pg_database_size('laravel_lms_db'));

-- Check table sizes
SELECT
    schemaname,
    tablename,
    pg_size_pretty(pg_total_relation_size(schemaname||'.'||tablename)) AS size
FROM pg_tables
WHERE schemaname = 'public'
ORDER BY pg_total_relation_size(schemaname||'.'||tablename) DESC;

-- Active connections
SELECT * FROM pg_stat_activity WHERE datname = 'laravel_lms_db';

-- Index usage
SELECT
    schemaname,
    tablename,
    indexname,
    idx_scan as index_scans
FROM pg_stat_user_indexes
ORDER BY idx_scan DESC;
```
