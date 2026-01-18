.PHONY: help setup start stop restart status logs shell psql clean build migrate seed fresh test

# Colors
GREEN  := $(shell tput -Txterm setaf 2)
YELLOW := $(shell tput -Txterm setaf 3)
WHITE  := $(shell tput -Txterm setaf 7)
RESET  := $(shell tput -Txterm sgr0)

help: ## Show this help
	@echo '${GREEN}Laravel LMS - Docker Commands${RESET}'
	@echo ''
	@echo 'Usage:'
	@echo '  ${YELLOW}make${RESET} ${GREEN}<target>${RESET}'
	@echo ''
	@echo 'Targets:'
	@awk 'BEGIN {FS = ":.*?## "} { \
		if (/^[a-zA-Z_-]+:.*?##.*$$/) {printf "  ${YELLOW}%-15s${RESET} %s\n", $$1, $$2} \
	}' $(MAKEFILE_LIST)

setup: ## Full setup (first time)
	@echo "${GREEN}🚀 Running full setup...${RESET}"
	@./docker.sh setup

start: ## Start all containers
	@echo "${GREEN}▶️  Starting containers...${RESET}"
	@docker-compose up -d
	@make status
	@make urls

stop: ## Stop all containers
	@echo "${YELLOW}⏹️  Stopping containers...${RESET}"
	@docker-compose stop

restart: ## Restart all containers
	@echo "${YELLOW}🔄 Restarting containers...${RESET}"
	@docker-compose restart

status: ## Show container status
	@docker-compose ps

logs: ## Show logs (use: make logs SERVICE=app)
	@docker-compose logs -f $(SERVICE)

shell: ## Enter Laravel app shell
	@docker-compose exec app bash

psql: ## Enter PostgreSQL shell
	@docker-compose exec postgres psql -U laravel_lms -d laravel_lms_db

redis: ## Enter Redis CLI
	@docker-compose exec redis redis-cli

clean: ## Remove all containers and volumes
	@echo "${YELLOW}⚠️  This will remove all containers and volumes!${RESET}"
	@read -p "Continue? (y/N): " confirm && [ "$$confirm" = "y" ]
	@docker-compose down -v
	@echo "${GREEN}✓ Cleaned up${RESET}"

build: ## Rebuild Docker images
	@echo "${GREEN}🔨 Building images...${RESET}"
	@docker-compose build

# Laravel Commands
migrate: ## Run database migrations
	@docker-compose exec app php artisan migrate

migrate-fresh: ## Fresh migrations (drops all tables)
	@docker-compose exec app php artisan migrate:fresh

migrate-rollback: ## Rollback last migration
	@docker-compose exec app php artisan migrate:rollback

seed: ## Run database seeders
	@docker-compose exec app php artisan db:seed

fresh: ## Fresh migrations with seeding
	@docker-compose exec app php artisan migrate:fresh --seed

# Artisan Commands
artisan: ## Run artisan command (use: make artisan CMD="make:controller Test")
	@docker-compose exec app php artisan $(CMD)

tinker: ## Laravel Tinker
	@docker-compose exec app php artisan tinker

optimize: ## Optimize Laravel (cache config, routes, views)
	@docker-compose exec app php artisan config:cache
	@docker-compose exec app php artisan route:cache
	@docker-compose exec app php artisan view:cache
	@echo "${GREEN}✓ Application optimized${RESET}"

clear-cache: ## Clear all cache
	@docker-compose exec app php artisan config:clear
	@docker-compose exec app php artisan cache:clear
	@docker-compose exec app php artisan route:clear
	@docker-compose exec app php artisan view:clear
	@docker-compose exec redis redis-cli FLUSHALL
	@echo "${GREEN}✓ All cache cleared${RESET}"

# Composer Commands
composer-install: ## Install composer dependencies
	@docker-compose exec app composer install

composer-update: ## Update composer dependencies
	@docker-compose exec app composer update

composer-dump: ## Dump composer autoload
	@docker-compose exec app composer dump-autoload

# NPM Commands
npm-install: ## Install npm dependencies
	@docker-compose exec node npm install

npm-update: ## Update npm dependencies
	@docker-compose exec node npm update

npm-build: ## Build assets for production
	@docker-compose exec node npm run build

npm-dev: ## Run dev server
	@docker-compose exec node npm run dev

# Testing
test: ## Run PHPUnit tests
	@docker-compose exec app php artisan test

test-coverage: ## Run tests with coverage
	@docker-compose exec app php artisan test --coverage

# Database Operations
db-backup: ## Backup database
	@mkdir -p backups
	@docker-compose exec postgres pg_dump -U laravel_lms laravel_lms_db > backups/backup_$$(date +%Y%m%d_%H%M%S).sql
	@echo "${GREEN}✓ Database backed up to backups/backup_$$(date +%Y%m%d_%H%M%S).sql${RESET}"

db-restore: ## Restore database (use: make db-restore FILE=backup.sql)
	@docker-compose exec -T postgres psql -U laravel_lms laravel_lms_db < $(FILE)
	@echo "${GREEN}✓ Database restored from $(FILE)${RESET}"

# Utility
urls: ## Show application URLs
	@echo ""
	@echo "${GREEN}🌐 Application URLs:${RESET}"
	@echo "  Application:  http://localhost:8000"
	@echo "  pgAdmin:      http://localhost:5050"
	@echo "  Mailhog:      http://localhost:8025"
	@echo "  PostgreSQL:   localhost:5432"
	@echo "  Redis:        localhost:6379"
	@echo ""

stats: ## Show container resource usage
	@docker stats --no-stream

prune: ## Remove unused Docker resources
	@docker system prune -a --volumes
	@echo "${GREEN}✓ Docker resources pruned${RESET}"

# Production
prod-build: ## Build for production
	@docker-compose -f docker-compose.prod.yml build

prod-up: ## Start production containers
	@docker-compose -f docker-compose.prod.yml up -d

prod-down: ## Stop production containers
	@docker-compose -f docker-compose.prod.yml down

# Default target
.DEFAULT_GOAL := help
