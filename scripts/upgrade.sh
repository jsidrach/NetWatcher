#!/bin/bash
# Upgrades the libraries
rm -f "composer.lock"
mkdir -p vendor/bin
# Get Composer
curl -sS https://getcomposer.org/installer | php -- --install-dir=vendor/bin
# Get Back-End Dependencies
./vendor/bin/composer.phar install
curl -o vendor/bin/phpDocumentor.phar http://www.phpdoc.org/phpDocumentor.phar
# Get Front-End Dependencies
sudo php vendor/bin/bowerphp install
# Move the libraries
cp vendor/bower_components/jquery/dist/jquery.min.js public/js/
cp vendor/bower_components/jquery/dist/jquery.min.map public/js/
cp vendor/bower_components/bootstrap/dist/js/bootstrap.min.js public/js/
cp vendor/bower_components/bootstrap/dist/css/bootstrap.min.css public/css/
cp vendor/bower_components/bootstrap/dist/fonts/* public/fonts/
cp vendor/bower_components/bootstrap-table/dist/bootstrap-table.min.js public/js/
cp vendor/bower_components/bootstrap-table/dist/bootstrap-table.min.css public/css/
cp vendor/bower_components/chartjs/Chart.min.js public/js/
cp vendor/bower_components/remarkable-bootstrap-notify/dist/bootstrap-notify.min.js public/js/
# Default Bootstrap Theme
mv vendor/bower_components/bootstrap/dist/css/bootstrap-theme.min.css public/themes/default.min.css
# Bootstrap Themes
curl http://api.bootswatch.com/3/ | cut -b2- | tr '"' '\n' | grep .min.css | grep latest | sed 's/^..//' | awk -F'/' '{ORS=""} {print "public/themes/"$4".min.css "; print; print "\n"}' | awk -F'$' '{system("curl -o "$1)}'