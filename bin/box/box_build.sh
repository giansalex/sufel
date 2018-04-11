#!/usr/bin/env bash
cd ../..
rm -rf dist
cp box2/settings.php src/settings.php
rm -rf vendor
composer install --no-dev --optimize-autoloader
pathBox=$(which box)
if [ -x "$pathBox" ] ; then
    box build
else
    boxFile="box.phar"
    php ./bin/box.php $boxFile
    php -d phar.readonly=0 $boxFile build
fi
mkdir dist
mkdir dist/upload
mv sufel.phar dist/sufel.phar
cp public/.htaccess dist/.htaccess
cp box2/index.php dist/index.php
cp box2/.htaccess dist/upload/.htaccess