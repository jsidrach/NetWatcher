#!/bin/sh

# Install/Updates the fpga server
# Run from the fpga-api dir

# Change configuration here
SERVER_IP=berilio.ii.uam.es
SERVER_PATH=/home/hpcn/JSid/fpga-api/
# End of configuration

USER=root

# Set up connection
SSHSOCKET=~/.ssh/$USER@$SERVER_IP
ssh -M -f -N -o ControlPath=$SSHSOCKET $USER@$SERVER_IP
RSYNCSOCKET="ssh -o ControlPath=${SSHSOCKET}"

# Sync server content
rsync -avzR -e "$RSYNCSOCKET" . ${USER}@${SERVER_IP}:${SERVER_PATH}
# Install server dependencies
#     Change dir to server path
#     Create data folder if it doesn't exist
#     Install (download) the server dependencies
#     Change permissions
#     Create the fpga-api service
#     Restart the fpga-api service
ssh -t -o ControlPath=$SSHSOCKET ${USER}@${SERVER_IP} "cd ${SERVER_PATH}
                             [ -d data ] || mkdir data
                             chmod +x ./scripts/do_chmod.sh
                             ./scripts/install_dependencies.sh
                             ./scripts/do_chmod.sh
                             ./scripts/install_service.sh ${SERVER_PATH} ${USER}"
# Close the connection
ssh -S $SSHSOCKET -O exit $USER@$SERVER_IP
# Don't forget to login in your remote server and start it: sudo service fpga-api start


