//
// Manager
//

// post '/system/reboot'
/**
 * @api {post} /system/reboot /system/reboot
 * @apiDescription Reboot the FPGA Web Service server
 * @apiName SystemReboot
 * @apiGroup Manager
 *
 * @apiHeader {Number} timestamp Milliseconds elapsed since 1 January 1970 00:00:00 UTC until now (output of Date.now())
 *
 * @apiError (Error 412) {String} code Return code ('error')
 * @apiError (Error 412) {String} type Return type ('notification')
 * @apiError (Error 412) {String} description Description of the error
 *
 * @apiErrorExample {json} Example data on error 412
 * {
 *  "code": "error",
 *  "type": "notification",
 *  "description": "The host can not be rebooted. The FPGA is being used."
 * }
 *
 * @apiSuccess {String} code Return code ('success')
 * @apiSuccess {String} type Return type ('notification')
 * @apiSuccess {String} description Description of the success code
 *
 * @apiSuccessExample {json} Example data on success
 * {
 *  "code": "success",
 *  "type": "notification",
 *  "description": "The host is rebooting now."
 * }
 */

// post '/player/init'
/**
 * @api {post} /player/init /player/init
 * @apiDescription Programs the FPGA as a player and reboots the system
 * @apiName PlayerInit
 * @apiGroup Manager
 *
 * @apiHeader {Number} timestamp Milliseconds elapsed since 1 January 1970 00:00:00 UTC until now (output of Date.now())
 *
 * @apiError (Error 412) {String} code Return code ('error')
 * @apiError (Error 412) {String} type Return type ('fpga_invalid_state')
 * @apiError (Error 412) {String} description Description of the error
 *
 * @apiErrorExample {json} Example data on error 412
 * {
 *  "code": "error",
 *  "type": "fpga_invalid_state",
 *  "description": "Invalid State. The FPGA is already running, stop it to init the FPGA."
 * }
 *
 * @apiSuccess {String} code Return code ('success')
 * @apiSuccess {String} type Return type ('notification')
 * @apiSuccess {String} description Description of the success code
 *
 * @apiSuccessExample {json} Example data on success
 * {
 *  "code": "success",
 *  "type": "notification",
 *  "description": "The FPGA has been initialized. The host will reboot now."
 * }
 */

// post '/recorder/init'
/**
 * @api {post} /recorder/init /recorder/init
 * @apiDescription Programs the FPGA as a recorder and reboots the system
 * @apiName RecorderInit
 * @apiGroup Manager
 *
 * @apiHeader {Number} timestamp Milliseconds elapsed since 1 January 1970 00:00:00 UTC until now (output of Date.now())
 *
 * @apiError (Error 412) {String} code Return code ('error')
 * @apiError (Error 412) {String} type Return type ('fpga_invalid_state')
 * @apiError (Error 412) {String} description Description of the error
 *
 * @apiErrorExample {json} Example data on error 412
 * {
 *  "code": "error",
 *  "type": "fpga_invalid_state",
 *  "description": "Invalid State. The FPGA is already running, stop it to init the FPGA."
 * }
 *
 * @apiSuccess {String} code Return code ('success')
 * @apiSuccess {String} type Return type ('notification')
 * @apiSuccess {String} description Description of the success code
 *
 * @apiSuccessExample {json} Example data on success
 * {
 *  "code": "success",
 *  "type": "notification",
 *  "description": "The FPGA has been initialized. The host will reboot now."
 * }
 */

// post '/player/install'
/**
 * @api {post} /player/install /player/install
 * @apiDescription Installs the driver and mounts the FPGA as a player
 * @apiName PlayerInstall
 * @apiGroup Manager
 *
 * @apiHeader {Number} timestamp Milliseconds elapsed since 1 January 1970 00:00:00 UTC until now (output of Date.now())
 *
 * @apiError (Error 412) {String} code Return code ('error')
 * @apiError (Error 412) {String} type Return type ('fpga_invalid_state')
 * @apiError (Error 412) {String} description Description of the error
 *
 * @apiErrorExample {json} Example data on error 412
 * {
 *  "code": "error",
 *  "type": "fpga_invalid_state",
 *  "description": "Invalid State. The FPGA must be programmed before mounted."
 * }
 *
 * @apiSuccess {String} code Return code ('success')
 * @apiSuccess {String} type Return type ('notification')
 * @apiSuccess {String} description Description of the success code
 *
 * @apiSuccessExample {json} Example data on success
 * {
 *  "code": "success",
 *  "type": "notification",
 *  "description": "The FPGA has been mounted and is ready to be used."
 * }
 */

