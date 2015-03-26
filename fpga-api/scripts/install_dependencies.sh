#!/bin/sh

# Run from the fpga server

# Install io.js
CURRENT=$(./iojs/bin/iojs -v)
VERSION=$(curl -L -s http://iojs.org/dist/latest/ \
    | egrep -o '[0-9]+\.[0-9]+\.[0-9]+' \
    | tail -n1)
# PLATFORM and ARCH are not set to linux x64 but the FPGA only will work properly in it. It is set dynamically for debug purposes
PLATFORM="$(uname | tr 'A-Z' 'a-z')"
ARCH="$(uname -m | sed 's/x86_64/x64/g' | sed 's/i.86/x86/g')"
PREFIX="iojs"

if test "v$VERSION" != "$CURRENT"; then
    echo "Installing io.js v$VERSION ..."
    mkdir -p "$PREFIX" && \
    curl -# -L http://iojs.org/dist/v$VERSION/iojs-v$VERSION-$PLATFORM-$ARCH.tar.gz \
      | tar xzvf - --strip-components=1 -C "$PREFIX"
else
    echo "Latest stable version of io.js already installed."
fi

# Install express
rm -rf "node_modules"
./iojs/bin/npm install express
./iojs/bin/npm install supervisor -g
./iojs/bin/npm install