// Package dependencies
var express = require('express');
var bodyParser = require('body-parser');
// Express
var app = express();

// Common module
var common = require('./routes/_common.js');
// Functions module for each petition
var manager = require('./routes/manager.js');
var statistics = require('./routes/statistics.js');
var captures = require('./routes/captures.js');

// Configure app to use bodyParser() so it will let us get the data from a POST
app.use(bodyParser.urlencoded({
  extended: true
}));
app.use(bodyParser.json());

// Set the port
var port = process.env.PORT || 1337;

// Routes for the FPGA API
var router = express.Router();
// Default routes (Not Found Error)
var defError = express.Router();

// Middleware to discard out of time request (reproduced calls with the FPGA)
app.route('*')
  .post(common.handleRequest)
  .put(common.handleRequest)
  .delete(common.handleRequest);

// Routes and Modules


// Manager

// router.post('/player/init', manager.initPlayer);
// router.post('/recorder/init', manager.initRecorder);

// router.post('/player/configure/:param1/:param2/:param3...', manager.configurePlayer);
// router.post('/recorder/configure/:param1/:param2/:param3...', manager.configureRecorder);

// router.post('/player/start', manager.startPlayer);
// router.post('/recorder/start', manager.startRecorder);

// router.post('/player/pause', manager.pausePlayer);
// router.post('/recorder/pause', manager.pauseRecorder);

// router.post('/player/stop', manager.stopPlayer);
// router.post('/recorder/stop', manager.stopRecorder);

// Real Time Statistics

router.get('/ping', statistics.ping);
router.get('/delay', statistics.delay);
router.get('/status', statistics.status);
// router.get('/player/statistics', statistics.playerStats);
// router.get('/recorder/statistics', statistics.recorderStats);


// Captures

router.get('/captures/all', captures.all);
router.get('/captures/simple', captures.simple);
router.get('/captures/pcap', captures.pcap);
router.get('/captures/path', captures.path);
router.put('/captures/rename/:oldname/:newname', captures.rename);
router.put('/captures/simple/pcap/:name/:convertedname', captures.convertToPcap);
router.put('/captures/pcap/simple/:name/:convertedname', captures.convertToSimple);
router.delete('/captures/delete/:name', captures.delete);


// Default router
defError.get('*', function (req, res) {
  res.sendStatus(404);
});

// All of our correct routes will be prefixed with /fpga/api
app.use('/fpga/api', router)
  // Other routes will return 404 - Not Found 
app.use('/', defError);

// Start the server
app.listen(port);