// post '/recorder/install'
/**
 * @api {post} /recorder/install /recorder/install
 * @apiDescription Installs the driver and mounts the FPGA as a recorder
 * @apiName RecorderInstall
 * @apiGroup Manager
 *
 * @apiHeader {Number} timestamp Milliseconds elapsed since 1 January 1970 00:00:00 UTC until now (output of Date.now())
 *
 * @apiError (Error 412) {String} code Return code ('error')
 * @apiError (Error 412) {String} type Return type ('fpga_invalid_state')
 * @apiError (Error 412) {String} description Description of the error
 *
 * @apiErrorExample {json} Example data on error 412
 * {
 *  "code": "error",
 *  "type": "fpga_invalid_state",
 *  "description": "Invalid State. The FPGA must be programmed before mounted."
 * }
 *
 * @apiSuccess {String} code Return code ('success')
 * @apiSuccess {String} type Return type ('notification')
 * @apiSuccess {String} description Description of the success code
 *
 * @apiSuccessExample {json} Example data on success
 * {
 *  "code": "success",
 *  "type": "notification",
 *  "description": "The FPGA has been mounted and is ready to be used."
 * }
 */

// post '/player/start/:capturename/:mask/:ifg'
/**
 * @api {post} /player/start/:capturename/:mask/:ifg /player/start/
 * @apiDescription Reproduces a capture
 * @apiName PlayerStart
 * @apiGroup Manager
 *
 * @apiHeader {Number} timestamp Milliseconds elapsed since 1 January 1970 00:00:00 UTC until now (output of Date.now())
 *
 * @apiParam {String} capturename Name of the capture to be reproduced
 * @apiParam {Number} mask Mask (set of ports) where the capture is going to be reproduced (0-1-2-3)
 * @apiParam {Number} ifg Interframe gap (0 to original captured rate)
 *
 * @apiError (Error 412) {String} code Return code ('error')
 * @apiError (Error 412) {String} type Return type ('fpga_invalid_state')
 * @apiError (Error 412) {String} description Description of the error
 *
 * @apiErrorExample {json} Example data on error 412
 * {
 *  "code": "error",
 *  "type": "fpga_invalid_state",
 *  "description": "Invalid State. The FPGA is not programmed and mounted as a player."
 * }
 *
 * @apiError (Error 400) {String} code Return code ('error')
 * @apiError (Error 400) {String} type Return type ('notification')
 * @apiError (Error 400) {String} description Description of the error
 *
 * @apiErrorExample {json} Example data on error 400
 * {
 *  "code": "error",
 *  "type": "notification",
 *  "description": "Invalid parameters. The FPGA could not start playing a capture."
 * }
 *
 * @apiSuccess {String} code Return code ('success')
 * @apiSuccess {String} type Return type ('notification')
 * @apiSuccess {String} description Description of the success code
 *
 * @apiSuccessExample {json} Example data on success
 * {
 *  "code": "success",
 *  "type": "notification",
 *  "description": "The FPGA has started playing a capture."
 * }
 */

// post '/player/start/loop/:capturename/:mask/:ifg'
/**
 * @api {post} /player/start/loop/:capturename/:mask/:ifg /player/start/loop/
 * @apiDescription Reproduces a capture in loop
 * @apiName PlayerStartLoop
 * @apiGroup Manager
 *
 * @apiHeader {Number} timestamp Milliseconds elapsed since 1 January 1970 00:00:00 UTC until now (output of Date.now())
 *
 * @apiParam {String} capturename Name of the capture to be reproduced
 * @apiParam {Number} mask Mask (set of ports) where the capture is going to be reproduced (0-1-2-3)
 * @apiParam {Number} ifg Interframe gap (0 to original captured rate)
 *
 * @apiError (Error 412) {String} code Return code ('error')
 * @apiError (Error 412) {String} type Return type ('fpga_invalid_state')
 * @apiError (Error 412) {String} description Description of the error
 *
 * @apiErrorExample {json} Example data on error 412
 * {
 *  "code": "error",
 *  "type": "fpga_invalid_state",
 *  "description": "Invalid State. The FPGA is not programmed and mounted as a player."
 * }
 *
 * @apiError (Error 400) {String} code Return code ('error')
 * @apiError (Error 400) {String} type Return type ('notification')
 * @apiError (Error 400) {String} description Description of the error
 *
 * @apiErrorExample {json} Example data on error 400
 * {
 *  "code": "error",
 *  "type": "notification",
 *  "description": "Invalid parameters. The FPGA could not start playing a capture."
 * }
 *
 * @apiSuccess {String} code Return code ('success')
 * @apiSuccess {String} type Return type ('notification')
 * @apiSuccess {String} description Description of the success code
 *
 * @apiSuccessExample {json} Example data on success
 * {
 *  "code": "success",
 *  "type": "notification",
 *  "description": "The FPGA has started playing a capture."
 * }
 */

