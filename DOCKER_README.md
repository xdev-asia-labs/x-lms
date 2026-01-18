# 🐳 Docker Setup - Laravel LMS

Hướng dẫn setup và chạy Laravel LMS với Docker.

## 📋 Yêu cầu

- Docker Desktop (Docker Engine 20.10+)
- Docker Compose v2.0+
- 4GB RAM tối thiểu
- 10GB disk space

## 🚀 Quick Start

### 1. Clone project và setup

```bash
# Clone repository
git clone <your-repo-url>
cd laravel-lms-cms

# Cấp quyền execute cho script
chmod +x docker.sh setup-postgresql.sh

# Chạy full setup
./docker.sh setup
```

Script sẽ tự động:

- ✅ Tạo file .env từ .env.example
- ✅ Build Docker images
- ✅ Start containers
- ✅ Install Composer dependencies
- ✅ Install NPM dependencies
- ✅ Generate application key
- ✅ Run migrations

### 2. Truy cập ứng dụng

Sau khi setup xong:

| Service | URL | Credentials |
|---------|-----|-------------|
| **Application** | <http://localhost:8000> | - |
| **pgAdmin** | <http://localhost:5050> | <admin@example.com> / admin |
| **Mailhog** | <http://localhost:8025> | - |
| **PostgreSQL** | localhost:5432 | laravel_lms / laravel_lms_password |
| **Redis** | localhost:6379 | - |

## 📦 Services

### Stack bao gồm

1. **app** - PHP 8.3 FPM với Laravel
2. **nginx** - Web server
3. **postgres** - PostgreSQL 16 database
4. **redis** - Cache và session store
5. **queue** - Laravel queue worker
6. **scheduler** - Laravel task scheduler
7. **node** - Node.js cho Vite dev server
8. **pgadmin** - PostgreSQL GUI management tool
9. **mailhog** - Email testing tool

## 🛠️ Quản lý Containers

### Sử dụng script docker.sh

```bash
# Menu tương tác
./docker.sh

# Hoặc chạy trực tiếp commands:
./docker.sh setup      # Full setup
./docker.sh start      # Start containers
./docker.sh stop       # Stop containers
./docker.sh restart    # Restart containers
./docker.sh status     # Show status
./docker.sh logs       # Show logs
./docker.sh shell      # Laravel shell
./docker.sh psql       # PostgreSQL shell
./docker.sh clean      # Remove all containers & volumes
```

### Hoặc dùng docker-compose trực tiếp

```bash
# Start services
docker-compose up -d

# Stop services
docker-compose stop

# View logs
docker-compose logs -f

# View logs của service cụ thể
docker-compose logs -f app

# Restart service
docker-compose restart app

# Remove everything
docker-compose down -v
```

## 💻 Development Commands

### Laravel Artisan

```bash
# Chạy artisan commands
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
docker-compose exec app php artisan make:controller ExampleController
docker-compose exec app php artisan queue:work

# Clear cache
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan view:clear
```

### Composer

```bash
# Install packages
docker-compose exec app composer install

# Add package
docker-compose exec app composer require vendor/package

# Update packages
docker-compose exec app composer update
```

### NPM / Node

```bash
# Install packages
docker-compose exec node npm install

# Add package
docker-compose exec node npm install --save package-name

# Build assets
docker-compose exec node npm run build

# Dev server (auto-refresh)
docker-compose exec node npm run dev
```

### Database

```bash
# Connect to PostgreSQL
docker-compose exec postgres psql -U laravel_lms -d laravel_lms_db

# Backup database
docker-compose exec postgres pg_dump -U laravel_lms laravel_lms_db > backup.sql

# Restore database
docker-compose exec -T postgres psql -U laravel_lms laravel_lms_db < backup.sql

# Fresh migration
docker-compose exec app php artisan migrate:fresh --seed
```

### Redis

```bash
# Connect to Redis CLI
docker-compose exec redis redis-cli

# Clear Redis cache
docker-compose exec redis redis-cli FLUSHALL
```

## 🔧 Configuration

### Environment Variables

File chính: `.env`

Mẫu cho Docker: `.env.docker`

**Quan trọng:** Các services trong Docker network communicate qua service name:

- Database host: `postgres` (không phải `localhost`)
- Redis host: `redis`
- Mail host: `mailhog`

### Custom Ports

Thay đổi ports trong `.env`:

```env
APP_PORT=8000
DB_PORT=5432
REDIS_PORT=6379
PGADMIN_PORT=5050
MAILHOG_PORT=8025
VITE_PORT=5173
```

