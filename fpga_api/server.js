// Package dependencies
var express = require('express');
var bodyParser = require('body-parser');
// Express
var app = express();

// Config module
var config = require('./config.js');
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
app.disable('x-powered-by');

// Set the port
var port = process.env.PORT || config.PORT;

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

router.post('/system/reboot', manager.reboot);

router.post('/player/init', manager.initPlayer);
router.post('/recorder/init', manager.initRecorder);

router.post('/player/install', manager.installPlayer);
router.post('/recorder/install', manager.installRecorder);

router.post('/player/start/:capturename/:mask/:ifg', manager.startPlayer);
router.post('/player/start/loop/:capturename/:mask/:ifg', manager.startPlayerLoop);
router.post('/recorder/start/:capturename/:port/:bytes', manager.startRecorder);

router.post('/player/stop', manager.stopPlayer);
router.post('/recorder/stop', manager.stopRecorder);


// Real Time Statistics

router.get('/info/ping', statistics.ping);
router.get('/info/delay', statistics.delay);

router.get('/info/status', statistics.status);
// router.get('/disk/stats', statistics.diskStats);
// router.delete('/disk/:id', statistics.diskDelete);


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
app.use(config.BASE_PREFIX, router)
  // Other routes will return 404 - Not Found 
app.use('/', defError);

// Start the server
app.listen(port);