// Real Time Statistics module

// Package dependencies
var scripts = require('child_process');
var config = require('../config.js');
var common = require('./_common.js');

// Constant execs
var checkHugePagesOff = 'cat /proc/meminfo | grep "HugePages_Total:       0"';
exports.checkHugePagesOff = checkHugePagesOff;
var checkInitFPGAOn = 'lspci | grep 19aa:e004';
exports.checkInitFPGAOn = checkInitFPGAOn;
var checkFPGAMountedOn = 'lsmod | grep nfp_driver';
exports.checkFPGAMountedOn = checkFPGAMountedOn;

// /ping
// Simple ping
exports.ping = function (req, res) {
  common.sendJSONP('statistics_ping', res, 200);
};

// /delay
// Seconds of delay between the client and the server (of timestamps)
exports.delay = function (req, res) {
  var delay = common.getDelay(req);
  if(delay === false) {
    res.sendStatus(408);
  } else {
    common.readJSON('statistics_delay', function (ans) {
      ans.delay = delay;
      ans.maxDelay = config.MAX_DELAY
      res.status(200).json(ans);
    });
  }
};

// /status
// Status of the FPGA
exports.status = function (req, res) {
  // Finite State Machine. Every transition to a new status (state) is checked with a callback test function
  nextCallback(res, [hugePagesOn, initializedFPGA, mountedFPGA, statusFPGA]);
};

// Internal functions for the transitions of the Finite State Machine

// Executes the next callback
nextCallback = function (res, callbackList) {
  if (callbackList[0] instanceof Function) {
    callbackList[0](res, callbackList.slice(1));
  }
};

// HugePages active
hugePagesOn = function (res, callbackList) {
  var status_json = 'status_1_hugepages_off';
  // 0 if huge pages is not active, 1 if hugepages is active
  var code_script = scripts.exec(checkHugePagesOff);
  code_script.on('exit', function (code) {
    if (code == 0) {
      common.sendJSON(status_json, res, 200);
      return;
    }
    nextCallback(res, callbackList);
  });
};

// FPGA initialized checker
initializedFPGA = function (res, callbackList) {
  var status_json = 'status_2_init_off';
  // 0 if fpga initialized, 1 otherwise
  var code_script = scripts.exec(checkInitFPGAOn);
  code_script.on('exit', function (code) {
    if (code != 0) {
      common.sendJSON(status_json, res, 200);
      return;
    }
    nextCallback(res, callbackList);
  });
};

// FPGA mounted checker
mountedFPGA = function (res, callbackList) {
  var status_json = 'status_3_mount_off';
  // 0 if fpga is mounted, 1 otherwise
  var code_script = scripts.exec(checkFPGAMountedOn);
  code_script.on('exit', function (code) {
    if (code != 0) {
      common.sendJSON(status_json, res, 200);
      return;
    }
    nextCallback(res, callbackList);
  });
};

// Status of the FPGA (after being mounted)
statusFPGA = function (res, callbackList) {
  var code_script = scripts.exec('cat /proc/nfp/nfp_report', function (error, stdout, stderr) {
    if (error) {
      common.logError(stderr);
      common.sendJSON('status_3_mount_off', res, 200);
      return;
    }
    common.readJSON('status_4_fpga_ok', function (ans) {
      // Set the type (player/recorder)
      if (stdout.indexOf('PLA') != -1) {
        ans.type = 'player';
      } else if (stdout.indexOf('REC') != -1) {
        ans.type = 'recorder';
      } else {
        common.logError('Neither a player nor recorder');
        common.sendJSON('status_3_mount_off', res, 200);
        return;
      }

      // Set the code (running, paused, stop, etc.)
      // TODO: Ask type of codes, what they mean

      // Send the response
      res.status(200).json(ans);
    });
  });
};

// Other Internal Functions