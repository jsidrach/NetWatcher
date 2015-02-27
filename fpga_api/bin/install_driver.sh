#!/bin/sh - 

# Remove the previous driver
rmmod ./bin/nfp_driver.ko

# Instantiate the driver
insmod ./bin/nfp_driver.ko
chmod 777 /dev/nfp

# Mount with HugePages
mkdir -p /mnt/huge
mount -t hugetlbfs none /mnt/huge
