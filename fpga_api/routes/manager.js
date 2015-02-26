// Manager module

// Package dependencies
var scripts = require('child_process');
var config = require('../config.js');
var common = require('./_common.js');
var rebootScript = 

// /reboot
// Reboots the system
exports.reboot = function (req, res) {
  // Reboots the system
  scripts.exec('sudo /sbin/reboot', function (error, stdout, stderr) {
    if (error) {
      common.logError(stderr);
      res.sendCode(500);
      return;
    }
    common.sendJSON('reboot_ok', res, 200);
  });
};