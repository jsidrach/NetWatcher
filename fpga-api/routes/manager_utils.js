// Manager module auxiliary and internal functions

// Package dependencies
var scripts = require('child_process');
var fs = require('fs');
var config = require('../config.js');
var common = require('./_common.js');
var captures_utils = require('./captures_utils.js');
var statistics_utils = require('./statistics_utils.js');

// Constants
var playerBitStream = 'bin/nf10g_player.bit';
var recorderBitStream = 'bin/nf10g_recorder.bit';


// Module exports

// Commands
var rebootCommand = 'sudo /sbin/reboot';
exports.rebootCommand = rebootCommand;

// Checks the state and programs and mounts the FPGA as a player or recorder
function initFPGA(req, res, recorder) {
  var bitstream = recorder ? recorderBitStream : playerBitStream;
  // Prequisite: HugePages on
  // 0 if huge pages is not active, 1 if hugepages is active
  scripts.exec(statistics_utils.checkHugePagesOff).on('exit', function (code) {
    if (code == 0) {
      // HugePages off: invalid state to initialize the FPGA
      common.readJSON('fpga_invalid_state', function (ans) {
        ans.description = 'Invalid State. HugePages needs to be active to initialize the FPGA.';
        res.status(412).json(ans);
      });
      return;
    }
    statistics_utils.runningAny(function(code) {
      if(code) {
        // FPGA is running
        common.readJSON('fpga_invalid_state', function (ans) {
          ans.description = 'Invalid State. The FPGA is already running, stop it to init the FPGA.';
          res.status(412).json(ans);
        });
        return;
      }
      // Initialize the FPGA
      initBitstream(req, res, bitstream);
    });
  });
};
exports.initFPGA = initFPGA;

// Mounts the FPGA
function installFPGA(req, res, recorder) {
  // Check if the FPGA is programmed
  scripts.exec(statistics_utils.checkInitFPGAOn).on('exit', function (code) {
    if (code != 0) {
      // FPGA not programmed: invalid state to mount the FPGA
      common.readJSON('fpga_invalid_state', function (ans) {
        ans.description = 'Invalid State. The FPGA must be programmed before mounted.';
        res.status(412).json(ans);
      });
      return;
    }
    statistics_utils.runningAny(function(code) {
      if(code) {
        // FPGA is running
        common.readJSON('fpga_invalid_state', function (ans) {
          ans.description = 'Invalid State. The FPGA is already running, stop it to install the FPGA.';
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
        if (recorder) {
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
  });
};
exports.installFPGA = installFPGA;

// Starts the player
function startPlaying(req, res, loop) {
  statistics_utils.modeFPGA(5, function (ans) {
    // FPGA must be programmed as a recorder
    if (ans != 'player') {
      // FPGA not programmed as a player
      common.readJSON('fpga_invalid_state', function (ans) {
        ans.description = 'Invalid State. The FPGA is not programmed and mounted as a player.';
        res.status(412).json(ans);
      });
      return;
    }
    statistics_utils.runningFPGA(false, function (isRunning) {
      // FPGA player must not be running
      if (isRunning) {
        common.readJSON('fpga_invalid_state', function (ans) {
          ans.description = 'Invalid State. The FPGA is already playing a capture.';
          res.status(412).json(ans);
        });
        return;
      }

      // Valid simple capture
      if (!captures_utils.validSimpleCapture(req.params.capturename)) {
        common.readJSON('player_start_error', function (ans) {
          ans.description = 'Invalid capture (must exist and be in simple format).';
          res.status(400).json(ans);
        });
        return;
      }

      // Valid mask (0,1,2,3)
      if (!/^[0123]$/.test(req.params.mask)) {
        common.readJSON('player_start_error', function (ans) {
          ans.description = 'Invalid mask.';
          res.status(400).json(ans);
        });
        return;
      }

      // Valid interframe gap
      if (!/^[0-9]+$/.test(req.params.ifg)) {
        common.readJSON('player_start_error', function (ans) {
          ans.description = 'Invalid interframe gap.';
          res.status(400).json(ans);
        });
        return;
      }

      // sudo -b nohup ./bin/launchPlayer.sh MASK IFG LOOP SIMPLE_FILE
      var command = 'sudo -b nohup ./bin/launchPlayer.sh ' + req.params.mask + ' ' + req.params.ifg + ' ' + (loop? '1' : '0') + ' "' + config.CAPTURES_DIR + req.params.capturename + '"';
      scripts.exec(command);
      common.sendJSON('player_start_success', res, 200);
    });
  });
};
exports.startPlaying = startPlaying;

// Stops the player (in loop)
function stopLoopPlayer(req, res) {
  scripts.exec('sudo pkill -SIGINT launchPlayer; sudo pkill -SIGINT host2card').on('exit', function (code) {
    statistics_utils.runningFPGA(false, function (isRunning) {
      // Recursion
      if (isRunning) {
        setTimeout(function () {
          stopLoopPlayer(req, res);
        }, 500);
      } else {
        common.sendJSON('player_stop_success', res, 200);
      }
    });
  });
};
exports.stopLoopPlayer = stopLoopPlayer;

// Stops the recorder (in loop)
function stopLoopRecorder(req, res, capturename) {
  scripts.exec('sudo pkill -SIGINT launchRecorder; sudo pkill -SIGINT card2host').on('exit', function (code) {
    statistics_utils.runningFPGA(true, function (isRunning) {
      // Recursion
      if (isRunning) {
        setTimeout(function () {
          stopLoopRecorder(req, res, capturename);
        }, 500);
      } else {
        // Delete the capture
        fs.unlink(config.CAPTURES_DIR + capturename);
        common.sendJSON('recorder_stop_success', res, 200);
      }
    });
  });
};
exports.stopLoopRecorder = stopLoopRecorder;



// Internal functions

// Programs and mounts the FPGA with a bitstream
function initBitstream(req, res, bitstream) {
  // Program the FPGA
  scripts.exec('sudo ./bin/impact.sh ' + bitstream).on('exit', function (code) {
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