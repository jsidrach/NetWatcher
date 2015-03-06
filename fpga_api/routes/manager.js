// Manager module

// Package dependencies
var scripts = require('child_process');
var config = require('../config.js');
var common = require('./_common.js');
var info = require('./statistics.js');

// Constant execs
var rebootCommand = 'sudo /sbin/reboot';
var playerBitStream = 'bin/nf10g_player.bit';
var recorderBitStream = 'bin/nf10g_recorder.bit';

// /system/reboot
// Reboots the system
function reboot(req, res) {
  // Reboot the system
  scripts.exec(rebootCommand, function (error, stdout, stderr) {
    if (error) {
      // Internal error
      common.logError(stderr);
      res.sendStatus(500);
      return;
    }
    common.sendJSON('system_reboot_ok', res, 200);
  });
};
exports.reboot = reboot;

// /player/init
// Programs the FPGA as a player and reboots the system
exports.initPlayer = function (req, res) {
  initFPGA(req, res, playerBitStream);
};

// /recorder/init
// Programs the FPGA as a recorder and reboots the system
exports.initRecorder = function (req, res) {
  initFPGA(req, res, recorderBitStream);
};

// /player/install
// Installs the driver and mounts the FPGA as a player
exports.installPlayer = function (req, res) {
  installFPGA(req, res, false);
}

// /recorder/install
// Installs the driver and mounts the FPGA as a recorder
exports.installRecorder = function (req, res) {
  installFPGA(req, res, true);
}

// Internal Functions

// Checks the state and programs and mounts the FPGA with a bitstream
function initFPGA(req, res, bitstream) {
  // Prequisite: HugePages on
  // 0 if huge pages is not active, 1 if hugepages is active
  var code_script = scripts.exec(info.checkHugePagesOff);
  code_script.on('exit', function (code) {
    if (code == 0) {
      // HugePages off: invalid state to initialize the FPGA
      common.readJSON('fpga_invalid_state', function (ans) {
        ans.description = 'Invalid State. HugePages needs to be active to initialize the FPGA.';
        res.status(412).json(ans);
      });
      return;
    }
    // HugePages on: initialize the FPGA
    initBitstream(req, res, bitstream);
  });
};

// Programs and mounts the FPGA with a bitstream
function initBitstream(req, res, bitstream) {
  // Program the FPGA
  scripts.exec('./bin/impact.sh ' + bitstream).on('exit', function (code) {
    // Check if the FPGA Is programmed
    if (code != 0) {
      // Internal error
      common.logError('Error initializing the FPGA');
      res.sendStatus(500);
      return;
    }
    // Reboot the system
    scripts.exec(rebootCommand, function (error, stdout, stderr) {
      if (error) {
        // Internal error
        common.logError(stderr);
        res.sendStatus(500);
        return;
      }
      // Programmed ok
      common.sendJSON('fpga_init_ok', res, 200);
    });
  });
};

// Mounts the FPGA
function installFPGA(req, res, recorder) {
  // Check if the FPGA is programmed
  var code_script = scripts.exec(info.checkInitFPGAOn);
  code_script.on('exit', function (code) {
    if (code != 0) {
      // FPGA not programmed: invalid state to mount the FPGA
      common.readJSON('fpga_invalid_state', function (ans) {
        ans.description = 'Invalid State. The FPGA must be programmed before mounted.';
        res.status(412).json(ans);
      });
      return;
    }
    // Mount the FPGA
    scripts.exec('sudo ./bin/installDriver.sh').on('exit', function (code) {
      if (code != 0) {
        // Internal Error
        common.logError('Error mounting the FPGA');
        res.sendStatus(500);
        return;
      }
      if(recorder) {
        scripts.exec('sudo ./bin/configureAel').on('exit', function (code) {
          if (code != 0) {
            // Internal Error
            common.logError('Error configuring the FPGA');
            res.sendStatus(500);
            return;
          }
          // Mounted and configured ok
          common.sendJSON('fpga_mount_ok', res, 200);
        });
      } else {
        // Mounted ok
        common.sendJSON('fpga_mount_ok', res, 200);
      }
    });
  });
};