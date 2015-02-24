// Captures module

// Package dependencies
var scripts = require('child_process');
var fs = require('fs');
var path = require('path');
var config = require('../config.js');
var common = require('./_common.js');

// /captures/all
// Gets all the captures (simple/pcap format) in the CAPTURES_DIR
exports.all = function (req, res) {
  dataCaptures(true, true, res, sendDataCaptures);
};

// /captures/simple
// Gets the simple captures in the CAPTURES_DIR 
exports.simple = function (req, res) {
  dataCaptures(true, false, res, sendDataCaptures);
};

// /captures/pcap
// Gets the pcap captures in the CAPTURES_DIR 
exports.pcap = function (req, res) {
  dataCaptures(false, true, res, sendDataCaptures);
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
  if ((!common.validCapture(req.params.oldname)) || (!common.validNewName(req.params.newname))) {
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
};

// /captures/simple/pcap/:name/:convertedname
// Coverts a capture from simple to pcap
exports.convertToPcap = function (req, res) {
  // Valid params
  if ((!common.validSimpleCapture(req.params.name)) || (!common.validNewName(req.params.convertedname))) {
    common.sendJSON('captures_convert_error', res, 400);
    return;
  }

  // Convert the capture
  var code_script = scripts.exec('./bin/simple2pcap -o ' + config.CAPTURES_DIR + req.params.convertedname + ' ' + config.CAPTURES_DIR + req.params.name);
  code_script.on('exit', function (code) {
    if (code != 0) {
      common.logError('Error executing the simple2pcap command. Code: ' + code);
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
  if ((!common.validPcapCapture(req.params.name)) || (!common.validNewName(req.params.convertedname))) {
    common.sendJSON('captures_convert_error', res, 400);
    return;
  }

  // Convert the capture
  var code_script = scripts.exec('./bin/pcap2simple ' + config.CAPTURES_DIR + req.params.name + ' ' + config.CAPTURES_DIR + req.params.convertedname);
  code_script.on('exit', function (code) {
    if (code != 0) {
      common.logError('Error executing the pcap2simple command. Code: ' + code);
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
  if (!common.validCapture(req.params.name)) {
    common.sendJSON('captures_delete_error', res, 400);
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
};


// Internal functions

// Gets an array of captures info from the CAPTURES_DIR 
function dataCaptures(simple, pcap, res, callback) {
  // Validator callback
  var validator;
  if (simple && pcap) {
    validator = common.validCapture;
  } else if (simple) {
    validator = common.validSimpleCapture;
  } else {
    validator = common.validPcapCapture;
  }

  // Gets all the files in the CAPTURES_DIR 
  fs.readdir(config.CAPTURES_DIR, function (err, files) {
    if (err) {
      common.logError(err);
      res.sendStatus(500);
      return;
    }
    var dataCaptures = [];
    // For each file
    files.forEach(function (entry) {
      fs.stat(config.CAPTURES_DIR + entry, function (err, stats) {
        if (err) {
          common.logError(err);
          return;
        }

        // Valid capture
        if (stats.isFile() && validator(entry)) {
          // Name
          var dataCapture = {};
          dataCapture['name'] = entry;

          // Type
          if (simple != pcap) {
            dataCapture['type'] = simple ? 'simple' : 'pcap';
          } else if (common.validSimpleCapture(entry)) {
            dataCapture['type'] = 'simple';
          } else {
            dataCapture['type'] = 'pcap';
          }

          // Size
          dataCapture['size'] = stats['size'];

          // Date
          dataCapture['date'] = stats['mtime'].toISOString().replace('T', ' ').substr(0, 19);

          // Push the object into the array
          dataCaptures.push(dataCapture);
        }
      });
    });
    callback(res, dataCaptures);
  });
};

// Sends the capture data into a json object
function sendDataCaptures(res, dataCaptures) {
  common.readJSON('captures_data', function (ans) {
    ans.captures = dataCaptures;
    res.status(200).json(ans);
  });
};