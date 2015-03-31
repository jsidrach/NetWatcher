#!/bin/bash
# Change directory permissions
sudo find . -type d -exec sudo chmod 755 {} \;
# Change file permissions
sudo find . -type f -exec sudo chmod 644 {} \;
# Special directories
sudo chmod 777 -R "log/" "config/"
# Executable directories
sudo chmod +x "scripts/"*
sudo chmod +x -R "vendor/"*
# FPGA Web Service permissions
cd fpga-api
sudo chmod +x "./scripts/do_chmod.sh"
sudo "./scripts/do_chmod.sh"
cd ..
