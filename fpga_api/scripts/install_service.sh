#!/bin/sh

# Install/Updates the FPGA server
# Run from the fpga_api dir
#    Parameter 1: server path
#    Parameter 2: user

if [ "$#" -ne 2 ]; then
    echo "Illegal number of parameters (server_path and user)"
    exit 1;
fi

FOLDER_EXP='s|dir=\".*\"|dir=\"'${1}'\"|g'
SERVER_EXP='s|user=\".*\"|user=\"'${2}'\"|g'
sed -i -e ${FOLDER_EXP} -e ${SERVER_EXP} ./scripts/fpga_api
# Install the service
sudo cp -f ./scripts/fpga_api /etc/init.d
sudo chmod +x /etc/init.d/fpga_api
# Set the service as a default on startup
sudo ln -sf /etc/init.d/fpga_api /etc/rc0.d/K01fpga_api
sudo ln -sf /etc/init.d/fpga_api /etc/rc1.d/K01fpga_api
sudo ln -sf /etc/init.d/fpga_api /etc/rc2.d/S03fpga_api
sudo ln -sf /etc/init.d/fpga_api /etc/rc3.d/S03fpga_api
sudo ln -sf /etc/init.d/fpga_api /etc/rc4.d/S03fpga_api
sudo ln -sf /etc/init.d/fpga_api /etc/rc5.d/S03fpga_api
sudo ln -sf /etc/init.d/fpga_api /etc/rc6.d/K01fpga_api