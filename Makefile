OPTS := ""

all: test md no-links only-dev only-prod json json-pretty

test:
	php ./composer-lock-diff --from test-data/composer.lock.from --to test-data/composer.lock.to $(OPTS)

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
