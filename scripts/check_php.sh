#!/bin/bash
# Checks the syntax of php files recursively
find . -iname "*.php" -exec php -l {} \; | grep -i "Errors.parsing"
