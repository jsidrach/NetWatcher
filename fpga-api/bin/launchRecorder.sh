#!/bin/sh -

# Launches the recorder
# Example of execution: sudo -b nohup ./bin/launchRecorder.sh PORT BYTES_TO_CAPTURE SIMPLE_FILE

if [ "$#" -ne 3 ]; then
    echo "Error: illegal number of parameters (PORT BYTES_TO_CAPTURE SIMPLE_FILE)"
    exit 1;
fi

sudo ./bin/writeControl -l ${1} -r &&
sudo ./bin/card2host ${3} -n ${2}
sleep 5

# TODO: testSimple para añadir el numero de paquetes al final
