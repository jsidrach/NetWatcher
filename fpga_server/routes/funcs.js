// Package dependencies
var fs       = require('fs');
var path     = require('path'); 

// Reads a json in the messages folder
var readJSON = function (file, obj, callback)
{
  fs.readFile(path.resolve(__dirname, '../messages/', file + '.json'), 'utf8', function (err, data) {
    if(err)
    {
      obj = { message: 'Not Found' };
    }
    else
    {
      obj = JSON.parse(data);
    }
    callback(obj);
  });
};


// test example to see if everything is working and the json reader is ok
exports.example = function(req, res) {
  var ans;
  readJSON('example', ans, function(ans)
  {
    res.json(ans);
  });
};