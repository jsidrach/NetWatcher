// Configuration file
var config = {}

// Server port
config.PORT = 1337;

// Captures dir (end it with /)
config.CAPTURES_DIR = 'data/';

// Maximum delay of petitions (in seconds)
// Set to <= 0 to not control the dealy of the petition
config.MAX_DELAY = 30;

module.exports = config;