// post '/recorder/start/:capturename/:port/:bytes'
/**
 * @api {post} /recorder/start/:capturename/:port/:bytes /recorder/start/
 * @apiDescription Records a capture
 * @apiName RecorderStart
 * @apiGroup Manager
 *
 * @apiHeader {Number} timestamp Milliseconds elapsed since 1 January 1970 00:00:00 UTC until now (output of Date.now())
 *
 * @apiParam {String} capturename Name the recorded capture will have
 * @apiParam {Number} port Port the FPGA will be capturing from (0-1-2-3)
 * @apiParam {Number} bytes Bytes the FPGA will capture
 *
 * @apiError (Error 412) {String} code Return code ('error')
 * @apiError (Error 412) {String} type Return type ('fpga_invalid_state')
 * @apiError (Error 412) {String} description Description of the error
 *
 * @apiErrorExample {json} Example data on error 412
 * {
 *  "code": "error",
 *  "type": "fpga_invalid_state",
 *  "description": "Invalid State. The FPGA is not programmed and mounted as a recorder."
 * }
 *
 * @apiError (Error 400) {String} code Return code ('error')
 * @apiError (Error 400) {String} type Return type ('notification')
 * @apiError (Error 400) {String} description Description of the error
 *
 * @apiErrorExample {json} Example data on error 400
 * {
 *  "code": "error",
 *  "type": "notification",
 *  "description": "Invalid capture name (must not exist)."
 * }
 *
 * @apiSuccess {String} code Return code ('success')
 * @apiSuccess {String} type Return type ('notification')
 * @apiSuccess {String} description Description of the success code
 *
 * @apiSuccessExample {json} Example data on success
 * {
 *  "code": "success",
 *  "type": "notification",
 *  "description": "The FPGA has started recording data."
 * }
 */

// post '/player/stop'
/**
 * @api {post} /player/stop /player/stop
 * @apiDescription Stops the player
 * @apiName PlayerStop
 * @apiGroup Manager
 *
 * @apiHeader {Number} timestamp Milliseconds elapsed since 1 January 1970 00:00:00 UTC until now (output of Date.now())
 *
 * @apiError (Error 412) {String} code Return code ('error')
 * @apiError (Error 412) {String} type Return type ('fpga_invalid_state')
 * @apiError (Error 412) {String} description Description of the error
 *
 * @apiErrorExample {json} Example data on error 412
 * {
 *  "code": "error",
 *  "type": "fpga_invalid_state",
 *  "description": "Invalid State. The FPGA is not playing a capture."
 * }
 *
 * @apiSuccess {String} code Return code ('success')
 * @apiSuccess {String} type Return type ('notification')
 * @apiSuccess {String} description Description of the success code
 *
 * @apiSuccessExample {json} Example data on success
 * {
 *  "code": "success",
 *  "type": "notification",
 *  "description": "The FPGA has stopped playing a capture."
 * }
 */

// post '/recorder/stop'
/**
 * @api {post} /recorder/stop /recorder/stop
 * @apiDescription Stops the recorder
 * @apiName RecorderStop
 * @apiGroup Manager
 *
 * @apiHeader {Number} timestamp Milliseconds elapsed since 1 January 1970 00:00:00 UTC until now (output of Date.now())
 *
 * @apiError (Error 412) {String} code Return code ('error')
 * @apiError (Error 412) {String} type Return type ('fpga_invalid_state')
 * @apiError (Error 412) {String} description Description of the error
 *
 * @apiErrorExample {json} Example data on error 412
 * {
 *  "code": "error",
 *  "type": "fpga_invalid_state",
 *  "description": "Invalid State. The FPGA is not recording data."
 * }
 *
 * @apiSuccess {String} code Return code ('success')
 * @apiSuccess {String} type Return type ('notification')
 * @apiSuccess {String} description Description of the success code
 *
 * @apiSuccessExample {json} Example data on success
 * {
 *  "code": "success",
 *  "type": "notification",
 *  "description": "The FPGA has stopped recording data."
 * }
 */

