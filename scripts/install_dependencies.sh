#!/bin/bash
# Intall the required packages
sudo apt-get install php5-common libapache2-mod-php5 php5-cli php5-xsl GraphViz curl gettext poedit git libcurl3 libcurl3-dev php5-curl
# Active mod_rewrite
sudo a2enmod rewrite
# Restart apache
sudo service apache2 restart