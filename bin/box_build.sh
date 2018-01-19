#!/usr/bin/env bash
cd ..
rm -rf dist
cp bin/settings.php src/settings.php
rm -rf vendor
composer install --no-dev --optimize-autoloader
box build
mkdir dist
mkdir dist/upload
mv sufel.phar dist/sufel.phar
cp public/.htaccess dist/.htaccess
cp bin/index.php dist/index.php
cp bin/.htaccess dist/upload/.htaccess
git checkout .