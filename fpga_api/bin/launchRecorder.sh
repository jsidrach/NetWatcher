#!/bin/sh - 

sudo nohup ./bin/launchRecorder.sh PORT BYTES_TO_CAPTURE SIMPLE_FILE

sudo ./bin/writeControl -n -1 -l <puerto> -r
sudo ./bin/card2host -n <bytes_to_capture> <file.simple>

#  the fpga must be programmed in recorder mode and no other ./launchRecorder.sh or card2host program active
#  file.simple must exist and be a valid simple capture
#  bytes_to_capture > 0
#  l in [0,3]
#
#  INFO
#  ps -eo etime,command | grep launchRecorder.sh | grep -v grep | head -n1
#  "capture": "", > SIMPLE_FILE
#  "elapsed_time": "",
#        ([[dd-]hh:]mm:ss)
#  "bytes_captured": "", > actualsize=$(wc -c "$file" | cut -f 1 -d ' ')
#  "bytes_total": "", > BYTES_TO_CAPTURE
#  "port": "" > PORT