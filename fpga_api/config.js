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

// Captures dir (end it with /)
config.CAPTURES_DIR = 'data/';

// Raid active
config.RAID = false;

// Raid mount point
config.RAID_DEV = '/dev/md0';

// Raid disks
config.RAID_DISKS = [
  '/dev/sdc',
  '/dev/sdd',
  '/dev/sde',
  '/dev/sdf',
  '/dev/sdg',
  '/dev/sdh',
  '/dev/sdi',
  '/dev/sdj'
];

// End configuration

// Exports the module
module.exports = config;