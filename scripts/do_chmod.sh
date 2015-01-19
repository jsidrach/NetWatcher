#!/bin/bash
# Change directory permissions
sudo find . -type d -exec sudo chmod 755 {} \;
sudo find . -type f -exec sudo chmod 644 {} \;
sudo chmod 777 -R log/ files/ config/
sudo chmod +x scripts/*
sudo chmod +x -R lib/vendor/*

