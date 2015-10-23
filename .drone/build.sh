#!/bin/bash
BUILD="$PWD"
VERSION="$(git rev-parse --short HEAD)"

echo "Started building at $(date) - $(whoami)"

mkdir -p /tests/www

# Update composer
composer self-update

# Install dependencies
composer install --no-interaction --no-progress

# First we need to build the library
cd vendor/compojoom/lib_compojoom

# Library dependencies
composer install --no-interaction --no-progress

# Build library
echo "$PWD";

cp jbuild.dist.ini jbuild.ini

vendor/bin/robo build

# Copy library
cp -r dist/current/libraries "$BUILD/source/libraries"

# Move back to the top
cd "$BUILD"

cp jbuild.dist.ini jbuild.ini

# Build package
vendor/bin/robo build --dev

# Copy acceptance yml
cp tests/acceptance.suite.dist.yml tests/acceptance.suite.yml

mkdir /tests/cmc
cp -r dist/current/* /tests/cmc

chown -R joomla:joomla /tests

# It should be already running, but sometimes the phusion script init is not executed
apache2ctl restart
mysqld &

# Start gui
export DISPLAY=:0

Xvfb -screen 0 1024x768x24 -ac +extension GLX +render -noreset > /dev/null 2>&1 &
sleep 4 # give xvfb some time to start

# Fluxbox
fluxbox &

chown -R joomla .

cd /tests/www

chown -R joomla .

cd "$BUILD"

vendor/bin/robo run:tests

echo "All tests finished at $(date)"