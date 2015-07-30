#!/bin/sh -

# Launches the player
# Example of execution: sudo -b nohup ./bin/launchPlayer.sh MASK IFG LOOP SIMPLE_FILE

if [ "$#" -ne 4 ]; then
    echo "Error: illegal number of parameters (MASK IFG LOOP SIMPLE_FILE)"
    exit 1;
fi

# http://www.cyberciti.biz/tips/shell-root-user-check-script.html
# Make sure only root can run our script
if [[ $EUID -ne 0 ]]; then
   echo "This script must be run as root" 1>&2
   exit 1
fi

if [ "$3" = "0" ]
then
  ./bin/writeControl -i ${2} -l ${1} -r &&
  ./bin/host2card ${4}
else
  ./bin/writeControl -i ${2} -l ${1} -r &&
  ./bin/host2card ${4} -n -1
fi

sleep 5
