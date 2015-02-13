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

// Parses a csv into a JSON string
exports.csv2JSONstring = function (csv)
{ 
  var lines = csv.split("\n"); 
  var result = []; 
  var headers = lines[0].split("|");
 
  for(var i = 1; i < lines.length; i++) { 
    var obj = {};
    var currentline = lines[i].split("|");
 
    for(var j = 0; j < headers.length;j++) {
      obj[headers[j]] = currentline[j];
    } 
    result.push(obj); 
  }
  
  // Return JSON
  return JSON.stringify(result);
};

// Parses a csv into a JSON Javascript object
exports.csv2JSONobject = function (csv)
{ 
  var lines = csv.split("\n"); 
  var result = []; 
  var headers = lines[0].split("|");
 
  for(var i = 1; i < lines.length; i++) { 
    var obj = {};
    var currentline = lines[i].split("|");
 
    for(var j = 0; j < headers.length;j++) {
      obj[headers[j]] = currentline[j];
    } 
    result.push(obj); 
  }
  
  // Return Javascript Object
  return result;
};