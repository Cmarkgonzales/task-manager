UID     = $(shell id -u)
GID     = $(shell id -g)
ARTISAN  = docker compose exec -u $(UID):$(GID) app php artisan
COMPOSER = docker compose exec -u $(UID):$(GID) app composer
PHP      = docker compose exec -u $(UID):$(GID) app php
MYSQL    = docker compose exec db mysql

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
	docker compose exec -e HOME=/tmp -u 1000:1000 app php artisan tinker

artisan:
	$(ARTISAN) $(ARGS)

composer:
	$(COMPOSER) $(ARGS)

test:
	$(ARTISAN) test

logs:
	docker compose logs -f --tail=100

fix-perms:
	sudo chown -R $(shell id -u):$(shell id -g) .

dbshell:
	$(MYSQL) -u laravel -p
