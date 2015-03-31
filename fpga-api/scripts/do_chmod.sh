#!/bin/sh
# Change directory permissions
sudo find . -type d -exec sudo chmod 755 {} \;
# Change file permissions
sudo find . -type f -exec sudo chmod 644 {} \;
# Executable directories
sudo chmod +x scripts/*
sudo chmod +x -R iojs/*
sudo chmod +x -R bin/*
