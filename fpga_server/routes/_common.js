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
    
    if(currentline.length == headers.length)
    {
      for(var j = 0; j < headers.length;j++) {
        obj[headers[j]] = currentline[j];
      }
      result.push(obj);
    }
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
 
    if(currentline.length == headers.length)
    {
      for(var j = 0; j < headers.length;j++) {
        obj[headers[j]] = currentline[j];
      }
      result.push(obj);
    }
  }
  
  // Return Javascript Object
  return result;
};

// Checks if a name is valid (syntactically)
function validName(name)
{
  var flag = true;
  // Name is valid if it does not have the following substrings:
  ['\\/', '\\.\\.', '\\$', '\\~'].every(function(entry) {
    if(name.search(entry) != -1)
    {
      flag = false;
      return false;
    }
    return true;
  });
  return flag;
};
exports.validName = validName;

// Checks if a new name is available and valid
function validNewName(name)
{
  // Valid name
  if(!validName(name))
  {
    return false;
  }
  // File already exists
  if (fs.existsSync('data/'+name)) {
    return false;
  }
  return true;
};
exports.validNewName = validNewName;

// Checks if a capture exists with a given name (full name)
exports.captureExists = function (name)
{
  // Valid name
  if(!validName(name))
  {
    return false;
  }
  // File already exists
  if (!fs.existsSync('data/'+name)) {
    return false;
  }
  return true;
}

// Checks if a capture has a valid simple format
exports.validSimpleCapture = function (name)
{
  // TODO: Identify simple captures
  return true;
}

// Checks if a capture has a valid pcap format
exports.validPcapCapture = function (name)
{
  // TODO: Identify valid pcap catures
  return true;
}