composer-lock-diff
==================

See what packages have changed after you run `composer update` by comparing composer.lock to the the git HEAD.

Requires:
- php >= 5.3

There are no other dependencies.

Install
-------

```bash
composer global require davidrjonas/composer-lock-diff:^1.0

# With zsh, run `rehash` to make it known to the shell.

# try it
composer-lock-diff --help
```

If `composer-lock-diff` is not found, make sure `~/.composer/vendor/bin` is in
your `$PATH` env variable. For more information on how to do that see [this question on Stack Overflow][SO].

[SO]: https://stackoverflow.com/questions/25373188/laravel-installation-how-to-place-the-composer-vendor-bin-directory-in-your

Usage
=====

```bash
composer update
# don't commit yet!
composer-lock-diff
```

Or from vim, to insert the output into the commit message, type `:r!composer-lock-diff`.

### Options

- `--path, -p`: Base to with which to prefix paths. Default "./"
- `--from`: The file^, git ref, or git ref with filename to compare from (HEAD:composer.lock)
- `--to`: The file^, git ref, or git ref with filename to compare to (composer.lock)
- `--md`: Markdown table output
- `--json`: json output
- `--pretty`: pretty output when combined with `--json` (>=5.4 only)
- `--no-links`: Don't include Compare links in plain text or any links in markdown
- `--only-prod`: Only include changes from `packages`
- `--only-dev`: Only include changes from `packages-dev`
- `--vcs`: Force vcs (git, svn, ...). Default auto-detect from path

