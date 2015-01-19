#!/bin/bash
# Generate the documentation
./lib/vendor/phpDocumentor.phar --ignore lib/vendor/ --template="clean" -d ./   -t ./docs/api --title="Network Tester Documentation"
