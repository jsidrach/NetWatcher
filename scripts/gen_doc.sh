#!/bin/bash
# Generate the front-end documentation
./vendor/bin/phpDocumentor.phar --ignore vendor/,public/ --template="clean" -d ./   -t ./docs/front-end --title="NetWatcher Front-End Documentation"
# Generate the back-end documentation
cd ./vendor/apidoc/apidoc
sudo npm install
cd ../../..
./vendor/apidoc/apidoc/bin/apidoc -i fpga_api/ -o docs/back-end/
