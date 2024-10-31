include .env

PRIVATE ?= private
REVEAL_DEPS := $(shell find ./ -name '.env.*')
DOCKER_COMPOSE_PROJECT ?= docker compose -p ${PROJECT_NAME}

# Colors
COLOR_RESET   = \033[0m
COLOR_ERROR   = \033[31m
COLOR_INFO    = \033[32m
COLOR_COMMENT = \033[33m

## Help - display content of "make" command
.PHONY: help
help:
	@printf "\n${COLOR_COMMENT}Usage:${COLOR_RESET}\n"
	@printf "make [target]\n"
	@printf "${COLOR_COMMENT}Available targets:${COLOR_RESET}\n"
	@awk '/^[^.#][a-zA-Z0-9_\/\-.@]*:/ { \
		if (substr(lastLine, 1, 1) == ".") { \
			lastLine = prevToLastLine; \
		} \
		helpMessage = match(lastLine, /^## (.*)/); \
		if (helpMessage) { \
			helpCommand = substr($$1, 1, index($$1, ":") - 1); \
			helpMessage = substr(lastLine, RSTART + 3, RLENGTH); \
			printf "  ${COLOR_INFO}%-20s${COLOR_RESET} %s\n", helpCommand, helpMessage; \
		} \
	} \
	{ prevToLastLine = lastLine } \
	{ lastLine = $$0 }' $(MAKEFILE_LIST)

## Encrypt PRIVATE folder into private.zip
.PHONY: hide
hide: check-env
	@if [ -f private/.key ]; then \
	    if [ -f private.zip ]; then \
		    rm private.zip; \
        fi; \
		zip -r private.zip $(PRIVATE) .env* --password "$$(cat private/.key)" -x 'private/.key' -x '*.local'; \
	else \
		echo 'Missing private/.key'; \
	fi;

## Decrypt private.zip into private
.PHONY: reveal
reveal: check-env $(PRIVATE) $(REVEAL_DEPS)
	@make -s .env
	@touch $?

private.zip:
	zip -r private.zip $(PRIVATE) .env* credentials.json --password "$$(cat private/.key)" -x 'private/.key' -x '*.local'; \

.env: private.zip
	@if [ -f private/.key ]; then \
        unzip -o -P"$$(cat private/.key)" private.zip; \
	else \
		echo 'Missing private/.key'; \
	fi;
	@touch .env


.PHONY: build
build:
	docker compose build

.PHONY: down
down:
	docker compose down -v

.PHONY: run
run:
	docker compose up -d

.PHONY: console
console:
	docker exec -it ${COMPOSE_PROJECT_NAME}_php-fpm bash

## Doctrine migration to the latest version
.PHONY: migrate
migrate:
	docker exec -i ${COMPOSE_PROJECT_NAME}_php-fpm bin/console doctrine:migrations:migrate --no-interaction


## Initialize project to run on localhost, specified port in .env
.PHONY: initialization
initialization: docker
	@if [ ! -d vendor ]; then \
        docker compose run --rm php composer install; \
    fi
	@if [ ! -d public/build ]; then \
        docker compose run --rm node sh -c "yarn install"; \
        docker compose run --rm node sh -c "yarn build"; \
	fi
	docker compose run --rm php php bin/console --no-interaction doctrine:migrations:migrate
	echo "Project is prepared unsecured on http://localhost:8180/ or secured on https://localhost:8660/"

## Check if project docker images exist
.PHONY: docker
docker: check-env
	@if [ -z "$$($(DOCKER_COMPOSE_PROJECT) ps -q)" ]; then \
		check=0; \
	else \
		check=1; \
	fi; \
	for CONTAINER_ID in $$($(DOCKER_COMPOSE_PROJECT) ps -q); do \
    	if [ -z "$$(docker ps -q --no-trunc | grep "$${CONTAINER_ID}")" ]; then \
    		check=0; \
    	fi; \
    done; \
    if [ "$${check}" -eq 0 ]; then \
    	echo "Docker images not found, building them, starting them afterwards"; \
    	make build; \
    	echo "Starting built docker images"; \
    	make run; \
    else \
    	echo "Docker images are prepared"; \
    fi

## Check existence of .env file
.PHONY: check-env
check-env:
	@if [ ! -f .env ]; then \
	    make reveal; \
	fi;
