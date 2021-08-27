#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )"

svn --help > /dev/null || { echo "Fail: could not find 'svn' executable"; exit 1; }
svnadmin --help > /dev/null || { echo "Fail: could not find 'svnadmin' executable"; exit 1; }

trap cleanup INT ERR

function cleanup() {
  cd "$DIR/test-data"
  rm -rf proj proj-working svnrepo
}

set -eEx

cd test-data

mkdir -p proj/trunk

cp composer.from.json proj/trunk/composer.json
cp composer.from.lock proj/trunk/composer.lock

svnadmin create svnrepo
svn import ./proj file://$PWD/svnrepo -m "Initial commit"

svn checkout file://$PWD/svnrepo proj-working
cp composer.to.json proj-working/trunk/composer.json
cp composer.to.lock proj-working/trunk/composer.lock

cd proj-working/trunk
../../../composer-lock-diff

cd ..
../../composer-lock-diff -p trunk

cd ..
rm -rf proj proj-working svnrepo

