# Static ———————————————————————————————————————————————————————————————————————————————————————————————————————————————
LC_LANG				= it_IT
DEFAULT_GOAL 		:= help
SHELL 				= /bin/bash
PROJECT_NAME        = $(shell basename $(shell pwd) | tr '[:upper:]' '[:lower:]')
# Setup ————————————————————————————————————————————————————————————————————————————————————————————————————————————————
docker := docker-compose
php_container := php
compose	:= $(docker) --file docker-compose.yml --file docker-compose.override.yml -f docker-compose.debug.yml
docker_exec := $(compose) exec
docker_run := $(compose) run --rm
args = $(filter-out $@,$(MAKECMDGOALS))

.PHONY: start stop up down enter erase stan csfix composer coverage test

build: ## Builds the Docker images
	@$(docker) build --pull --no-cache

start: ## Start all project containers
	@$(compose) start

stop: ## Stop the project containers
	@$(compose) stop $(s)

up: ## Spin up project containers
	@$(compose) up -d --remove-orphans

down: ## Erase containers & volumes
	@$(compose) down -v --remove-orphans

enter: ## Enter the PHP container in bash mode
	@$(docker_exec) $(php_container) sh

erase: ## Erase containers with related volumes
	@$(compose) down -v

stan: ## Execute phpstan
	@$(docker_run) $(php_container) vendor/bin/phpstan analyse src/ -c phpstan.neon --level=9 --no-progress -vvv --memory-limit=2048M

csfix: ## Execute php-cs-fixer
	@$(docker_run) $(php_container) vendor/bin/php-cs-fixer fix

composer: ## Execute composer
	@$(docker_run) $(php_container) composer $(call args)

coverage: ## Execute entire tests with coverage metrics
	@$(docker_run) $(php_container) sh -lc "XDEBUG_MODE=coverage php bin/phpunit --coverage-html public/coverage"

test: ## Execute entire tests
	@$(docker_run) $(php_container) sh -lc "php bin/phpunit"

.PHONY: help
help: ## Show this help message
		@cat $(MAKEFILE_LIST) | grep -e "^[a-zA-Z_\-]*: *.*## *" | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'
