#!/usr/bin/env bash
cd ..
rm -rf dist
cp docker/settings.php src/settings.php
rm -rf vendor
composer install --no-dev --optimize-autoloader
box build
mkdir dist
mv sufel.phar sufel/.phar
cp public/.htaccess dist/.htaccess
cp bin/index.php dist/index.php
git checkout .