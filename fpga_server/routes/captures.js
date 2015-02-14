// Captures module

// Package dependencies
var scripts    = require('child_process');
var fs         = require('fs');
var common     = require('./_common.js');

// /captures/all
// Gets all the captures (simple/pcap format) in the ./data/ dir
exports.all = function(req, res) {
  var code_script = scripts.exec('./commands/show_captures.sh', function (error, stdout, stderr) {
    if(error)
    {
      console.error('Error executing the show_captures command');
      return;
    }
    var ans;
    common.readJSON('captures_data', ans, function(ans)
    {
      ans.files = common.csv2JSONobject(stdout);
      res.json(ans);
    });
  });
};

// /captures/simple
// Gets all the captures (simple/pcap format) in the ./data/ dir
exports.simple = function(req, res) {
  var code_script = scripts.exec('./commands/show_captures_simple.sh', function (error, stdout, stderr) {
    if(error)
    {
      console.error('Error executing the show_captures_simple command');
      return;
    }
    var ans;
    common.readJSON('captures_data', ans, function(ans)
    {
      ans.files = common.csv2JSONobject(stdout);
      res.json(ans);
    });
  });
};

// /captures/folder
// Gets all the files in the ./data/ dir
exports.folder = function(req, res) {
  var code_script = scripts.exec('./commands/show_data_files.sh', function (error, stdout, stderr) {
    if(error)
    {
      console.error('Error executing the show_data_files command');
      return;
    }
    var ans;
    common.readJSON('captures_data', ans, function(ans)
    {
      ans.files = common.csv2JSONobject(stdout);
      res.json(ans);
    });
  });
};

// /captures/rename/:oldname/:newname
// Renames a capture
exports.rename = function(req, res) {  
  var ans;
  // Valid params
  if((!common.captureExists(req.params.oldname)) || (!common.validNewName(req.params.newname)))
  {
    common.readJSON('captures_rename_error', ans, function(ans)
    {
      res.json(ans);
    });
    return;
  }

  // Rename
  fs.rename('data/'+req.params.oldname, 'data/'+req.params.newname, function (err) {
    if(err)
    {
      console.error(err.message);
      common.readJSON('captures_rename_error', ans, function(ans)
      {
        res.json(ans);
      });
      return;
    }
    common.readJSON('captures_rename_success', ans, function(ans)
    {
      res.json(ans);
    });
  });
};

// /captures/delete/:name
// Deletes a file in the data folder
exports.delete = function(req, res) {  
  var ans;
  // Valid param
  if(!common.captureExists(req.params.name))
  {
    common.readJSON('captures_delete_error', ans, function(ans)
    {
      res.json(ans);
    });
    return;
  }

  // Delete
  fs.unlink('data/'+req.params.name, function (err) {
    if(err)
    {
      console.error(err.message);
      common.readJSON('captures_delete_error', ans, function(ans)
      {
        res.json(ans);
      });
      return;
    }
    common.readJSON('captures_delete_success', ans, function(ans)
    {
      res.json(ans);
    });
  });
};