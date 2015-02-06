#!/bin/sh
sudo find . -type d -exec sudo chmod 755 {} \;
sudo find . -type f -exec sudo chmod 644 {} \;
sudo chmod +x scripts/*
sudo chmod +x -R iojs/*
sudo chmod +x bin/*
