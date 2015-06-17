#!/bin/bash

# For every svg file
find ./graphics -name "*.svg" -print0 | while IFS= read -r -d $'\0' line; do
    # Remove previous pdf
    rm -f "${line%.*}".pdf
    # Export svg to pdf
    inkscape -z -C --file="$line" --export-pdf="${line%.*}".pdf
done
