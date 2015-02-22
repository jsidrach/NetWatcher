#!/bin/bash
# Change directory permissions
sudo find . -type d -exec sudo chmod 755 {} \;
sudo find . -type f -exec sudo chmod 644 {} \;
sudo chmod 777 -R "log/" "config/"
sudo chmod +x "scripts/"*
sudo chmod +x -R "lib/vendor/"*
cd fpga_api
sudo chmod +x "./scripts/do_chmod.sh"
sudo "./scripts/do_chmod.sh"
cd ..
