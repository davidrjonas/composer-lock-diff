composer-lock-diff
==================

See what packages have changed after you run `composer update` by comparing composer.lock to the the git HEAD.

Requires:
- php >= 5.3

There are no other dependencies. Just copy the file to `/usr/local/bin`.

Usage
=====

```bash
composer update
# don't commit yet!
composer-lock-diff
```

Or from vim, to insert the output into the commit message, type `:r!composer-lock-diff`.

### Options

- `--json`: json output
- `--pretty`: pretty output when combined with `--json` (>=5.4 only)

Example Table Output
====================

```
+---------------------------------------------------------+
| Production Changes               | From       | To      |
+---------------------------------------------------------+
| guzzlehttp/guzzle                | 6.2.0      | 6.2.1   |
| hashids/hashids                  | 1.0.5      | 1.0.6   |
| laravel/framework                | v5.1.27    | v5.1.44 |
| league/flysystem                 | 1.0.16     | 1.0.27  |
| monolog/monolog                  | 1.17.2     | 1.21.0  |
| symfony/polyfill-mbstring        | NEW        | v1.2.0  |
+---------------------------------------------------------+
+------------------------------------------------------------+
| Dev Changes                       | From      | To         |
+------------------------------------------------------------+
| behat/behat                       | v3.0.15   | v3.2.1     |
| behat/gherkin                     | v4.4.1    | v4.4.4     |
| behat/mink                        | v1.7.0    | v1.7.1     |
| behat/mink-browserkit-driver      | v1.3.0    | v1.3.2     |
| behat/mink-extension              | v2.1.0    | v2.2       |
| behat/mink-selenium2-driver       | v1.3.0    | v1.3.1     |
| mockery/mockery                   | 0.9.4     | REMOVED    |
+------------------------------------------------------------+
```
