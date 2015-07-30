// Manager module

// Package dependencies)
var scripts = require('child_process');
var async = require('async');
var config = require('../config.js');
var common = require('./_common.js');
var statistics_utils = require('./statistics_utils.js');
var captures_utils = require('./captures_utils.js');
var manager_utils = require('./manager_utils.js');


// Routes

// /system/reboot
// Reboots the system
exports.reboot = function(req, res) {
  // Check the FPGA is not running
  statistics_utils.runningAny(function(isRunning) {
    if (isRunning) {
      common.sendJSON('system_reboot_error', res, 412);
      return;
    }
    // Reboot the system
    scripts.exec(manager_utils.rebootCommand, function(error, stdout, stderr) {
      if (error) {
        // Internal error
        common.logError(stderr);
        res.sendStatus(500);
        return;
      }
      common.sendJSON('system_reboot_success', res, 200);
    });
  });
};
exports.reboot.displayName = common.prettyName(__filename, 'reboot');

// /player/init
// Programs the FPGA as a player and reboots the system
exports.initPlayer = function(req, res) {
  manager_utils.initFPGA(req, res, false);
};
exports.initPlayer.displayName = common.prettyName(__filename, 'initPlayer');

// /recorder/init
// Programs the FPGA as a recorder and reboots the system
exports.initRecorder = function(req, res) {
  manager_utils.initFPGA(req, res, true);
};
exports.initRecorder.displayName = common.prettyName(__filename, 'initRecorder');

// /player/install
// Installs the driver and mounts the FPGA as a player
exports.installPlayer = function(req, res) {
  manager_utils.installFPGA(req, res, false);
};
exports.installPlayer.displayName = common.prettyName(__filename, 'installPlayer');

// /recorder/install
// Installs the driver and mounts the FPGA as a recorder
exports.installRecorder = function(req, res) {
  manager_utils.installFPGA(req, res, true);
};
exports.installRecorder.displayName = common.prettyName(__filename, 'installRecorder');

// /player/start/:capturename/:mask/:ifg
// Reproduces a capture
exports.startPlayer = function(req, res) {
  manager_utils.startPlaying(req, res, false);
};
exports.startPlayer.displayName = common.prettyName(__filename, 'startPlayer');

// /player/start/loop/:capturename/:mask/:ifg
// Reproduces a capture in loop
exports.startPlayerLoop = function(req, res) {
  manager_utils.startPlaying(req, res, true);
};
exports.startPlayerLoop.displayName = common.prettyName(__filename, 'startPlayerLoop');

// /recorder/start/:capturename/:port/:bytes
// Records a capture
exports.startRecorder = function(req, res) {
  statistics_utils.modeFPGA(5, function(ans) {
    // FPGA must be programmed as a recorder
    if (ans != 'recorder') {
      // FPGA not programmed as a recorder
      common.readJSON('fpga_invalid_state', function(ans) {
        ans.description = 'Invalid State. The FPGA is not programmed and mounted as a recorder.';
        res.status(412).json(ans);
      });
      return;
    }
    statistics_utils.runningFPGA(true, function(isRunning) {
      // FPGA recorder must not be running
      if (isRunning) {
        common.readJSON('fpga_invalid_state', function(ans) {
          ans.description = 'Invalid State. The FPGA is already recording data.';
          res.status(412).json(ans);
        });
        return;
      }

      // Valid port (0,1,2,3)
      if (!/^[0123]$/.test(req.params.port)) {
        common.readJSON('recorder_start_error', function(ans) {
          ans.description = 'Invalid port.';
          res.status(400).json(ans);
        });
        return;
      }

      // Valid number of bytes
      if (!/^[1-9]+[0-9]*[gGmMkK]$/.test(req.params.bytes)) {
        common.readJSON('recorder_start_error', function(ans) {
          ans.description = 'Invalid number of bytes.';
          res.status(400).json(ans);
        });
        return;
      }

      // Valid output file
      captures_utils.validNewName(req.params.capturename, function(valid) {
        if (!valid) {
          common.readJSON('recorder_start_error', function(ans) {
            ans.description = 'Invalid capture name (must not exist).';
            res.status(400).json(ans);
          });
        } else {
          // sudo -b nohup ./bin/launchRecorder.sh PORT BYTES_TO_CAPTURE SIMPLE_FILE
          var command = 'sudo -b nohup ./bin/launchRecorder.sh ' + req.params.port + ' ' + req.params.bytes + ' "' + config.CAPTURES_DIR + req.params.capturename + '"';
          scripts.exec(command);
          common.sendJSON('recorder_start_success', res, 200);
        }
      });
    });
  });
};
exports.startRecorder.displayName = common.prettyName(__filename, 'startRecorder');

