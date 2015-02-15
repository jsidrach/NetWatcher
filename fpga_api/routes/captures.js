// Captures module

// Package dependencies
var scripts = require('child_process');
var fs = require('fs');
var path = require('path');
var common = require('./_common.js');

// /captures/all
// Gets all the captures (simple/pcap format) in the ./data/ dir
exports.all = function (req, res) {
  dataCaptures(true, true, res, sendDataCaptures);
};

// /captures/simple
// Gets the simple captures in the ./data/ dir
exports.simple = function (req, res) {
  dataCaptures(true, false, res, sendDataCaptures);
};

// /captures/pcap
// Gets the pcap captures in the ./data/ dir
exports.pcap = function (req, res) {
  dataCaptures(false, true, res, sendDataCaptures);
};

// /captures/path
// Gets the path where the captures are stored
exports.path = function (req, res) {
  var dataPath = {};
  dataPath['path'] = path.resolve(__dirname, '..', 'data/');
  res.json(dataPath);
};


// /captures/rename/:oldname/:newname
// Renames a capture in the ./data/ dir
exports.rename = function (req, res) {
  // Valid params
  if ((!common.validCapture(req.params.oldname)) || (!common.validNewName(req.params.newname))) {
    common.sendJSON('captures_rename_error', res);
    return;
  }

  // Rename
  fs.rename('data/' + req.params.oldname, 'data/' + req.params.newname, function (err) {
    if (err) {
      console.error(err.message);
      common.sendJSON('captures_rename_error', res);
      return;
    }
    common.sendJSON('captures_rename_success', res);
  });
};

// /captures/simple/pcap/:name/:convertedname
// Coverts a capture from simple to pcap
exports.convertToPcap = function (req, res) {
  // Valid params
  if ((!common.validSimpleCapture(req.params.name)) || (!common.validNewName(req.params.convertedname))) {
    common.sendJSON('captures_convert_error', res);
    return;
  }

  // Convert the capture
  var code_script = scripts.exec('./bin/simple2pcap data/' + req.params.name + ' data/' + req.params.convertedname, function (error, stdout, stderr) {
    if (error) {
      console.error('Error executing the simple2pcap command');
      console.error(stdout);
      console.error(stderr);
      common.sendJSON('captures_convert_error', res);
      return;
    }
  });
  code_script.on('exit', function (code) {
    if (code != 0) {
      console.error('Error executing the simple2pcap command');
      console.error('Code: ' + code);
      common.sendJSON('captures_convert_error', res);
      return;
    }
    common.sendJSON('captures_convert_success', res);
  });
}

// /captures/pcap/simple/:name/:convertedname
// Converts a capture from pcap to simple
exports.convertToSimple = function (req, res) {
  // Valid params
  if ((!common.validPcapCapture(req.params.name)) || (!common.validNewName(req.params.convertedname))) {
    common.sendJSON('captures_convert_error', res);
    return;
  }

  // Convert the capture
  var code_script = scripts.exec('./bin/pcap2simple data/' + req.params.name + ' data/' + req.params.convertedname, function (error, stdout, stderr) {
    if (error) {
      console.error('Error executing the pcap2simple command');
      console.error(stdout);
      console.error(stderr);
      common.sendJSON('captures_convert_error', res);
      return;
    }
  });
  code_script.on('exit', function (code) {
    if (code != 0) {
      console.error('Error executing the pcap2simple command');
      console.error('Code: ' + code);
      common.sendJSON('captures_convert_error', res);
      return;
    }
    common.sendJSON('captures_convert_success', res);
  });
}


// /captures/delete/:name
// Deletes a capture in the ./data/ dir
exports.delete = function (req, res) {
  // Valid param
  if (!common.validCapture(req.params.name)) {
    common.sendJSON('captures_delete_error', res);
    return;
  }

  // Delete
  fs.unlink('data/' + req.params.name, function (err) {
    if (err) {
      console.error(err.message);
      common.sendJSON('captures_delete_error', res);
      return;
    }
    common.sendJSON('captures_delete_success', res);
  });
};

// Internal functions

// Gets an array of captures info from the ./data/ dir
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

  // Gets all the files in the ./data/ dir
  fs.readdir('data/', function (err, files) {
    var dataCaptures = [];
    // For each file
    files.forEach(function (entry) {
      fs.stat('data/' + entry, function (err, stats) {
        if (err) {
          console.error(err);
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
    res.json(ans);
  });
};