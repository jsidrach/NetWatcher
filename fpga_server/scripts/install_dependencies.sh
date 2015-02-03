#!/bin/sh

CURRENT=$(node -v)
VERSION=$(curl -L -s http://nodejs.org/dist/latest/ \
    | egrep -o '[0-9]+\.[0-9]+\.[0-9]+' \
    | tail -n1)
PLATFORM=linux
ARCH=x86
PREFIX="node"

if test "v$VERSION" != "$CURRENT"; then
    echo "Installing node v$VERSION ..."
    mkdir -p "$PREFIX" && \
    curl -# -L http://nodejs.org/dist/v$VERSION/node-v$VERSION-$PLATFORM-$ARCH.tar.gz \
      | tar xzvf - --strip-components=1 -C "$PREFIX"
else
    echo "Latest stable version of node already installed."
fi

# Install express
rm -rf "node_modules"
./node/bin/npm install express
./node/bin/npm install
