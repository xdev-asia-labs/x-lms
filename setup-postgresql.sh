#!/bin/bash

# Laravel LMS - PostgreSQL Setup Script
# Chạy script này để setup database PostgreSQL

set -e

echo "🚀 Laravel LMS - PostgreSQL Setup"
echo "=================================="
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Database configuration
DB_NAME="laravel_lms_db"
DB_USER="laravel_lms"
DB_PASSWORD="laravel_lms_password"

echo -e "${YELLOW}Nhập thông tin database (nhấn Enter để dùng mặc định):${NC}"
echo ""

read -p "Database name [$DB_NAME]: " input_db_name
DB_NAME="${input_db_name:-$DB_NAME}"

read -p "Database user [$DB_USER]: " input_db_user
DB_USER="${input_db_user:-$DB_USER}"

read -sp "Database password [$DB_PASSWORD]: " input_db_password
echo ""
DB_PASSWORD="${input_db_password:-$DB_PASSWORD}"

echo ""
echo -e "${GREEN}Cấu hình:${NC}"
echo "Database: $DB_NAME"
echo "User: $DB_USER"
echo "Password: ****"
echo ""

read -p "Tiếp tục? (y/n) " -n 1 -r
echo ""
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "Đã hủy."
    exit 1
fi

# Check if PostgreSQL is installed
if ! command -v psql &> /dev/null; then
    echo -e "${RED}❌ PostgreSQL chưa được cài đặt!${NC}"
    echo ""
    echo "Cài đặt PostgreSQL:"
    echo "  macOS:   brew install postgresql@16"
    echo "  Ubuntu:  sudo apt install postgresql postgresql-contrib"
    exit 1
fi

echo -e "${GREEN}✓ PostgreSQL đã được cài đặt${NC}"

# Check if PostgreSQL is running
if ! pg_isready &> /dev/null; then
    echo -e "${YELLOW}⚠ PostgreSQL chưa chạy. Đang khởi động...${NC}"
    
    # Try to start PostgreSQL (macOS)
    if command -v brew &> /dev/null; then
        brew services start postgresql@16 || brew services start postgresql
    fi
    
    # Wait for PostgreSQL to start
    sleep 2
    
    if ! pg_isready &> /dev/null; then
        echo -e "${RED}❌ Không thể khởi động PostgreSQL${NC}"
        echo "Hãy khởi động thủ công:"
        echo "  macOS:   brew services start postgresql@16"
        echo "  Ubuntu:  sudo systemctl start postgresql"
        exit 1
    fi
fi

echo -e "${GREEN}✓ PostgreSQL đang chạy${NC}"

# Create database and user
echo ""
echo "📦 Tạo database và user..."

# Check if running as postgres user or need sudo
PSQL_CMD="psql postgres"
if [ "$(whoami)" != "postgres" ] && [ -f /etc/debian_version ]; then
    PSQL_CMD="sudo -u postgres psql"
fi

# Create user and database
$PSQL_CMD <<EOF 2>/dev/null || echo "Note: User hoặc database có thể đã tồn tại"
-- Create user if not exists
DO \$\$
BEGIN
    IF NOT EXISTS (SELECT FROM pg_user WHERE usename = '$DB_USER') THEN
        CREATE USER $DB_USER WITH PASSWORD '$DB_PASSWORD';
    END IF;
END \$\$;

-- Create database if not exists
SELECT 'CREATE DATABASE $DB_NAME' WHERE NOT EXISTS (SELECT FROM pg_database WHERE datname = '$DB_NAME')\gexec

-- Grant privileges
GRANT ALL PRIVILEGES ON DATABASE $DB_NAME TO $DB_USER;

\c $DB_NAME

-- PostgreSQL 15+ requires additional grants
GRANT ALL ON SCHEMA public TO $DB_USER;
GRANT CREATE ON SCHEMA public TO $DB_USER;
EOF

echo -e "${GREEN}✓ Database và user đã được tạo${NC}"

# Update .env file
echo ""
echo "📝 Cập nhật file .env..."

if [ -f .env ]; then
    # Backup .env
    cp .env .env.backup
    echo -e "${GREEN}✓ Đã backup .env thành .env.backup${NC}"
    
    # Update database configuration
    sed -i.tmp "s/^DB_CONNECTION=.*/DB_CONNECTION=pgsql/" .env
    sed -i.tmp "s/^DB_HOST=.*/DB_HOST=127.0.0.1/" .env
    sed -i.tmp "s/^DB_PORT=.*/DB_PORT=5432/" .env
    sed -i.tmp "s/^DB_DATABASE=.*/DB_DATABASE=$DB_NAME/" .env
    sed -i.tmp "s/^DB_USERNAME=.*/DB_USERNAME=$DB_USER/" .env
    sed -i.tmp "s/^DB_PASSWORD=.*/DB_PASSWORD=$DB_PASSWORD/" .env
    
    # Clean up temp files
    rm -f .env.tmp
    
    echo -e "${GREEN}✓ File .env đã được cập nhật${NC}"
else
    echo -e "${RED}❌ File .env không tồn tại${NC}"
    echo "Hãy tạo từ .env.example:"
    echo "  cp .env.example .env"
    exit 1
fi

# Clear cache
echo ""
echo "🧹 Clear cache..."
php artisan config:clear
php artisan cache:clear
echo -e "${GREEN}✓ Cache đã được xóa${NC}"

# Ask to run migrations
echo ""
read -p "Chạy migrations? (y/n) " -n 1 -r
echo ""
if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo "🔄 Đang chạy migrations..."
    php artisan migrate
    echo -e "${GREEN}✓ Migrations đã hoàn thành${NC}"
fi

# Success message
echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}✅ Setup PostgreSQL hoàn tất!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo "Thông tin kết nối:"
echo "  Host:     127.0.0.1"
echo "  Port:     5432"
echo "  Database: $DB_NAME"
echo "  User:     $DB_USER"
echo ""
echo "Test kết nối:"
echo "  psql -U $DB_USER -d $DB_NAME"
echo ""
echo "Để xem tables:"
echo "  php artisan db:show"
echo "  php artisan db:table <table_name>"
echo ""

# Test connection
echo "🔍 Test kết nối database..."
if php artisan db:show &> /dev/null; then
    echo -e "${GREEN}✓ Kết nối database thành công!${NC}"
else
    echo -e "${YELLOW}⚠ Không thể test kết nối. Hãy kiểm tra lại cấu hình.${NC}"
fi

echo ""
echo "Happy coding! 🎉"
