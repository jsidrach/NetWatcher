// Real Time Statistics module

// Package dependencies
var scripts = require('child_process');
var common = require('./_common.js');


// /ping
// Simple ping
exports.ping = function (req, res) {
  res.sendStatus(200);
};

// /status
// Status of the FPGA
exports.status = function (req, res) {
  // Finite State Machine. Every transition to a new status (state) is checked with a callback test function
  nextCallback(res, [hugePagesOn, initializedFPGA, lastTest]);
};

// Internal functions

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
  var code_script = scripts.exec('cat /proc/meminfo | grep "HugePages_Total:       0"');
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
  // 0 if huge pages is active, 1 otherwise
  var code_script = scripts.exec('lspci | grep 19aa:e004');
  code_script.on('exit', function (code) {
    if (code != 0) {
      common.sendJSON(status_json, res, 200);
      return;
    }
    nextCallback(res, callbackList);
  });
};

// Test function
lastTest = function (res, callbackList) {
  common.sendJSON('status_test', res, 200);
}