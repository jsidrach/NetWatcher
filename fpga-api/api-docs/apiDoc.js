//
// Manager
//


// post '/system/reboot'

// post '/player/init'
// post '/recorder/init'

// post '/player/install'
// post '/recorder/install'

// post '/player/start/:capturename/:mask/:ifg'
// post '/player/start/loop/:capturename/:mask/:ifg'
// post '/recorder/start/:capturename/:port/:bytes'

// post '/player/stop'
// post '/recorder/stop'

// delete '/storage/raid'

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
 * @apiSuccessExample {json} Example data on success:
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
 * @apiHeader {Number} timestamp Milliseconds elapsed since 1 January 1970 00:00:00 UTC up until now (output of Date.now())
 *
 * @apiError (Error 400) TimestampNotFound Timestamp is not set in the request's header
 *
 * @apiSuccess {String} code Return code ('success')
 * @apiSuccess {Number} delay Delay in seconds between the caller and the FPGA Web Service
 * @apiSuccess {Number} maxDelay Maximum delay allowed in seconds between the caller and the FPGA Web Service
 *
 * @apiSuccessExample {json} Example data on success:
 * {
 *  "code": "success",
 *  "delay": 1,
 *  "maxDelay": 30
 * }
 */



// get '/info/status'
// get '/storage/stats'

//
// Captures
//

// get '/captures/all'
// get '/captures/simple'
// get '/captures/pcap'

// get '/captures/path'

// put '/captures/rename/:oldname/:newname'

// put '/captures/simple/pcap/:name/:convertedname'
// put '/captures/pcap/simple/:name/:convertedname'

// delete '/captures/delete/:name'