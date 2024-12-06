OPTS ?= ""
PHP = php

all: test test-help test-help-vcs-error md no-links only-dev only-prod json json-pretty

test:
	$(PHP) ./composer-lock-diff --from test-data/composer.from.lock --to test-data/composer.to.lock $(OPTS)

test-help:
	$(PHP) ./composer-lock-diff --help

test-help-vcs-error:
	$(PHP) ./composer-lock-diff --vcs UNKNOWN ; test $$? -eq 1

md:
	$(MAKE) test OPTS=--md

no-links:
	$(MAKE) test OPTS=--no-links

only-dev:
	$(MAKE) test OPTS=--only-dev

only-prod:
	$(MAKE) test OPTS=--only-prod

json:
	$(MAKE) test OPTS=--json

json-pretty:
	$(MAKE) test OPTS="--json --pretty"

# Regenerate the lock files from the json. This is useful when adding new
# entries to test.
locks:
	rm -rf test-data/composer.from.lock test-data/vendor
	cd test-data && COMPOSER=composer.from.json composer install -v
	rm -rf test-data/composer.to.lock test-data/vendor
	cd test-data && COMPOSER=composer.to.json composer install -v
	rm -rf test-data/vendor
