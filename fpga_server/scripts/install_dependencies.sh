#!/bin/sh

CURRENT=$(iojs -v)
VERSION=$(curl -L -s http://iojs.org/dist/latest/ \
    | egrep -o '[0-9]+\.[0-9]+\.[0-9]+' \
    | tail -n1)
PLATFORM=linux
ARCH=x86
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
./iojs/bin/npm install