// delete '/storage/raid'
/**
 * @api {delete} /storage/raid /storage/raid
 * @apiDescription Deletes (format and reset) the storage raid
 * @apiName StorageRaid
 * @apiGroup Manager
 *
 * @apiHeader {Number} timestamp Milliseconds elapsed since 1 January 1970 00:00:00 UTC until now (output of Date.now())
 *
 * @apiError (Error 412) {String} code Return code ('error')
 * @apiError (Error 412) {String} type Return type ('notification')
 * @apiError (Error 412) {String} description Description of the error
 *
 * @apiErrorExample {json} Example data on error 412
 * {
 *  "code": "error",
 *  "type": "notification",
 *  "description": "The RAID could not be formatted, RAID configuration option is not set or the FPGA is running."
 * }
 *
 * @apiSuccess {String} code Return code ('success')
 * @apiSuccess {String} type Return type ('notification')
 * @apiSuccess {String} description Description of the success code
 *
 * @apiSuccessExample {json} Example data on success
 * {
 *  "code": "success",
 *  "type": "notification",
 *  "description": "The RAID has been formatted and mounted properly."
 * }
 */



//
// Statistics
//

// get '/info/ping'
/**
 * @api {get} /info/ping /info/ping
 * @apiDescription Simple request to test if the server is up
 * @apiName InfoPing
 * @apiGroup Statistics
 *
 * @apiSuccess {String} code Return code ('success')
 *
 * @apiSuccessExample {json} Example data on success
 * {
 *  "code": "success"
 * }
 */

// get '/info/delay'
/**
 * @api {get} /info/delay /info/delay
 * @apiDescription Request the seconds of delay between the client and the server (of timestamps)
 * @apiName InfoDelay
 * @apiGroup Statistics
 *
 * @apiHeader {Number} timestamp Milliseconds elapsed since 1 January 1970 00:00:00 UTC until now (output of Date.now())
 *
 * @apiError (Error 400) TimestampNotFound Timestamp is not set in the request's header
 *
 * @apiSuccess {String} code Return code ('success')
 * @apiSuccess {String} type Return type ('data')
 * @apiSuccess {Number} delay Delay in seconds between the caller and the FPGA Web Service
 * @apiSuccess {Number} maxDelay Maximum delay allowed in seconds between the caller and the FPGA Web Service
 *
 * @apiSuccessExample {json} Example data on success
 * {
 *  "code": "success",
 *  "type": "data",
 *  "delay": 1,
 *  "maxDelay": 30
 * }
 */

// get '/info/status'
// TODO

// get '/storage/stats'
/**
 * @api {get} /storage/stats /storage/stats
 * @apiDescription Request statistics of the storage (disk space, write speed, RAID)
 * @apiName StorageStats
 * @apiGroup Statistics
 *
 * @apiSuccess {String} code Return code ('success')
 * @apiSuccess {String} type Return type ('data')
 * @apiSuccess {Number} total_space Total space in the device that stores the captures (in bytes)
 * @apiSuccess {Number} used_space Used space in the device that stores the captures (in bytes)
 * @apiSuccess {Object} raid_stats RAID statistics
 * @apiSuccess {Boolean} raid_stats.raid_active Flag to see if the RAID is being used for store captures. The rest of the fields below are only set if <code>raid_active</code> is <code>true</code>
 * @apiSuccess {String} raid_stats.raid_name Name of the RAID
 * @apiSuccess {Number} raid_stats.write_speed Write speed of the RAID (in bytes/second)
 * @apiSuccess {Object} raid_stats.disks Array of statistics for each disk of the RAID
 * @apiSuccess {String} raid_stats.disks.name Disk name
 * @apiSuccess {Number} raid_stats.disks.write_speed Write speed of the disk (in bytes/second)
 *
 * @apiSuccessExample {json} Example data on success
 * {
 *   "code": "success",
 *   "type": "data",
 *   "total_space": 240972104,
 *   "used_space": 70828412,
 *   "raid_stats": {
 *     "raid_active": true,
 *     "raid_name": "/dev/md0",
 *     "write_speed": 4051114978890,
 *     "disks": [
 *       {
 *         "name": "/dev/sdc",
 *         "write_speed": 15435231341
 *       },
 *       {
 *         "name": "/dev/sdd",
 *         "write_speed": 32112351239
 *       },
 *       {
 *         "name": "/dev/sde",
 *         "write_speed": 19123843109
 *       }
 *     ]
 *   }
 * }
 */