^ File includes anything available as a [protocol stream wrapper](http://php.net/manual/en/wrappers.php) such as URLs.

Example Plain Text Table Output
===============================

```
+--------------------+-------+--------+------------------------------------------------------------------+
| Production Changes | From  | To     | Compare                                                          |
+--------------------+-------+--------+------------------------------------------------------------------+
| guzzlehttp/guzzle  | 6.2.0 | 6.3.0  | https://github.com/guzzle/guzzle/compare/6.2.0...6.3.0           |
| hashids/hashids    | 2.0.0 | 2.0.4  | https://github.com/ivanakimov/hashids.php/compare/2.0.0...2.0.4  |
| league/flysystem   | 1.0.0 | 1.0.42 | https://github.com/thephpleague/flysystem/compare/1.0.0...1.0.42 |
| monolog/monolog    | NEW   | 1.21.0 |                                                                  |
+--------------------+-------+--------+------------------------------------------------------------------+

+------------------+--------+---------+---------+
| Dev Changes      | From   | To      | Compare |
+------------------+--------+---------+---------+
| phpspec/php-diff | v1.0.2 | REMOVED |         |
+------------------+--------+---------+---------+
```

Markdown Table
==============

### Raw

```
| Production Changes | From  | To     | Compare                                                                 |
|--------------------|-------|--------|-------------------------------------------------------------------------|
| guzzlehttp/guzzle  | 6.2.0 | 6.3.0  | [...](https://github.com/guzzle/guzzle/compare/6.2.0...6.3.0)           |
| hashids/hashids    | 2.0.0 | 2.0.4  | [...](https://github.com/ivanakimov/hashids.php/compare/2.0.0...2.0.4)  |
| league/flysystem   | 1.0.0 | 1.0.42 | [...](https://github.com/thephpleague/flysystem/compare/1.0.0...1.0.42) |
| monolog/monolog    | NEW   | 1.21.0 |                                                                         |

| Dev Changes      | From   | To      | Compare |
|------------------|--------|---------|---------|
| phpspec/php-diff | v1.0.2 | REMOVED |         |
```

### Rendered

| Production Changes | From  | To     | Compare                                                                 |
|--------------------|-------|--------|-------------------------------------------------------------------------|
| guzzlehttp/guzzle  | 6.2.0 | 6.3.0  | [...](https://github.com/guzzle/guzzle/compare/6.2.0...6.3.0)           |
| hashids/hashids    | 2.0.0 | 2.0.4  | [...](https://github.com/ivanakimov/hashids.php/compare/2.0.0...2.0.4)  |
| league/flysystem   | 1.0.0 | 1.0.42 | [...](https://github.com/thephpleague/flysystem/compare/1.0.0...1.0.42) |
| monolog/monolog    | NEW   | 1.21.0 |                                                                         |

| Dev Changes      | From   | To      | Compare |
|------------------|--------|---------|---------|
| phpspec/php-diff | v1.0.2 | REMOVED |         |

Development
===========

New features are always welcome! Follow these guidelines

- Try to match the style of the code that already exists, even if it isn't _your_ style (sorry!).
- Make sure there is a way to test the feature.
- Test with PHP 5.3 (I'm serious!), >=5.4<7, 7.current. Docker is helpful, particularly for the older versions. Just run the ubuntu:12.04 image and install php for 5.3 and 14.04 for 5.6. I can help if you're having trouble.

The [Makefile](Makefile) has some test cases. Run `make | less` and inspect the output. If you need specific versions or more information, continue reading.

To run using the test data manually simply point the `--to` and `--from` args at the lock files,

```php
php ./composer-lock-diff --from ./test-data/composer.from.lock --to ./test-data/composer.to.lock
```

Docker is very helpful for targeting a specific version of php and/or composer,

```php
docker run --rm -it -v "$PWD":/src -w /src php:7.4.2 \
  php ./composer-lock-diff --from ./test-data/composer.from.lock --to ./test-data/composer.to.lock
```

Sometimes you want to test the git related functions. To do that first I make a temporary repo. Then I copy into it `test-data/composer.from.lock` as `composer.lock` to set the previous state and `test-data/composer.to.json` as `composer.json` for the future state. I commit those then run `composer-lock-diff` with the options I want to test and visually inspect the results.

```bash
mkdir tmp && cd tmp
git init
cp ../test-data/composer.to.json composer.json
cp ../test-data/composer.from.lock composer.lock
git add .
git commit -m "initial"

composer update
# or
docker run --rm -it -v "$PWD":/src -w /src composer:latest php composer update

php ../composer-lock-diff

# or, if you want to use docker, you'll need git
cd ..
docker run --rm -it -v "$PWD":/src -w /src php:7.4.2 bash
apt-get update && apt-get install -y git
# You may want composer as well,
curl -OL https://getcomposer.org/download/1.9.3/composer.phar
cd tmp
php ../composer.phar update
php ../composer-lock-diff
```

Add a test case to test-data/
-----------------------------

- Make a new, temporary git repo in `./tmp`
- Copy `../test-data/composer.from.json` as `composer.json` and `../test-data/composer.from.lock` as `composer.lock`.
- Commit them.
- Run `composer install`
- Add your _pre_ case to `composer.json`. Use an exact version.
- Run `composer update`.
- The generated `composer.lock` should look similar to `../test-data/composer.from.lock` but there will be differences due to transient dependencies. No real way around that. Use `composer-lock-diff` to make sure none of the named packages change versions and your new case is there.
- Copy `composer.json` to `../test-data/composer.from.json` and `composer.lock` to `composer.from.lock`.
- Copy `../test-data/composer.to.json` as `composer.json`.
- Add your _post_ case to `composer.json`. Again, exact versions are best.
- Generate a new lock file.
- Use composer-lock-diff to test your feature.
- When you're happy with it, copy `composer.json` to `../test-data/composer.to.json` and `composer.lock` to `../test-data/composer.to.lock`.

Test Cases
----------

See [Makefile](Makefile).

- `comopser-lock-diff` # no args
- `composer-lock-diff --from ./test-data/composer.from.lock --to ./test-data/composer.to.lock`
- `composer-lock-diff --path ./test-data/`
- `composer-lock-diff --from <git ref>` # this gets tested with 'no args'
- `composer-lock-diff --from <git ref with filename>`
- `composer-lock-diff --to <git ref>`
- `composer-lock-diff --to <git ref with filename>`
- `composer-lock-diff --only-dev`
- `composer-lock-diff --only-prod`
- `composer-lock-diff --no-links`
- `composer-lock-diff --json`
- `composer-lock-diff --json --pretty`
- `composer-lock-diff --md`
- `composer-lock-diff --md --no-links`

If anyone can help test with Windows that would be very much appreciated!

Contributors
============

Thanks to everyone who has shared ideas and code! In particular,

- https://github.com/delamart
- https://github.com/prometheas
- https://github.com/paxal
- https://github.com/nclavaud
- https://github.com/cafferata
- https://github.com/ihor-sviziev
- https://github.com/wiese
- https://github.com/jibran
- https://github.com/soleuu

