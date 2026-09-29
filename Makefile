OPTS ?= ""
PHP = php

all: test

test: vendor
	./vendor/bin/phpunit tests

vendor:
	composer install

# Regenerate the lock files from the json. This is useful when adding new
# entries to test.
locks:
	rm -rf tests/locks/base/composer.from.lock tests/locks/base/vendor
	cd test-data && COMPOSER=composer.from.json composer install -v
	rm -rf tests/locks/base/composer.to.lock tests/locks/base/vendor
	cd test-data && COMPOSER=composer.to.json composer install -v
	rm -rf tests/locks/base/vendor

# Update the expected output files for the unit tests
update-expected:
	./composer-lock-diff --from tests/locks/base/composer.from.lock --to tests/locks/base/composer.to.lock > tests/expected/defaultOutput.txt
	./composer-lock-diff --from tests/locks/base/composer.from.lock --to tests/locks/base/composer.to.lock --json --pretty > tests/expected/jsonPrettyOutput.txt
	./composer-lock-diff --from tests/locks/base/composer.from.lock --to tests/locks/base/composer.to.lock --md > tests/expected/markdownOutput.txt
	./composer-lock-diff --from tests/locks/base/composer.from.lock --to tests/locks/base/composer.to.lock --md --no-links > tests/expected/markdownNoLinksOutput.txt
	./composer-lock-diff --from tests/locks/base/composer.from.lock --to tests/locks/base/composer.to.lock --no-links > tests/expected/noLinksOutput.txt
	./composer-lock-diff --from tests/locks/base/composer.from.lock --to tests/locks/base/composer.to.lock --only-dev > tests/expected/onlyDevOutput.txt
	./composer-lock-diff --from tests/locks/base/composer.from.lock --to tests/locks/base/composer.to.lock --only-prod > tests/expected/onlyProdOutput.txt
	./composer-lock-diff --help > tests/expected/helpOutput.txt
