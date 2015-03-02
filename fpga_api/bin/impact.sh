#!/bin/sh

# Programs the FPGA
#    Parameter 1: bitstream file

if [ "$#" -ne 1 ]; then
    echo "Error: illegal number of parameters (bitstream)"
    exit 1;
fi

if [ ! -f $1 ]; then
    echo "Error: bitstream not found"
    exit 1;
fi

echo "setMode -bscan
setCable -p auto
identify
assignfile -p 1 -file $1
program -p 1 -prog
quit" > impact.bat

# impact -batch impact.bat
/opt/Xilinx/14.6/ISE_DS/ISE/bin/lin64/impact -batch impact.bat
RESULT=$?
rm -f impact.bat
exit $RESULT