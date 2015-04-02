#!/bin/sh

# Removes the fpga-api service
#    Parameter 1: fpga-api service name

if [ "$#" -ne 1 ]; then
    echo "Illegal number of parameters (service_name)"
    exit 1;
fi

# Stop the service
sudo service ${1} stop
# Remove the links
sudo rm -f /etc/rc0.d/K01${1}
sudo rm -f /etc/rc1.d/K01${1}
sudo rm -f /etc/rc2.d/S03${1}
sudo rm -f /etc/rc3.d/S03${1}
sudo rm -f /etc/rc4.d/S03${1}
sudo rm -f /etc/rc5.d/S03${1}
sudo rm -f /etc/rc6.d/K01${1}
# Remove the service file
sudo rm -f /etc/init.d/${1}