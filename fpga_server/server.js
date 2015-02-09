// Package dependencies
var express        = require('express');
var bodyParser     = require('body-parser');
// Express
var app            = express();

// Functions module for each petition
var manager        = require('./routes/manager.js');
var statistics     = require('./routes/statistics.js');
var captures       = require('./routes/captures.js');

// TODO: Delete example playground
var playground     = require('./routes/playground.js');

// Configure app to use bodyParser() so it will let us get the data from a POST
app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());

// Set the port
var port           = process.env.PORT || 1337;

// Routes for the FPGA API
var router         = express.Router();
// Default routes (Not Found Error)
var defError       = express.Router();

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

// router.get('/ping', statistics.ping);
// router.get('/status', statistics.getStatus);
// router.get('/player/statistics', statistics.getPlayerStats);
// router.get('/recorder/statistics', statistics.getRecorderStats);


// Captures

// router.get('/info/captures', captures.getAll);
// router.put('/rename/:oldname/:newname', captures.rename);
// router.delete('/delete/:name', captures.delete);
// router.post('/parse/:name/:parsedname', captures.parse);



// TODO: delete example route
router.get('/example', playground.example);

// Default router
defError.get('*', function(req, res){
    res.sendStatus(404);
});

// All of our correct routes will be prefixed with /fpga_api
app.use('/fpga_api', router);
// Other routes will return 404 - Not Found 
app.use('/', defError);

// Start the server
app.listen(port);