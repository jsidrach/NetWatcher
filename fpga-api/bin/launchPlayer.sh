#!/bin/sh -

# Launches the player
# Example of execution: sudo -b nohup ./bin/launchPlayer.sh MASK IFG LOOP SIMPLE_FILE

if [ "$#" -ne 4 ]; then
    echo "Error: illegal number of parameters (MASK IFG LOOP SIMPLE_FILE)"
    exit 1;
fi

if [ "$3" = "0" ]
then
  sudo ./bin/writeControl -i ${2} -l ${1} -r &&
  sudo ./bin/host2card ${4}
else
  sudo ./bin/writeControl -i ${2} -l ${1} -r &&
  sudo ./bin/host2card ${4} -n -1
fi
sleep 5