//
// Captures
//

// get '/captures/all'
/**
 * @api {get} /captures/all /captures/all
 * @apiDescription Gets all the captures (simple/pcap format) in the CAPTURES_DIR
 * @apiName CapturesAll
 * @apiGroup Captures
 *
 * @apiSuccess {String} code Return code ('success')
 * @apiSuccess {String} type Return type ('data')
 * @apiSuccess {Object} captures Array of information for each capture
 * @apiSuccess {String} captures.name Name of the capture
 * @apiSuccess {Number} captures.type Type of the capture (simple/pcap)
 * @apiSuccess {String} captures.size Size of the capture (in bytes)
 * @apiSuccess {String} captures.date Date of the capture (yyyy-mm-dd hh:mm:ss)
 *
 * @apiSuccessExample {json} Example data on success
 * {
 *   "code": "success",
 *   "type": "data",
 *   "captures": [
 *     {
 *       "name": "2_flows_crc",
 *       "type": "simple",
 *       "size": 956092345897,
 *       "date": "2015-03-05 13:42:15"
 *     },
 *     {
 *       "name": "my_capture0.simple",
 *       "type": "pcap",
 *       "size": 4981234712,
 *       "date": "2015-02-16 11:08:18"
 *     },
 *     {
 *       "name": "capture_labs.pcap",
 *       "type": "pcap",
 *       "size": 30563653141,
 *       "date": "2015-01-11 17:32:19"
 *     }
 *   ]
 * }
 */

// get '/captures/simple'
/**
 * @api {get} /captures/simple /captures/simple
 * @apiDescription Gets all the simple captures in the CAPTURES_DIR
 * @apiName CapturesSimple
 * @apiGroup Captures
 *
 * @apiSuccess {String} code Return code ('success')
 * @apiSuccess {String} type Return type ('data')
 * @apiSuccess {Object} captures Array of information for each capture
 * @apiSuccess {String} captures.name Name of the capture
 * @apiSuccess {Number} captures.type Type of the capture (simple)
 * @apiSuccess {String} captures.size Size of the capture (in bytes)
 * @apiSuccess {String} captures.date Date of the capture (yyyy-mm-dd hh:mm:ss)
 *
 * @apiSuccessExample {json} Example data on success
 * {
 *   "code": "success",
 *   "type": "data",
 *   "captures": [
 *     {
 *       "name": "2_flows_crc",
 *       "type": "simple",
 *       "size": 956092345897,
 *       "date": "2015-03-05 13:42:15"
 *     }
 *   ]
 * }
 */

// get '/captures/pcap'
/**
 * @api {get} /captures/pcap /captures/pcap
 * @apiDescription Gets all the pcap captures in the CAPTURES_DIR
 * @apiName CapturesPcap
 * @apiGroup Captures
 *
 * @apiSuccess {String} code Return code ('success')
 * @apiSuccess {String} type Return type ('data')
 * @apiSuccess {Object} captures Array of information for each capture
 * @apiSuccess {String} captures.name Name of the capture
 * @apiSuccess {Number} captures.type Type of the capture (pcap)
 * @apiSuccess {String} captures.size Size of the capture (in bytes)
 * @apiSuccess {String} captures.date Date of the capture (yyyy-mm-dd hh:mm:ss)
 *
 * @apiSuccessExample {json} Example data on success
 * {
 *   "code": "success",
 *   "type": "data",
 *   "captures": [
 *     {
 *       "name": "my_capture0.simple",
 *       "type": "pcap",
 *       "size": 4981234712,
 *       "date": "2015-02-16 11:08:18"
 *     },
 *     {
 *       "name": "capture_labs.pcap",
 *       "type": "pcap",
 *       "size": 30563653141,
 *       "date": "2015-01-11 17:32:19"
 *     }
 *   ]
 * }
 */

// get '/captures/path'
/**
 * @api {get} /captures/path /captures/path
 * @apiDescription Gets the path where the captures are stored
 * @apiName CapturesPath
 * @apiGroup Captures
 *
 * @apiSuccess {String} code Return code ('success')
 * @apiSuccess {String} type Return type ('data')
 * @apiSuccess {String} path Path where the captures are stored
 *
 * @apiSuccessExample {json} Example data on success
 * {
 *   "code": "success",
 *   "type": "data",
 *   "path": "/dev/raid/captures/"
 * }
 */

