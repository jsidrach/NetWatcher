// Manager module

// Package dependencies
var scripts = require('child_process');
var config = require('../config.js');
var common = require('./_common.js');
var statistics_utils = require('./statistics_utils.js');
var captures_utils = require('./captures_utils.js');
var manager_utils = require('./manager_utils.js');


// Routes

// /system/reboot
// Reboots the system
exports.reboot = function (req, res) {
  // Reboot the system
  scripts.exec(manager_utils.rebootCommand, function (error, stdout, stderr) {
    if (error) {
      // Internal error
      common.logError(stderr);
      res.sendStatus(500);
      return;
    }
    common.sendJSON('system_reboot_success', res, 200);
  });
};

// /player/init
// Programs the FPGA as a player and reboots the system
exports.initPlayer = function (req, res) {
  manager_utils.initFPGA(req, res, false);
};

// /recorder/init
// Programs the FPGA as a recorder and reboots the system
exports.initRecorder = function (req, res) {
  manager_utils.initFPGA(req, res, true);
};

// /player/install
// Installs the driver and mounts the FPGA as a player
exports.installPlayer = function (req, res) {
  manager_utils.installFPGA(req, res, false);
};

// /recorder/install
// Installs the driver and mounts the FPGA as a recorder
exports.installRecorder = function (req, res) {
  manager_utils.installFPGA(req, res, true);
};

// /player/start/:capturename/:mask/:other
// Reproduces a capture
exports.startPlayer = function (req, res) {
  // TODO: Choose parameters
  // TODO: Implement function
  res.sendStatus(404);
};

// /recorder/start/:capturename/:port/:bytes
// Records a capture
exports.startRecorder = function (req, res) {
  statistics_utils.modeFPGA(function (ans) {
    // FPGA must be programmed as a recorder
    if (ans != 'recorder') {
      // FPGA programmed as a recorder
      common.readJSON('fpga_invalid_state', function (ans) {
        ans.description = 'Invalid State. The FPGA is not programmed and mounted as a recorder.';
        res.status(412).json(ans);
      });
      return;
    }
    statistics_utils.runningFPGA(true, function (isRunning) {
      // FPGA recorder must not be running
      if (isRunning) {
        common.readJSON('fpga_invalid_state', function (ans) {
          ans.description = 'Invalid State. The FPGA is already recording data.';
          res.status(412).json(ans);
        });
        return;
      }

      // Valid output file
      if (!captures_utils.validNewName(req.params.capturename)) {
        common.readJSON('recorder_start_error', function (ans) {
          ans.description = 'Invalid capture name (must not exist).';
          res.status(400).json(ans);
        });
        return;
      }

      // Valid port (0,1,2,3)
      if (!/^[0123]$/.test(req.params.port)) {
        common.readJSON('recorder_start_error', function (ans) {
          ans.description = 'Invalid port.';
          res.status(400).json(ans);
        });
        return;
      }

      // Valid number of bytes
      if (!/^[1-9]+[0-9]*[gGmMkK]$/.test(req.params.bytes)) {
        common.readJSON('recorder_start_error', function (ans) {
          ans.description = 'Invalid number of bytes.';
          res.status(400).json(ans);
        });
        return;
      }

      // sudo -b nohup ./bin/launchRecorder.sh PORT BYTES_TO_CAPTURE SIMPLE_FILE
      var command = 'sudo -b nohup ./bin/launchRecorder.sh ' + req.params.port + ' ' + req.params.bytes + ' "' + config.CAPTURES_DIR + req.params.capturename + '"';
      scripts.exec(command);
      common.sendJSON('recorder_start_success', res, 200);
    });
  });
};

// /player/stop
// Stops the player
exports.stopPlayer = function (req, res) {
  // TODO: Implement function
  res.sendStatus(404);
};

// /recorder/stop
// Stops the recorder
exports.stopRecorder = function (req, res) {
  statistics_utils.runningFPGA(true, function (isRunning) {
    if (!isRunning) {
      common.readJSON('fpga_invalid_state', function (ans) {
        ans.description = 'Invalid State. The FPGA is not recording data.';
        res.status(412).json(ans);
      });
      return;
    }
    manager_utils.stopLoopRecorder(req, res);
  });
};