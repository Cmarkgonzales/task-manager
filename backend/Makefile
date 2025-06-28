# Variables
ARTISAN  = docker compose exec app php artisan
COMPOSER = docker compose exec app composer
PHP      = docker compose exec app php

.PHONY: help up down build migrate fresh seed tinker artisan composer test

# Show help menu
help:
	@echo "Available commands:"
	@echo "  make up                  -- Start containers"
	@echo "  make down                -- Stop containers"
	@echo "  make build               -- Build/rebuild containers"
	@echo "  make migrate             -- Run DB migrations"
	@echo "  make fresh               -- Drop all tables & re-run migrations + seeders"
	@echo "  make seed                -- Run database seeders"
	@echo "  make tinker              -- Launch Laravel Tinker"
	@echo "  make artisan ARGS='...'  -- Run any artisan command (ex: make artisan ARGS='route:list')"
	@echo "  make composer ARGS='...' -- Run composer command (ex: make composer ARGS='install')"
	@echo "  make test                -- Run PHPUnit tests"

# Docker Compose
up:
	docker compose up -d

down:
	docker compose down

build:
	docker compose up -d --build

# Laravel Commands
migrate:
	$(ARTISAN) migrate

fresh:
	$(ARTISAN) migrate:fresh --seed

seed:
	$(ARTISAN) db:seed

tinker:
	$(ARTISAN) tinker

artisan:
	$(ARTISAN) $(ARGS)

composer:
	$(COMPOSER) $(ARGS)

test:
	$(ARTISAN) test
