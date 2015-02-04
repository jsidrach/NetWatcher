// Package dependencies
var express    = require('express');
var bodyParser = require('body-parser');
// Express
var app        = express();

// Functions module for each petition
// TODO: One javascript for each typep
var playground     = require('./routes/playground.js');

// Configure app to use bodyParser() so it will let us get the data from a POST
app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());

// Set the port
var port = process.env.PORT || 1337;

// Routes for the FPGA API
var router = express.Router();
// Default routes (Not Found Error)
var defError = express.Router();

// Routes and Modules

// Example route
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