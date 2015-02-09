#!/bin/sh

# Install/Updates the fpga server
# Run from the fpga_server dir

# Change configuration here
SERVER_IP=localhost
SERVER_PATH=/home/jsid/Desktop/fpga_test/
USER=jsid
# End of configuration

# Create destination folder
ssh ${USER}@${SERVER_IP} "[ -d $SERVER_PATH ] || mkdir $SERVER_PATH; exit;" &&
# Copy server content
scp -r * ${USER}@${SERVER_IP}:${SERVER_PATH} &&
# Install server dependencies
ssh -t ${USER}@${SERVER_IP} "cd ${SERVER_PATH}; [ -d data ] || mkdir data; chmod +x ./scripts/do_chmod.sh; ./scripts/install_server.sh; ./scripts/do_chmod.sh; exit;"