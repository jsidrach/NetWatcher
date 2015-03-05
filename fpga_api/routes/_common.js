// Common functions for the scripts

// Package dependencies
var scripts = require('child_process');
var fs = require('fs');
var path = require('path');
var config = require('../config.js');

// Function exports

// Checks the timestamp and discard not valid requests
exports.handleRequest = function (req, res, next) {
  if (!validTimestamp(req)) {
    res.sendStatus(408);
  } else {
    next();
  }
};

// Logs an error with a date
exports.logError = function (string) {
  var logHeader = '[' + new Date().toUTCString() + ']: ';
  console.error(logHeader + string);
};

// Reads a json in the messages folder
exports.readJSON = function (file, callback) {
  fs.readFile(path.resolve(__dirname, '../messages/', file + '.json'), 'utf8', function (err, data) {
    var obj;
    if (err) {
      obj = {
        message: 'Not Found'
      };
    } else {
      obj = JSON.parse(data);
    }
    callback(obj);
  });
};

// Sends a json as a response
exports.sendJSON = function (file, res, code) {
  fs.readFile(path.resolve(__dirname, '../messages/', file + '.json'), 'utf8', function (err, data) {
    var obj;
    if (err) {
      obj = {
        message: 'Not Found'
      };
    } else {
      obj = JSON.parse(data);
    }
    res.status(code).json(obj);
  });
}

// Sends a jsonp as a response
exports.sendJSONP = function (file, res, code) {
  fs.readFile(path.resolve(__dirname, '../messages/', file + '.json'), 'utf8', function (err, data) {
    var obj;
    if (err) {
      obj = {
        message: 'Not Found'
      };
    } else {
      obj = JSON.parse(data);
    }
    res.status(code).jsonp(obj);
  });
}

// Parses a csv into a JSON string
exports.csv2JSONstring = function (csv) {
  var lines = csv.split("\n");
  var result = [];
  var headers = lines[0].split("|");

  for (var i = 1; i < lines.length; i++) {
    var obj = {};
    var currentline = lines[i].split("|");

    if (currentline.length == headers.length) {
      for (var j = 0; j < headers.length; j++) {
        obj[headers[j]] = currentline[j];
      }
      result.push(obj);
    }
  }

  // Return JSON
  return JSON.stringify(result);
};

// Parses a csv into a JSON Javascript object
exports.csv2JSONobject = function (csv) {
  var lines = csv.split("\n");
  var result = [];
  var headers = lines[0].split("|");

  for (var i = 1; i < lines.length; i++) {
    var obj = {};
    var currentline = lines[i].split("|");

    if (currentline.length == headers.length) {
      for (var j = 0; j < headers.length; j++) {
        obj[headers[j]] = currentline[j];
      }
      result.push(obj);
    }
  }

  // Return Javascript Object
  return result;
};

// Exports that are also internal functions

// Gets the delay (in seconds) between the petition timestamp and the petition
function getDelay (req) {
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

// Checks if a new name is available and valid
function validNewName(name) {
  // Valid name
  if (!validName(name)) {
    return false;
  }
  // File already exists
  if (fs.existsSync(config.CAPTURES_DIR + name)) {
    return false;
  }
  return true;
};
exports.validNewName = validNewName;

// Checks if a capture has a valid simple format
function validSimpleCapture(name) {
  if (!validFile(name)) {  console.log('hey2');
    return false;
  }
  // 3rd and 4th byte are 0x69
  var magicNumber = new Buffer([0x69, 0x69]);
  var buffer = new Buffer([0x00, 0x00]);
  var fd = fs.openSync(config.CAPTURES_DIR + name, 'r');
  var len = fs.readSync(fd, buffer, 0, 2, 2);
  if (len != magicNumber.length) {
    return false;
  }
  return ((magicNumber[0] == buffer[0]) && (magicNumber[1] == buffer[1]));
}
exports.validSimpleCapture = validSimpleCapture;

// Checks if a capture has a valid pcap format
function validPcapCapture(name) {
  if (!validFile(name) || validSimpleCapture(name)) {
    return false;
  }
  // Check if it is a valid pcap capture
  try {
    scripts.execSync(
      'export LD_LIBRARY_PATH=bin/caputils/ && ./bin/caputils/capinfos -t "'
      + config.CAPTURES_DIR + name + '" 2> /dev/null | grep "File type"'
      );
  } catch (error) {
    return false;
  }
  return true;
}
exports.validPcapCapture = validPcapCapture;

// Checks if a capture exists with a given name (full name)
function validCapture(name) {
  return (validSimpleCapture(name) || validPcapCapture(name));
}
exports.validCapture = validCapture;



// Internal functions

// Checks if a timestamp is valid
function validTimestamp(req) {
  var delay = getDelay(req);
  if(delay === false) {
    return false;
  }
  return (getDelay(req) <= config.MAX_DELAY);
};

// Checks if a name is valid (syntactically)
function validName(name) {
  var flag = true;
  // Name is valid if it does not have the following substrings:
  ['\\/', '\\.\\.', '\\$', '\\~'].every(function (entry) {
    if (name.search(entry) != -1) {
      flag = false;
      return false;
    }
    return true;
  });
  return flag;
};

// File exists and it is valid
function validFile(name) {
  // Valid name
  if (!validName(name)) {
    return false;
  }
  // File already exists
  if (!fs.existsSync(config.CAPTURES_DIR + name)) {
    return false;
  }
  return true;
};