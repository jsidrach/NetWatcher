// Manager module

// Package dependencies
var scripts = require('child_process');
var common = require('./_common.js');
var manager_utils = require('./manager_utils.js');


// Routes

// /system/reboot
// Reboots the system
exports.reboot = function (req, res) {
  // Reboot the system
  scripts.exec(manager_utils.rebootCommand, function (error, stdout, stderr) {
    if (error) {
      // Internal error
      common.logError(stderr);
      res.sendStatus(500);
      return;
    }
    common.sendJSON('system_reboot_ok', res, 200);
  });
};

// /player/init
// Programs the FPGA as a player and reboots the system
exports.initPlayer = function (req, res) {
  manager_utils.initFPGA(req, res, false);
};

// /recorder/init
// Programs the FPGA as a recorder and reboots the system
exports.initRecorder = function (req, res) {
  manager_utils.initFPGA(req, res, true);
};

// /player/install
// Installs the driver and mounts the FPGA as a player
exports.installPlayer = function (req, res) {
  manager_utils.installFPGA(req, res, false);
};

// /recorder/install
// Installs the driver and mounts the FPGA as a recorder
exports.installRecorder = function (req, res) {
  manager_utils.installFPGA(req, res, true);
};