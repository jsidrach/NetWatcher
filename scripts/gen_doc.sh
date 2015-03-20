#!/bin/bash
# Generate the documentation
./vendor/bin/phpDocumentor.phar --ignore vendor/,public/ --template="clean" -d ./   -t ./docs/front-end --title="NetWatcher Front-End Documentation"
