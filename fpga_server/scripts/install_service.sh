#!/bin/sh

# Install/Updates the fpga server
# Run from the fpga_server dir
#    Parameter 1: server path
#    Parameter 2: user

if [ "$#" -ne 2 ]; then
    echo "Illegal number of parameters (server_path and user)"
    exit 0;
fi

FOLDER_EXP='s|dir=\".*\"|dir=\"'${1}'\"|g'
SERVER_EXP='s|user=\".*\"|user=\"'${2}'\"|g'
sed -i -e ${FOLDER_EXP} -e ${SERVER_EXP} ./scripts/fpga_api
# Install the service
sudo cp -f ./scripts/fpga_api /etc/init.d
# Set the service as a default on startup
sudo update-rc.d fpga_api defaults
# Restart the service
sudo service fpga_api restart