// testing configuration
// Configuration file
var config = {}

// Begin configuration

// Server prefix
config.BASE_PREFIX = '/fpga/api';

// Server port
config.PORT = 1337;

// Maximum delay of petitions (in seconds)
// Set to <= 0 to not control the delay of the petition
config.MAX_DELAY = 30;

// Xilinx's impact binary
config.IMPACT_BIN = '/opt/Xilinx/14.6/ISE_DS/ISE/bin/lin64/impact';

// Captures dir (end it with /)
config.CAPTURES_DIR = '/home/hpcn/Desktop/Trazas/';

// Raid active
config.RAID = false;

// Raid mount point
config.RAID_DEV = '/dev/sda';

// Raid disks
config.RAID_DISKS = [
  '/dev/sda',
  '/dev/sda',
  '/dev/sda'
];

// End configuration

// Exports the module
module.exports = config;
