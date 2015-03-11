// Common functions for the scripts

// Package dependencies
var fs = require('fs');
var path = require('path');
var config = require('../config.js');


// Module exports

// Checks the timestamp and discard not valid requests
function handleRequest(req, res, next) {
  if (!validTimestamp(req)) {
    res.sendStatus(408);
  } else {
    next();
  }
};
exports.handleRequest = handleRequest;

// Logs an error with a date
function logError(string) {
  var logHeader = '[' + new Date().toUTCString() + ']: ';
  console.error(logHeader + string);
};
exports.logError = logError;

// Reads a json in the messages folder
function readJSON(file, callback) {
  var filePath = path.resolve(__dirname, '../messages/', file + '.json');
  fs.readFile(filePath, 'utf8', function (err, data) {
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

// Sends a json as a response
function sendJSON(file, res, code) {
  var filePath = path.resolve(__dirname, '../messages/', file + '.json');
  fs.readFile(filePath, 'utf8', function (err, data) {
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

// Sends a jsonp as a response
function sendJSONP(file, res, code) {
  var filePath = path.resolve(__dirname, '../messages/', file + '.json');
  fs.readFile(filePath, 'utf8', function (err, data) {
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

// Parses an etime string to the number of seconds it represents
function etime2seconds(etime) {
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

// Gets the delay (in seconds) between the petition timestamp and the petition
function getDelay(req) {
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



// Internal functions

// Checks if a timestamp is valid
function validTimestamp(req) {
  // Set it off if MAX_DELAY is <= 0
  if(config.MAX_DELAY <= 0) {
    return true;
  }
  // Get the delay and compare
  var delay = getDelay(req);
  if (delay === false) {
    return false;
  }
  return (getDelay(req) <= config.MAX_DELAY);
};