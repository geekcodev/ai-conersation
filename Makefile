.PHONY: help local-env local-up local-down local-install local-migrate dev-up dev-down dev-migrate dev-cache-warm dev-cache-clear

# Detect if we're in a proper terminal
SHELL := /bin/bash
ifneq (,$(findstring xterm,${TERM}))
	BLUE := \033[0;34m
	GREEN := \033[0;32m
	YELLOW := \033[1;33m
	RED := \033[0;31m
	NC := \033[0m
else
	BLUE :=
	GREEN :=
	YELLOW :=
	RED :=
	NC :=
endif

help: ## Show this help message
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "$(GREEN)%-20s$(NC) %s\n", $$1, $$2}'
	@echo ''

local-env:
	@if [ ! -f .env.example ]; then \
		echo "$(RED)Error: .env.example not found!$(NC)" >&2; \
		exit 1; \
	fi
	@if [ ! -f .env ]; then \
		echo "Copying .env.example to .env..."; \
		cp .env.example .env; \
		echo ".env created from .env.example"; \
	else \
		echo ".env already exists"; \
	fi

local-up: ## Start local environment
	@echo 'Starting containers...'
	@docker compose -f docker-compose.local.yml up -d --build
	@echo '✓ Containers started!'

local-down: ## Stop local environment
	@echo 'Stopping containers...'
	@docker compose -f docker-compose.local.yml down -v
	@echo '✓ Containers stopped!'

local-install: ## Install application in local environment
	@echo 'Installing application...'
	@docker compose -f docker-compose.local.yml exec php composer install --no-interaction
	@docker compose -f docker-compose.local.yml exec php cp .env.example .env
	@docker compose -f docker-compose.local.yml exec php php artisan key:generate
	@echo '✓ Application installed!'

local-migrate: ## Run migrations in local environment
	@echo 'Running migrations...'
	@docker compose -f docker-compose.local.yml exec php php artisan migrate --force
	@echo '✓ Migrations completed!'

dev-up: ## Start development environment
	@echo 'Starting containers...'
	@docker compose -f docker-compose.dev.yml up -d --build
	@echo '✓ Containers started!'

dev-down: ## Stop development environment
	@echo 'Stopping containers...'
	@docker compose -f docker-compose.dev.yml down -v
	@echo '✓ Containers stopped!'

dev-migrate: ## Run migrations in development environment
	@echo 'Running migrations...'
	@docker compose -f docker-compose.dev.yml exec dev-app php artisan migrate --force
	@echo '✓ Migrations completed!'

dev-cache-warm: ## Warm application cache in development environment
	@echo 'Warming application cache...'
	@docker compose -f docker-compose.dev.yml exec dev-app php artisan config:cache
	@docker compose -f docker-compose.dev.yml exec dev-app php artisan route:cache
	@docker compose -f docker-compose.dev.yml exec dev-app php artisan view:cache
	@echo '✓ Application cache warmed!'

dev-cache-clear: ## Clear application cache in development environment
	@echo 'Clearing application cache...'
	@docker compose -f docker-compose.dev.yml exec dev-app php artisan cache:clear
	@echo '✓ Application cache cleared!'
