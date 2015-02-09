// Common functions for the scripts

// Package dependencies
var fs         = require('fs');
var path       = require('path');

// Reads a json in the messages folder
exports.readJSON = function (file, obj, callback)
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