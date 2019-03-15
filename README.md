composer-lock-diff
==================

See what packages have changed after you run `composer update` by comparing composer.lock to the the git HEAD.

Requires:
- php >= 5.3

There are no other dependencies.

Install
-------

```bash
composer global require davidrjonas/composer-lock-diff:^1.0@dev
```

Or just copy the 'composer-lock-diff' to `/usr/local/bin`.

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

^ File includes anything available as a [protocol stream wrapper](http://php.net/manual/en/wrappers.php) such as URLs.

Example Plain Text Table Output
===============================

```
+--------------------+-------+--------+------------------------------------------------------------------+-------------------------------------------+
| Production Changes | From  | To     | Compare                                                          | Source                                    |
+--------------------+-------+--------+------------------------------------------------------------------+-------------------------------------------+
| guzzlehttp/guzzle  | 6.2.0 | 6.3.0  | https://github.com/guzzle/guzzle/compare/6.2.0...6.3.0           | https://github.com/guzzle/guzzle          |
| hashids/hashids    | 2.0.0 | 2.0.4  | https://github.com/ivanakimov/hashids.php/compare/2.0.0...2.0.4  | https://github.com/ivanakimov/hashids.php |
| league/flysystem   | 1.0.0 | 1.0.42 | https://github.com/thephpleague/flysystem/compare/1.0.0...1.0.42 | https://github.com/thephpleague/flysystem |
| monolog/monolog    | NEW   | 1.21.0 |                                                                  | https://github.com/Seldaek/monolog        |
+--------------------+-------+--------+------------------------------------------------------------------+-------------------------------------------+

+------------------+--------+---------+---------+-------------------------------------+
| Dev Changes      | From   | To      | Compare | Source                              |
+------------------+--------+---------+---------+-------------------------------------+
| phpspec/php-diff | v1.0.2 | REMOVED |         | https://github.com/phpspec/php-diff |
+------------------+--------+---------+---------+-------------------------------------+
```

Markdown Table
==============

### Raw

```
| Production Changes                                            | From  | To     | Compare                                                                 |
|---------------------------------------------------------------|-------|--------|-------------------------------------------------------------------------|
| [guzzlehttp/guzzle](https://github.com/guzzle/guzzle)         | 6.2.0 | 6.3.0  | [...](https://github.com/guzzle/guzzle/compare/6.2.0...6.3.0)           |
| [hashids/hashids](https://github.com/ivanakimov/hashids.php)  | 2.0.0 | 2.0.4  | [...](https://github.com/ivanakimov/hashids.php/compare/2.0.0...2.0.4)  |
| [league/flysystem](https://github.com/thephpleague/flysystem) | 1.0.0 | 1.0.42 | [...](https://github.com/thephpleague/flysystem/compare/1.0.0...1.0.42) |
| [monolog/monolog](https://github.com/Seldaek/monolog)         | NEW   | 1.21.0 |                                                                         |

| Dev Changes                                             | From   | To      | Compare |
|---------------------------------------------------------|--------|---------|---------|
| [phpspec/php-diff](https://github.com/phpspec/php-diff) | v1.0.2 | REMOVED |         |
```

### Rendered

| Production Changes                                            | From  | To     | Compare                                                                 |
|---------------------------------------------------------------|-------|--------|-------------------------------------------------------------------------|
| [guzzlehttp/guzzle](https://github.com/guzzle/guzzle)         | 6.2.0 | 6.3.0  | [...](https://github.com/guzzle/guzzle/compare/6.2.0...6.3.0)           |
| [hashids/hashids](https://github.com/ivanakimov/hashids.php)  | 2.0.0 | 2.0.4  | [...](https://github.com/ivanakimov/hashids.php/compare/2.0.0...2.0.4)  |
| [league/flysystem](https://github.com/thephpleague/flysystem) | 1.0.0 | 1.0.42 | [...](https://github.com/thephpleague/flysystem/compare/1.0.0...1.0.42) |
| [monolog/monolog](https://github.com/Seldaek/monolog)         | NEW   | 1.21.0 |                                                                         |

| Dev Changes                                             | From   | To      | Compare |
|---------------------------------------------------------|--------|---------|---------|
| [phpspec/php-diff](https://github.com/phpspec/php-diff) | v1.0.2 | REMOVED |         |

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
- https://github.com/enomotodev

