#!/bin/sh

# Programs the FPGA
#    Parameter 1: bitstream file
#    Parameter 2: xilinx impact binary

if [ "$#" -ne 2 ]; then
    echo "Error: illegal number of parameters (bitstream xilinx_impact_binary)"
    exit 1;
fi

if [ ! -f $1 ]; then
    echo "Error: bitstream not found"
    exit 1;
fi

if [ ! -f $2 ]; then
    echo "Error: impact binary not found"
    exit 1;
fi

echo "setMode -bscan
setCable -p auto
identify
assignfile -p 1 -file $1
program -p 1 -prog
quit" > impact.bat

# impact -batch impact.bat
$2 -batch impact.bat
RESULT=$?
rm -f impact.bat
rm -f _impactbatch.log
exit $RESULT