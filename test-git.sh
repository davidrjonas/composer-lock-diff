#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )"

git --help > /dev/null || { echo "Fail: could not find 'git' executable"; exit 1; }

trap cleanup INT ERR

function cleanup() {
  cd "$DIR/test-data"
  rm -rf proj proj-working gitrepo
}

set -eEx

cd test-data

mkdir -p proj/trunk

cp composer.from.json proj/trunk/composer.json
cp composer.from.lock proj/trunk/composer.lock

cp -r proj gitrepo
cd gitrepo && \
  git init && \
  git add . && \
  git commit -m "initial commit" && \
  cd ..

git clone file://$PWD/gitrepo proj-working
cp composer.to.json proj-working/trunk/composer.json
cp composer.to.lock proj-working/trunk/composer.lock

cd proj-working/trunk
../../../composer-lock-diff

cd ..
../../composer-lock-diff -p trunk

cd ..
rm -rf proj proj-working gitrepo