// /player/stop
// Stops the player
exports.stopPlayer = function(req, res) {
  statistics_utils.runningFPGA(false, function(isRunning) {
    // Error if the player is not running
    if (!isRunning) {
      common.readJSON('fpga_invalid_state', function(ans) {
        ans.description = 'Invalid State. The FPGA is not playing a capture.';
        res.status(412).json(ans);
      });
      return;
    }
    // Stop the player in a loop
    manager_utils.stopLoopPlayer(req, res);
  });
};
exports.stopPlayer.displayName = common.prettyName(__filename, 'stopPlayer');

// /recorder/stop
// Stops the recorder
exports.stopRecorder = function(req, res) {
  statistics_utils.runningFPGA(true, function(isRunning) {
    // Error if the recorder is not running
    if (!isRunning) {
      common.readJSON('fpga_invalid_state', function(ans) {
        ans.description = 'Invalid State. The FPGA is not recording data.';
        res.status(412).json(ans);
      });
      return;
    }
    // Get the capture name and stop the recorder
    statistics_utils.getDataRecording(function(ans) {
      if (ans == 'error') {
        res.sendStatus(500);
        return;
      }
      manager_utils.stopLoopRecorder(req, res, ans.capture);
    });
  });
};
exports.stopRecorder.displayName = common.prettyName(__filename, 'stopRecorder');

// /storage/raid
// Deletes (format and reset) the storage raid
exports.deleteRaid = function(req, res) {
  // Do not allow to reset the raid if RAID flag is not set or the FPGA is running
  if (!config.RAID) {
    common.sendJSON('raid_delete_error', res, 412);
    return;
  }
  async.series([
      function(callback) {
        statistics_utils.runningAny(function(isRunning) {
          if (isRunning) {
            callback('error');
          } else {
            callback(null);
          }
        });
      },
      // Format the RAID
      async.apply(exec, 'umount /mnt/raid'),
      async.apply(exec, 'mdadm --stop "' + config.RAID_DEV + '"'),
      async.apply(exec, 'mdadm --remove "' + config.RAID_DEV + '"'),
      // Format each disk
      function(callback) {
        // Format each disk
        async.each(config.RAID_DISKS, function(disk, innerCallback) {
            async.series([
                async.apply(exec, 'hdparm --user-master u --security-set-pass Eins "' + disk + '"'),
                async.apply(exec, 'hdparm --user-master u --security-erase Eins "' + disk + '"')
              ],
              // Inner loop error
              function(loopErr, loopResults) {
                if (loopErr) {
                  innerCallback('error');
                } else {
                  innerCallback(null);
                }
              }
            );
          },
          // Any error in disk formatting
          function(err) {
            if (err) {
              callback('error');
            } else {
              callback(null);
            }
          }
        );
      },
      // Re-create the RAID
      async.apply(exec, 'mdadm --create "' + config.RAID_DEV + '" --level=0 --raid-devices=' + config.RAID_DISKS.length + ' "' + config.RAID_DISKS.join('" "') + '"'),
      async.apply(exec, 'mkfs.xfs "' + config.RAID_DEV + '"'),
      async.apply(exec, 'mount "' + config.RAID_DEV + '" /mnt/raid')
    ],
    function(error, results) {
      if (error) {
        common.logError('Error while formatting the RAID');
        res.sendJSON('raid_delete_error', res, 400);
      } else {
        res.sendJSON('raid_delete_success', res, 200);
      }
    }
  );
};
exports.deleteRaid.displayName = common.prettyName(__filename, 'deleteRaid');
