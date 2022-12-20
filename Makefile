#!/bin/bash

OS = $(shell uname)
UID = $(shell id -u)
DOCKER_BE = admicon-clean-arch-be

help: ## Show this help message
	@echo 'usage: make [target]'
	@echo
	@echo 'targets:'
	@egrep '^(.+)\:\ ##\ (.+)' ${MAKEFILE_LIST} | column -t -c 2 -s ':#'

start: ## Start the containers
	docker network create admicon-clean-arch-network || true
	cp -n docker-compose.yml.dist docker-compose.yml || true
	cp -n .env.dist .env || true
	U_ID=${UID} docker-compose up -d

stop: ## Stop the containers
	U_ID=${UID} docker-compose stop

restart: ## Restart the containers
	$(MAKE) stop && $(MAKE) start

build: ## Rebuilds all the containers
	docker network create admicon-clean-arch-network || true
	cp -n docker-compose.yml.dist docker-compose.yml || true
	cp -n .env.dist .env || true
	U_ID=${UID} docker-compose build

prepare: ## Runs backend commands
	$(MAKE) composer-install
	$(MAKE) migrations
	$(MAKE) db-test-creation
	$(MAKE) migrations-test

run: ## starts the Symfony development server
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} symfony serve -d

logs: ## Show Symfony logs in real time
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} symfony server:log


# Backend commands
composer-install: ## Installs composer dependencies
	U_ID=${UID} docker exec --user ${UID} ${DOCKER_BE} composer install --no-interaction

#migrations: ## Installs composer dependencies
#	U_ID=${UID} docker exec --user ${UID} ${DOCKER_BE} bin/console doctrine:migration:migrate -n --allow-no-migration
.PHONY: migrations migrations-test
migrations: ## Run migrations for dev/prod environments
	U_ID=${UID} docker exec --user ${UID} ${DOCKER_BE} bin/console doctrine:migration:migrate -n

migrations-test: ## Run migrations for test environments
	U_ID=${UID} docker exec --user ${UID} ${DOCKER_BE} bin/console doctrine:migration:migrate -n --env=test

db-test-creation: ## Create the project databases for test environment
	U_ID=${UID} docker exec --user ${UID} ${DOCKER_BE} bin/console doctrine:database:create -n --env=test

code-style:
	U_ID=${UID} docker exec --user ${UID} ${DOCKER_BE} vendor/bin/php-cs-fixer fix src --rules=@Symfony

code-style-check:
	U_ID=${UID} docker exec --user ${UID} ${DOCKER_BE} vendor/bin/php-cs-fixer fix src --rules=@Symfony --dry-run

generate-ssh-keys: ## Generates SSH keys for JWT authentication
	U_ID=${UID} docker exec --user ${UID} ${DOCKER_BE} bin/console lexik:jwt:generate-keypair
# End backend commands

ssh-be: ## bash into the be container
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} bash

#code-style: ## Runs php-cs to fix code styling following Symfony rules
#	U_ID=${UID} docker exec --user ${UID} ${DOCKER_BE} php-cs-fixer fix src --rules=@Symfony

.PHONY: tests
tests:
	U_ID=${UID} docker exec --user ${UID} ${DOCKER_BE} vendor/bin/simple-phpunit -c phpunit.xml.dist

