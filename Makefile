OPTS := ""

all: test test-md test-no-links test-only-dev test-only-prod test-json

test:
	php ./composer-lock-diff --from test-data/composer.lock.from --to test-data/composer.lock.to $(OPTS)

test-md:
	$(MAKE) test OPTS=--md

test-no-links:
	$(MAKE) test OPTS=--no-links

test-only-dev:
	$(MAKE) test OPTS=--only-dev

test-only-prod:
	$(MAKE) test OPTS=--only-prod

test-json:
	$(MAKE) test OPTS=--json
