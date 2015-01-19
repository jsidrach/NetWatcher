#!/bin/bash
# Upgrades the libraries
# Delete Libraries
rm -rf lib/vendor
rm -f composer.lock
mkdir lib/vendor
# Get Composer
curl -sS https://getcomposer.org/installer | php -- --install-dir=lib/vendor
# Get Php Documentor
curl -o lib/vendor/phpDocumentor.phar http://www.phpdoc.org/phpDocumentor.phar
# Get Dependencies
./lib/vendor/composer.phar install
# JQuery
cp lib/vendor/components/jquery/jquery.js public/js/
# Growl notifications
cp -rf lib/vendor/ifightcrime/bootstrap-growl/jquery.bootstrap-growl.js public/js/
# Copy Bootstrap to public dir
cp -rf lib/vendor/twbs/bootstrap/dist/* public/
rm -rf public/js/bootstrap.min.js
rm -rf public/css/bootstrap-theme.css public/css/bootstrap-theme.css.map public/css/bootstrap.css public/css/bootstrap.css.map
rm -rf components/
mv public/css/bootstrap-theme.min.css public/themes/
# Bootstrap Themes
curl -o public/themes/bootstrap.celurean.min.css http://bootswatch.com/cerulean/bootstrap.min.css
curl -o public/themes/bootstrap.cosmo.min.css http://bootswatch.com/cosmo/bootstrap.min.css
curl -o public/themes/bootstrap.cyborg.min.css http://bootswatch.com/cyborg/bootstrap.min.css
curl -o public/themes/bootstrap.darkly.min.css http://bootswatch.com/darkly/bootstrap.min.css
curl -o public/themes/bootstrap.slate.min.css http://bootswatch.com/slate/bootstrap.min.css
curl -o public/themes/bootstrap.yeti.min.css http://bootswatch.com/yeti/bootstrap.min.css
