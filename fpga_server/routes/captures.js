// Captures module

// Package dependencies
var scripts    = require('child_process');
var common     = require('./_common.js');

// Gets all the captures in the ./data/ dir
exports.getAll = function(req, res) {
  var code_script = scripts.exec('./commands/show_captures.sh', function (error, stdout, stderr) {
    if(error)
    {
     console.log(error.code);
     return;
    }
    res.json(common.csv2JSONstring(stdout));
  });
};