### PHP Configuration

Edit: `docker/php/php.ini`

```ini
memory_limit = 512M
upload_max_filesize = 100M
post_max_size = 100M
```

Sau khi sửa, restart container:

```bash
docker-compose restart app
```

### Nginx Configuration

Edit: `docker/nginx/conf.d/app.conf`

Restart nginx:

```bash
docker-compose restart nginx
```

## 🐛 Debugging

### View Logs

```bash
# All services
docker-compose logs -f

# Specific service
docker-compose logs -f app
docker-compose logs -f nginx
docker-compose logs -f postgres

# Last 100 lines
docker-compose logs --tail=100 app
```

### Check Container Status

```bash
docker-compose ps
```

### Enter Container Shell

```bash
# Laravel app
docker-compose exec app bash

# PostgreSQL
docker-compose exec postgres bash

# Nginx
docker-compose exec nginx sh
```

### Check Service Health

```bash
# PostgreSQL
docker-compose exec postgres pg_isready

# Redis
docker-compose exec redis redis-cli ping
```

## 🚨 Troubleshooting

### Port Already in Use

```bash
# Check what's using the port
lsof -i :8000

# Change port in .env
APP_PORT=8080
```

### Permission Issues

```bash
# Fix storage permissions
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R laravel:laravel storage bootstrap/cache
```

### Database Connection Failed

```bash
# Check if PostgreSQL is ready
docker-compose exec postgres pg_isready

# Check .env config
DB_HOST=postgres  # Must be service name, not localhost
DB_PORT=5432
```

### Clear Everything and Start Fresh

```bash
# Stop and remove everything
docker-compose down -v

# Remove images
docker-compose down --rmi all -v

# Start fresh
./docker.sh setup
```

### Node Module Issues

```bash
# Remove node_modules
rm -rf node_modules

# Reinstall
docker-compose exec node npm install
```

## 🔒 Production Considerations

### Security

1. **Change default passwords** trong `.env`:

   ```env
   DB_PASSWORD=strong_password_here
   PGADMIN_PASSWORD=strong_password_here
   ```

2. **Remove debug tools:**
   - Comment out pgAdmin service
   - Comment out Mailhog service

3. **Use secrets management:**
   - Docker secrets
   - AWS Secrets Manager
   - HashiCorp Vault

### Performance

1. **Enable OPcache** (đã có trong php.ini)

2. **Optimize Laravel:**

   ```bash
   docker-compose exec app php artisan config:cache
   docker-compose exec app php artisan route:cache
   docker-compose exec app php artisan view:cache
   ```

3. **Use Redis for cache/sessions** (đã config sẵn)

4. **CDN for assets**

### SSL/HTTPS

Thêm nginx SSL config hoặc dùng reverse proxy như:

- Traefik
- Nginx Proxy Manager
- Cloudflare

## 📊 Monitoring

### Container Stats

```bash
docker stats
```

### Database Size

```bash
docker-compose exec postgres psql -U laravel_lms -d laravel_lms_db -c \
  "SELECT pg_size_pretty(pg_database_size('laravel_lms_db'));"
```

### Active Connections

```bash
docker-compose exec postgres psql -U laravel_lms -d laravel_lms_db -c \
  "SELECT * FROM pg_stat_activity WHERE datname = 'laravel_lms_db';"
```

## 📚 Additional Resources

- [Docker Documentation](https://docs.docker.com/)
- [Docker Compose Documentation](https://docs.docker.com/compose/)
- [Laravel Sail](https://laravel.com/docs/sail) (official Laravel Docker)
- [PostgreSQL Docker Hub](https://hub.docker.com/_/postgres)

## 💡 Tips

1. **Use docker-compose exec instead of docker exec** - tự động tìm container
2. **Add aliases** vào `.bashrc` or `.zshrc`:

   ```bash
   alias dcu='docker-compose up -d'
   alias dcd='docker-compose down'
   alias dcr='docker-compose restart'
   alias dcl='docker-compose logs -f'
   alias dce='docker-compose exec'
   alias art='docker-compose exec app php artisan'
   ```

3. **VS Code Remote Containers extension** - để code trực tiếp trong container

4. **Keep volumes for data persistence** - PostgreSQL data sẽ được lưu trong Docker volume

## 🤝 Contributing

Khi thêm service mới:

1. Update `docker-compose.yml`
2. Add config files vào `docker/` folder
3. Update documentation này
4. Test full setup với `./docker.sh setup`

---

**Happy Dockerizing! 🐳**
