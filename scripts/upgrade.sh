#!/bin/bash
# Upgrades the libraries
# Delete Libraries
rm -rf "lib/vendor"
rm -f "composer.lock"
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
cp -rf "lib/vendor/twbs/bootstrap/dist/"* "public/"
rm -rf "public/js/bootstrap.min.js"
rm -rf "public/css/bootstrap-theme.css" "public/css/bootstrap-theme.css.map" "public/css/bootstrap.css" "public/css/bootstrap.css.map"
rm -rf "components/"
mv public/css/bootstrap-theme.min.css public/themes/default.min.css
# Bootstrap Themes
curl http://api.bootswatch.com/3/ | cut -b2- | tr '"' '\n' | grep .min.css | grep latest | sed 's/^..//' | awk -F'/' '{ORS=""} {print "public/themes/"$4".min.css "; print; print "\n"}' | awk -F'$' '{system("curl -o "$1)}'
# Bootstrap Table
curl -o public/js/bootstrap-table.js https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.6.0/bootstrap-table.min.js
curl -o public/css/bootstrap-table.css https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.6.0/bootstrap-table.min.css
