#!/bin/bash
BUILD="$PWD"
VERSION="$(git rev-parse --short HEAD)"

echo "Started building at $(date) - $(whoami)"

mkdir -p /tests/www

cp -r ./* /tests/www
cp -r ./.* /tests/www

cd /tests/www

# Update composer
composer self-update

# Install dependencies
composer install --no-interaction --no-progress

# First we need to build the library
cd vendor/compojoom/lib_compojoom

# Library dependencies
composer install --no-interaction --no-progress

# Build library
cp jbuild.dist.ini jbuild.ini

vendor/bin/robo build

# Copy library
cp -r dist/current/libraries "/tests/www/source/libraries"

# Move back to the top
cd /tests/www

cp jbuild.dist.ini jbuild.ini

# Build package
vendor/bin/robo build --dev

# Copy acceptance yml
cp tests/acceptance.suite.dist.yml tests/acceptance.suite.yml

mkdir /tests/cmc
cp -r dist/current/* /tests/cmc

# It should be already running, but sometimes the phusion script init is not executed
apache2ctl restart
mysqld &

chown -R www-data .
chown -R www-data /tests