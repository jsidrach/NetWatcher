// Common functions for the scripts

// Package dependencies
var fs = require('fs');
var path = require('path');
var config = require('../config.js');

// logging utilities
var util = require('util');
var log_stdout = process.stdout;

// Module exports

// sources: http://stackoverflow.com/a/16713639
// http://stackoverflow.com/questions/8128894/remove-prefix-from-a-list-of-strings
function prettyName(path, funcName) {
  // strips the CWD prefix to the file path and add the function name as a suffix
  return path.replace(process.cwd() + '/', '') + ':' + funcName + '()';
};
exports.prettyName = prettyName;
exports.prettyName.displayName = prettyName(__filename, 'prettyName');

// Enables debugging messages
var DEBUG_ON = true;

function logTimestamped(msg, type) {
  log_stdout.write('[' + new Date().toISOString() + '] ' + type + ': ' + util.format(msg) + '\n');
};
exports.logTimestamped = logTimestamped;
exports.logTimestamped.displayName = prettyName(__filename, 'logTimestamped');

// debug messages will only appear if DEBUG_ON is true
function logDebug(msg) {
  if (DEBUG_ON) {
    logTimestamped(logDebug.caller.displayName + ' ' + msg, 'DEBUG');
  }
};
exports.logDebug = logDebug;
exports.logDebug.displayName = prettyName(__filename, 'logDebug');

// log to stdout
function log(msg) {
  logTimestamped(logDebug.caller.displayName + ' ' + msg, 'INFO');
};
exports.log = log;
exports.log.displayName = prettyName(__filename, 'log');

// log errors to stdout
function logError(msg) {
  logTimestamped(logDebug.caller.displayName + ' ' + msg, 'ERROR');
};
exports.logError = logError;
exports.logError.displayName = prettyName(__filename, 'logError');

// Checks the timestamp and discard not valid requests
function handleRequest(req, res, next) {
  logDebug('');
  if (!validTimestamp(req)) {
    process.nextTick(function() {
      res.sendStatus(408);
    });
  } else {
    process.nextTick(function() {
      next();
    });
  }
};
exports.handleRequest = handleRequest;
exports.handleRequest.displayName = prettyName(__filename, 'handleRequest');

// Reads a json in the messages folder
function readJSON(file, callback) {
  logDebug('');
  var filePath = path.resolve(__dirname, '../messages/', file + '.json');
  fs.readFile(filePath, 'utf8', function(err, data) {
    var obj;
    if (err) {
      logError('Unable to read ' + filePath);
      obj = {
        message: 'Not Found'
      };
    } else {
      obj = JSON.parse(data);
    }
    callback(obj);
  });
};
exports.readJSON = readJSON;
exports.readJSON.displayName = prettyName(__filename, 'readJSON');

// Sends a json as a response
function sendJSON(file, res, code) {
  logDebug('');
  var filePath = path.resolve(__dirname, '../messages/', file + '.json');
  fs.readFile(filePath, 'utf8', function(err, data) {
    var obj;
    if (err) {
      logError('Unable to read ' + filePath);
      obj = {
        message: 'Not Found'
      };
    } else {
      obj = JSON.parse(data);
    }
    res.status(code).json(obj);
  });
};
exports.sendJSON = sendJSON;
exports.sendJSON.displayName = prettyName(__filename, 'sendJSON');

// Sends a jsonp as a response
function sendJSONP(file, res, code) {
  logDebug('');
  var filePath = path.resolve(__dirname, '../messages/', file + '.json');
  fs.readFile(filePath, 'utf8', function(err, data) {
    var obj;
    if (err) {
      logError('Unable to read ' + filePath);
      obj = {
        message: 'Not Found'
      };
    } else {
      obj = JSON.parse(data);
    }
    res.status(code).jsonp(obj);
  });
};
exports.sendJSONP = sendJSONP;
exports.sendJSONP.displayName = prettyName(__filename, 'sendJSONP');

// Parses an etime string to the number of seconds it represents
function etime2seconds(etime) {
  logDebug('');
  // The format for etime is [[dd-]hh:]mm:ss
  var parts = etime.trim().split(':');
  var parts_length = parts.length;
  if (parts_length == 2) {
    return parseInt(parts[0]) * 60 + parseInt(parts[1]);
  } else if (parts_length == 3) {
    var parts_aux = parts[0].split('-');
    if (parts_aux.length > 1) {
      return ((parseInt(parts_aux[0]) * 24 + parseInt(parts_aux[1])) * 60 + parseInt(parts[1])) * 60 + parseInt(parts[2]);
    } else {
      return (parseInt(parts[0]) * 60 + parseInt(parts[1])) * 60 + parseInt(parts[2]);
    }
  }
};
exports.etime2seconds = etime2seconds;
exports.etime2seconds.displayName = prettyName(__filename, 'etime2seconds');

// Parses a mtime into a string
function mtime2string(mtime) {
  logDebug('');
  return mtime.toISOString().replace('T', ' ').substr(0, 19);
};
exports.mtime2string = mtime2string;
exports.mtime2string.displayName = prettyName(__filename, 'mtime2string');

// Gets the delay (in seconds) between the petition timestamp and the petition
function getDelay(req) {
  logDebug('');
  if (typeof req.headers.timestamp === 'undefined') {
    return false;
  }
  // 1 second precision
  var currentTimestamp = parseInt(Date.now() / 1000);
  var requestTimestamp = parseInt(req.headers.timestamp);
  if (isNaN(requestTimestamp)) {
    return false;
  }
  // Round request timestamp to the same precision
  if (requestTimestamp.toString().length > currentTimestamp.toString().length) {
    requestTimestamp = parseInt(requestTimestamp / (Math.pow(10, requestTimestamp.toString().length - currentTimestamp.toString().length)));
  }
  var delay = Math.abs(currentTimestamp - requestTimestamp);
  return delay;
};
exports.getDelay = getDelay;
exports.getDelay.displayName = prettyName(__filename, 'getDelay');



// Internal functions

// Checks if a timestamp is valid
function validTimestamp(req) {
  // Set it off if MAX_DELAY is <= 0
  if (config.MAX_DELAY <= 0) {
    return true;
  }
  // Get the delay and compare
  var delay = getDelay(req);
  if (delay === false) {
    return false;
  }
  return (getDelay(req) <= config.MAX_DELAY);
};