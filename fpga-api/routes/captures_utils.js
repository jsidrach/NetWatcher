// Captures module auxiliary and internal functions

// Package dependencies
var scripts = require('child_process');
var async = require('async');
var path = require('path');
var fs = require('fs');
var config = require('../config.js');
var common = require('./_common.js');


// Module exports

// Gets an array of captures info from the CAPTURES_DIR 
function dataCaptures(simple, pcap, res) {
  // Validator callback
  var validator;
  if (simple && pcap) {
    validator = validCapture;
  } else if (simple) {
    validator = validSimpleCapture;
  } else {
    validator = validPcapCapture;
  }

  // Gets all the files in the CAPTURES_DIR 
  fs.readdir(config.CAPTURES_DIR, function(err, files) {
    if (err) {
      common.logError(err);
      res.sendStatus(500);
      return;
    }

    // Only valid captures
    async.filter(files,
      function(entry, callback) {
        fs.stat(config.CAPTURES_DIR + entry, function(err, stats) {
          if (err) {
            common.logError(err);
            callback(false);
          } else {
            validator(entry, function(valid) {
              callback(valid);
            });
          }
        });
      },
      function(results) {
        // Get the info for each capture
        async.map(results,
          function(name, callback) {
            getInfoCapture(name, simple, pcap, callback);
          },
          function(error, captures) {
            common.readJSON('captures_data', function(ans) {
              ans.captures = captures;
              res.status(200).json(ans);
            });
          });
      });
  });
};
exports.dataCaptures = dataCaptures;

// Checks if a new name is available and valid
function validNewName(name, callback) {
  // Valid name
  if (!validName(name)) {
    callback(false);
  }
  // File already exists
  fs.exists(config.CAPTURES_DIR + name, function(exists) {
    callback(!exists);
  });
};
exports.validNewName = validNewName;

// Checks if a capture has a valid simple format
function validSimpleCapture(name, callback) {
  // TODO: Use something different (testSimple?) to determine if its a valid simple capture with the #packets at the end
  if (!validName(name)) {
    callback(false);
  }
  // 3rd and 4th byte are 0x69
  var magicNumber = new Buffer([0x69, 0x69]);
  var buff = new Buffer([0x00, 0x00]);
  fs.open(config.CAPTURES_DIR + name, 'r', function(err, fd) {
    if (err) {
      callback(false);
    }
    fs.read(fd, buff, 0, 2, 2, function(error, bytes, buffer) {
      fs.close(fd);
      if (bytes != magicNumber.length) {
        callback(false);
      } else {
        callback((magicNumber[0] == buffer[0]) && (magicNumber[1] == buffer[1]));
      }
    });
  });
};
exports.validSimpleCapture = validSimpleCapture;

// Checks if a capture has a valid pcap format
function validPcapCapture(name, callback) {
  if (!validName(name)) {
    callback(false);
  }
  validSimpleCapture(name, function(valid) {
    if (valid) {
      callback(false);
    } else {
      // Check if it is a valid pcap capture
      var validPcapCommand = 'sudo LD_LIBRARY_PATH=bin/caputils/ ./bin/caputils/capinfos -t "' + config.CAPTURES_DIR + name + '" 2> /dev/null | grep "File type"';
      scripts.exec(validPcapCommand).on('exit', function(code) {
        callback(code == 0);
      });
    }
  });
};
exports.validPcapCapture = validPcapCapture;

// Checks if a capture exists with a given name (full name)
function validCapture(name, callback) {
  validSimpleCapture(name, function(validSimple) {
    if (validSimple) {
      callback(true);
    } else {
      validPcapCapture(name, function(validPcap) {
        callback(validPcap);
      });
    }
  });
};
exports.validCapture = validCapture;

// Checks if a capture is in use
function inUse(name, callback) {
  var capturePath = path.normalize(config.CAPTURES_DIR + name);
  var command = 'sudo lsof "' + capturePath + '"';
  scripts.exec(command).on('exit', function(code) {
    callback(code == 0);
  });
};
exports.inUse = inUse;


// Internal functions

// Gets the info for a capture
function getInfoCapture(name, simple, pcap, callback) {
  fs.stat(config.CAPTURES_DIR + name, function(err, stats) {
    if(err) {
      callback('error', null);
    }

    // Name
    var dataCapture = {};
    dataCapture['name'] = name;

    // Size
    dataCapture['size'] = stats['size'];

    // Date
    dataCapture['date'] = common.mtime2string(stats['mtime']);

    // Type
    if (simple != pcap) {
      dataCapture['type'] = simple ? 'simple' : 'pcap';
      callback(null, dataCapture);
    } else {
      validSimpleCapture(name, function(valid) {
        if (valid) {
          dataCapture['type'] = 'simple';
        } else {
          dataCapture['type'] = 'pcap';
        }
        callback(null, dataCapture);
      });
    }
  });
};

// Checks if a name is valid (syntactically)
function validName(name) {
  var nameLength = name.length;
  if ((nameLength < 1) || (nameLength > 50)) {
    return false;
  }

  // Name regexp
  var regexpName = /[-a-zA-Z0-9_ \(\)]+\.{0,1}/g;
  while (name.match(regexpName)) {
    name = name.replace(regexpName, '');
  }
  return name.length == 0;
};