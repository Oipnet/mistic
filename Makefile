.PHONY: server help install test

ENV ?= dev
COMPOSER_ARGS =
ifeq ($(ENV), prod)
	COMPOSER_ARGS=--prefer-dist --classmap-authoritative --optimize-autoloader --no-dev
endif

help: ## This help
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

server: ## Lance le serveur de dev
	php -S localhost:8080 -t public -d display_errors=1 -d xdebug.remote_enable=1 -d xdebug.remote_autostart=1

install: vendor ## Install application

test: install ## Run unit test
	vendor/bin/phpunit

vendor: composer.lock
	composer install $(COMPOSER_ARGS)

composer.lock: composer.json
	composer update $(COMPOSER_ARGS)