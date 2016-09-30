composer-lock-diff
==================

See what packages have changed after you run `composer update` by comparing composer.lock to the the git HEAD.

Requires:
- perl >= 5.14 (probably 5.6 but only tested with 5.14)
- jq   >= 1.2 (maybe earlier, tested with 1.2 from 2013)

Usage
=====

```bash
composer update
# don't commit yet!
composer-lock-diff
```

Example Output
==============

```
production packages
===================
predis/predis            dev-master => v1.1.1
nikic/php-parser         v2.0.0     => v2.1.1
paragonie/random_compat  1.1.4      => v1.4.1
psr/log                  1.0.0      => 1.0.1
sentry/sentry            0.22.0     => 1.4.1

dev packages
============
behat/mink-selenium2-driver  v1.3.0    => v1.3.1
symfony/yaml                 v2.8.1    => v3.1.4
doctrine/dbal                v2.5.3    => v2.5.5
behat/gherkin                v4.4.1    => v4.4.4
phpspec/phpspec              2.4.0     => 2.5.3
henrikbjorn/lurker           1.0.0     => dev-master
mockery/mockery              0.9.4     => 0.9.5
```