// put '/captures/rename/:oldname/:newname'
/**
 * @api {put} /captures/rename/:oldname/:newname /captures/rename/
 * @apiDescription Renames a capture
 * @apiName CapturesRename
 * @apiGroup Captures
 *
 * @apiParam {String} oldname Name of the capture to be renamed
 * @apiParam {String} newname New name of the capture
 *
 * @apiError (Error 400) {String} code Return code ('error')
 * @apiError (Error 400) {String} type Return type ('notification')
 * @apiError (Error 400) {String} description Description of the error
 *
 * @apiErrorExample {json} Example data on error 400
 * {
 *  "code": "error",
 *  "type": "notification",
 *  "description": "The capture could not be renamed. The new name is already in use or the capture is being used."
 * }
 *
 * @apiSuccess {String} code Return code ('success')
 * @apiSuccess {String} type Return type ('notification')
 * @apiSuccess {String} description Description of the success code
 *
 * @apiSuccessExample {json} Example data on success
 * {
 *  "code": "success",
 *  "type": "notification",
 *  "description": "The capture has been successfully renamed."
 * }
 */

// put '/captures/simple/pcap/:name/:convertedname'
/**
 * @api {put} /captures/simple/pcap/:name/:convertedname /captures/simple/pcap/
 * @apiDescription Converts a capture from simple to pcap (the original is mantained)
 * @apiName CapturesSimplePcap
 * @apiGroup Captures
 *
 * @apiParam {String} name Name of the capture simple capture to be converted
 * @apiParam {String} convertedname New name of the converted pcap capture
 *
 * @apiError (Error 400) {String} code Return code ('error')
 * @apiError (Error 400) {String} type Return type ('notification')
 * @apiError (Error 400) {String} description Description of the error
 *
 * @apiErrorExample {json} Example data on error 400
 * {
 *  "code": "error",
 *  "type": "notification",
 *  "description": "The capture could not be converted. The capture has not a valid format or name, or the new name is already in use."
 * }
 *
 * @apiSuccess {String} code Return code ('success')
 * @apiSuccess {String} type Return type ('notification')
 * @apiSuccess {String} description Description of the success code
 *
 * @apiSuccessExample {json} Example data on success
 * {
 *  "code": "success",
 *  "type": "notification",
 *  "description": "The capture has been successfully converted."
 * }
 */

// put '/captures/pcap/simple/:name/:convertedname'
/**
 * @api {put} /captures/pcap/simple/:name/:convertedname /captures/pcap/simple/
 * @apiDescription Converts a capture from pcap to simple (the original is mantained)
 * @apiName CapturesPcapSimple
 * @apiGroup Captures
 *
 * @apiParam {String} name Name of the capture pcap capture to be converted
 * @apiParam {String} convertedname New name of the converted simple capture
 *
 * @apiError (Error 400) {String} code Return code ('error')
 * @apiError (Error 400) {String} type Return type ('notification')
 * @apiError (Error 400) {String} description Description of the error
 *
 * @apiErrorExample {json} Example data on error 400
 * {
 *  "code": "error",
 *  "type": "notification",
 *  "description": "The capture could not be converted. The capture has not a valid format or name, or the new name is already in use."
 * }
 *
 * @apiSuccess {String} code Return code ('success')
 * @apiSuccess {String} type Return type ('notification')
 * @apiSuccess {String} description Description of the success code
 *
 * @apiSuccessExample {json} Example data on success
 * {
 *  "code": "success",
 *  "type": "notification",
 *  "description": "The capture has been successfully converted."
 * }
 */

// delete '/captures/delete/:name'
/**
 * @api {delete} /captures/delete/:name /captures/delete/
 * @apiDescription Deletes a capture
 * @apiName CapturesDelete
 * @apiGroup Captures
 *
 * @apiParam {String} name Name of the capture to be deleted
 *
 * @apiError (Error 400) {String} code Return code ('error')
 * @apiError (Error 400) {String} type Return type ('notification')
 * @apiError (Error 400) {String} description Description of the error
 *
 * @apiErrorExample {json} Example data on error 400
 * {
 *  "code": "error",
 *  "type": "notification",
 *  "description": "The capture could not be deleted (it is in use or it does not exist)."
 * }
 *
 * @apiSuccess {String} code Return code ('success')
 * @apiSuccess {String} type Return type ('notification')
 * @apiSuccess {String} description Description of the success code
 *
 * @apiSuccessExample {json} Example data on success
 * {
 *  "code": "success",
 *  "type": "notification",
 *  "description": "The capture has been successfully converted."
 * }
 */