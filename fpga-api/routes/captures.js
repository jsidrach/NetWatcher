// Captures module

// Package dependencies
var scripts = require('child_process');
var fs = require('fs');
var path = require('path');
var config = require('../config.js');
var common = require('./_common.js');
var captures_utils = require('./captures_utils.js');


// Routes

// /captures/all
// Gets all the captures (simple/pcap format) in the CAPTURES_DIR
exports.all = function (req, res) {
  captures_utils.dataCaptures(true, true, res);
};

// /captures/simple
// Gets the simple captures in the CAPTURES_DIR 
exports.simple = function (req, res) {
  captures_utils.dataCaptures(true, false, res);
};

// /captures/pcap
// Gets the pcap captures in the CAPTURES_DIR 
exports.pcap = function (req, res) {
  captures_utils.dataCaptures(false, true, res);
};

// /captures/path
// Gets the path where the captures are stored
exports.path = function (req, res) {
  var dataPath = {};
  // Absolute path
  if (config.CAPTURES_DIR.charAt(0) == '/') {
    dataPath['path'] = path.resolve(config.CAPTURES_DIR);
  }
  // Relative path
  else {
    dataPath['path'] = path.resolve(__dirname, '..', config.CAPTURES_DIR);
  }
  res.json(dataPath);
};

// /captures/rename/:oldname/:newname
// Renames a capture in the CAPTURES_DIR
exports.rename = function (req, res) {
  // Valid params
  if ((!captures_utils.validCapture(req.params.oldname)) || (!captures_utils.validNewName(req.params.newname))) {
    common.sendJSON('captures_rename_error', res, 400);
    return;
  }

  // Check if it is being used
  captures_utils.inUse(req.params.oldName, function(flag) {
    if(flag) {
      common.sendJSON('captures_rename_error', res, 400);
      return;
    }
    // Rename
    fs.rename(config.CAPTURES_DIR + req.params.oldname, config.CAPTURES_DIR + req.params.newname, function (err) {
      if (err) {
        common.logError(err.message);
        common.sendJSON('captures_rename_error', res, 400);
        return;
      }
      common.sendJSON('captures_rename_success', res, 200);
    });
  });
};

// /captures/simple/pcap/:name/:convertedname
// Coverts a capture from simple to pcap
exports.convertToPcap = function (req, res) {
  // Valid params
  if ((!captures_utils.validSimpleCapture(req.params.name)) || (!captures_utils.validNewName(req.params.convertedname))) {
    common.sendJSON('captures_convert_error', res, 400);
    return;
  }

  // Convert the capture
  var command = 'sudo ./bin/simple2pcap -o "' + config.CAPTURES_DIR + req.params.convertedname + '" "' + config.CAPTURES_DIR + req.params.name + '"';
  scripts.exec(command).on('exit', function (code) {
    if (code != 0) {
      common.logError('Error executing the simple2pcap command. Code: ' + code);
      common.logError('Command: ' + command);
      common.sendJSON('captures_convert_error', res, 400);
      return;
    }
    common.sendJSON('captures_convert_success', res, 200);
  });
};

// /captures/pcap/simple/:name/:convertedname
// Converts a capture from pcap to simple
exports.convertToSimple = function (req, res) {
  // Valid params
  if ((!captures_utils.validPcapCapture(req.params.name)) || (!captures_utils.validNewName(req.params.convertedname))) {
    common.sendJSON('captures_convert_error', res, 400);
    return;
  }

  // Convert the capture
  var command = 'sudo LD_LIBRARY_PATH=bin/caputils/ ' +
    './bin/caputils/editcap -F hw_gen "' + config.CAPTURES_DIR + req.params.name + '" "' + config.CAPTURES_DIR + req.params.convertedname + '"';
  scripts.exec(command).on('exit', function (code) {
    if (code != 0) {
      common.logError('Error executing the editcap command. Code: ' + code);
      common.logError('Command: ' + command);
      common.sendJSON('captures_convert_error', res, 400);
      return;
    }
    common.sendJSON('captures_convert_success', res, 200);
  });
};

// /captures/delete/:name
// Deletes a capture in the CAPTURES_DIR
exports.delete = function (req, res) {
  // Valid param
  if (!captures_utils.validCapture(req.params.name)) {
    common.sendJSON('captures_delete_error', res, 400);
    return;
  }

  // Check if it is being used
  captures_utils.inUse(req.params.name, function(flag) {
    if(flag) {
      common.sendJSON('captures_rename_error', res, 400);
      return;
    }
    // Delete
    fs.unlink(config.CAPTURES_DIR + req.params.name, function (err) {
      if (err) {
        common.logError(err.message);
        common.sendJSON('captures_delete_error', res, 400);
        return;
      }
      common.sendJSON('captures_delete_success', res, 200);
    });
  });
};