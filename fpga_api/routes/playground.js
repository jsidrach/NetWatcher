// File to test different things (playground)

// Package dependencies
var scripts    = require('child_process');
var common     = require('./_common.js');

// Test example to see if everything is working and to understand different concepts
exports.example = function(req, res) {
  var code_script = scripts.exec('ls -al', function (error, stdout, stderr) {
    if(error)
    {
     console.log(error.code);
     return;
    }
    console.log(stdout);
  });


  code_script.on('exit', function (code) {
    console.log('Child process exited with exit code '+code);
    var ans;
    common.readJSON('config_off', ans, function(ans)
    {
      res.json(ans);
    });
  });
};