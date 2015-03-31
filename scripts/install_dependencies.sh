#!/bin/bash
# Intall the required packages
sudo apt-get install php5-common libapache2-mod-php5 php5-cli php5-xsl GraphViz curl gettext poedit git libcurl3 libcurl3-dev php5-curl npm
# Get node.js
curl -sL https://deb.nodesource.com/setup | sudo bash -
sudo apt-get install -y nodejs
# Active mod_rewrite
sudo a2enmod rewrite
# Restart apache
sudo service apache2 restart
# Create log directory
mkdir -p log