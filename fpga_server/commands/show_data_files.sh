#!/bin/bash

# Shows a CSV with the files in this directory

echo "name|extension|size|date" &&
ls -l ./data/ --time-style=long-iso \
| grep -v ^total. \
| grep -E '^[^d]' \
| awk '
  function extension(file, a, n) {
    n = split(file, a, ".")
    if(n == 1)
      return " "
    return a[n]
  }
  function name(file, a, n, result)
  {
    n = split(file, a, ".")
    if(n == 1)
      return a[1]
    for (i = 1; i < n; i++)
      result = result sep a[i]
    return result
  }
  { printf "%s|%s|%s|%s %s\n", name($8), extension($8), $5, $6, $7 }'