// Real Time Statistics module

// Package dependencies
var scripts = require('child_process');
var fs = require('fs');
var path = require('path');
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
  scripts.exec(checkHugePagesOff).on('exit', function (code) {
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
  scripts.exec(checkInitFPGAOn).on('exit', function (code) {
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
  scripts.exec(checkFPGAMountedOn).on('exit', function (code) {
    if (code != 0) {
      common.sendJSON(status_json, res, 200);
      return;
    }
    nextCallback(res, callbackList);
  });
};

// Status of the FPGA (after being mounted)
statusFPGA = function (res, callbackList) {
  modeFPGA(function (ans) {
    // Set the type (player/recorder)
    if(ans == 'recorder') {
      runningFPGA(true, function(isRunning) {
        if(isRunning) {
          sendDataRecording(res);
        } else {
          common.sendJSON('status_4_1_recorder_ready', res, 200);
        }
      });
    } else if(ans == 'player') {
      runningFPGA(false, function(isRunning) {
        if(isRunning) {
          common.sendJSON('status_4_2_playing', res, 200);
        } else {
          common.sendJSON('status_4_1_player_ready', res, 200);
        }
      });
    } else {
      common.sendJSON('status_3_mount_off', res, 200);      
    }
  });
};

// Internal+External Functions

// Gets the mode of the FPGA (player/recorder/error)
function modeFPGA(callback) {
  scripts.exec('cat /proc/nfp/nfp_report | tail -n 1', function (error, stdout, stderr) {
    var ans;
    if (error) {
      ans = 'error';
      common.logError(stderr);
    }
    // Set the type (player/recorder)
    else if (stdout.indexOf('PLA') != -1) {
      ans = 'player';
    } else if (stdout.indexOf('REC') != -1) {
      ans = 'recorder';
    } else {
      ans = 'error';
    }
    callback(ans);
  });
};
exports.modeFPGA = modeFPGA;

// Gets a boolean value that represents if the FPGA is running (true: yes, false: no)
function runningFPGA(recorder, callback) {
  var command = recorder ? 'pgrep launchRecorder || pgrep card2host' : 'pgrep launchPlayer || pgrep host2card';
  scripts.exec(command).on('exit', function(code) {
    callback(code == 0);
  });
};
exports.runningFPGA = runningFPGA;

// Other Internal FUnctions

// Sends the current info of the record in progress
function sendDataRecording(res) {
  common.readJSON('status_4_2_recording', function (ans) {
    scripts.exec('ps -eo etime,command | grep launchRecorder.sh | grep -v grep | head -n1', function (error, stdout, stderr) {
      if (error) {
        // Internal error
        common.logError(stderr);
        res.sendStatus(500);
        return;
      }    
      // Output format:
      //    [etime] sudo -b nohup ./bin/launchRecorder.sh port bytes simple
      var parts = stdout.match(/\S+/g);
      if(parts.length >= 8) {
        ans.elapsed_time = common.etime2seconds(parts[0]);
        ans.bytes_total = parseInt(parts[6]);
        ans.port = parseInt(parts[5]);
        var capturePath = parts.slice(7).join(' ');
        var captureStats = fs.statSync(capturePath);
        ans.bytes_captured = captureStats['size'];
        ans.capture = path.basename(capturePath);
      }
      res.status(200).json(ans);
    });
  });
};