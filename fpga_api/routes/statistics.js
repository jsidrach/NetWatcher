// Real Time Statistics module

// Package dependencies
var config = require('../config.js');
var common = require('./_common.js');
var statistics_utils = require('./statistics_utils.js');


// Routes

// /ping
// Simple ping
exports.ping = function (req, res) {
  common.sendJSONP('statistics_ping', res, 200);
};

// /delay
// Seconds of delay between the client and the server (of timestamps)
exports.delay = function (req, res) {
  var delay = common.getDelay(req);
  if (delay === false) {
    res.sendStatus(408);
  } else {
    common.readJSON('statistics_delay', function (ans) {
      ans.delay = delay;
      ans.maxDelay = config.MAX_DELAY;
      res.status(200).json(ans);
    });
  }
};

// /status
// Status of the FPGA
exports.status = function (req, res) {
  // Finite State Machine. Every transition to a new status (state) is checked with a callback test function
  statistics_utils.nextCallback(res, [
    statistics_utils.hugePagesOn,
    statistics_utils.initializedFPGA,
    statistics_utils.mountedFPGA,
    statistics_utils.statusFPGA
  ]);
};

// /storage/stats
// Statistics of the storage
exports.storageStats = function (req, res) {
  // TODO
  res.sendStatus(404);
};

// /storage/raid
// Delete (format and reset) the storage raid
exports.deleteRaid = function (req, res) {
  // TODO
  res.sendStatus(404);
};