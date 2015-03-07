#!/bin/sh - 

# Launches the recorder
# Example of execution: sudo -b nohup ./bin/launchRecorder.sh PORT BYTES_TO_CAPTURE SIMPLE_FILE

# TODO: Delete after testing
sleep 9000

if [ "$#" -ne 3 ]; then
    echo "Error: illegal number of parameters (bitstream)"
    exit 1;
fi

sudo ./bin/writeControl -n -1 -l ${1} -r
sudo -b nohup ./bin/card2host -n ${2} ${3} > /dev/null 2>&1