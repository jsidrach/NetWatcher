define({ api: [
  {
    "type": "get",
    "url": "/captures/all",
    "title": "/captures/all",
    "description": "<p>Gets all the captures (simple/pcap format) in the CAPTURES_DIR</p>",
    "name": "CapturesAll",
    "group": "Captures",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "field": "code",
            "optional": false,
            "description": "<p>Return code (&#39;success&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "type",
            "optional": false,
            "description": "<p>Return type (&#39;data&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "field": "captures",
            "optional": false,
            "description": "<p>Array of information for each capture</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "captures.name",
            "optional": false,
            "description": "<p>Name of the capture</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "captures.type",
            "optional": false,
            "description": "<p>Type of the capture (simple/pcap)</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "field": "captures.size",
            "optional": false,
            "description": "<p>Size of the capture (in bytes)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "captures.date",
            "optional": false,
            "description": "<p>Date of the capture (yyyy-mm-dd hh:mm:ss)</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Example data on success",
          "content": "{\n \"code\": \"success\",\n \"type\": \"data\",\n \"captures\": [\n   {\n     \"name\": \"2_flows_crc\",\n     \"type\": \"simple\",\n     \"size\": 956092345897,\n     \"date\": \"2015-03-05 13:42:15\"\n   },\n   {\n     \"name\": \"my_capture0.simple\",\n     \"type\": \"pcap\",\n     \"size\": 4981234712,\n     \"date\": \"2015-02-16 11:08:18\"\n   },\n   {\n     \"name\": \"capture_labs.pcap\",\n     \"type\": \"pcap\",\n     \"size\": 30563653141,\n     \"date\": \"2015-01-11 17:32:19\"\n   }\n ]\n}\n",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "fpga-api/api-docs/apiDoc.js"
  },
  {
    "type": "delete",
    "url": "/captures/delete/:name",
    "title": "/captures/delete/",
    "description": "<p>Deletes a capture</p>",
    "name": "CapturesDelete",
    "group": "Captures",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "field": "name",
            "optional": false,
            "description": "<p>Name of the capture to be deleted</p>"
          }
        ]
      }
    },
    "error": {
      "fields": {
        "Error 400": [
          {
            "group": "Error 400",
            "type": "String",
            "field": "code",
            "optional": false,
            "description": "<p>Return code (&#39;error&#39;)</p>"
          },
          {
            "group": "Error 400",
            "type": "String",
            "field": "type",
            "optional": false,
            "description": "<p>Return type (&#39;notification&#39;)</p>"
          },
          {
            "group": "Error 400",
            "type": "String",
            "field": "description",
            "optional": false,
            "description": "<p>Description of the error</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Example data on error 400",
          "content": "{\n\"code\": \"error\",\n\"type\": \"notification\",\n\"description\": \"The capture could not be deleted (it is in use or it does not exist).\"\n}\n",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "field": "code",
            "optional": false,
            "description": "<p>Return code (&#39;success&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "type",
            "optional": false,
            "description": "<p>Return type (&#39;notification&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "description",
            "optional": false,
            "description": "<p>Description of the success code</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Example data on success",
          "content": "{\n\"code\": \"success\",\n\"type\": \"notification\",\n\"description\": \"The capture has been successfully deleted.\"\n}\n",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "fpga-api/api-docs/apiDoc.js"
  },
  {
    "type": "get",
    "url": "/captures/path",
    "title": "/captures/path",
    "description": "<p>Gets the path where the captures are stored</p>",
    "name": "CapturesPath",
    "group": "Captures",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "field": "code",
            "optional": false,
            "description": "<p>Return code (&#39;success&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "type",
            "optional": false,
            "description": "<p>Return type (&#39;data&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "path",
            "optional": false,
            "description": "<p>Path where the captures are stored</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Example data on success",
          "content": "{\n \"code\": \"success\",\n \"type\": \"data\",\n \"path\": \"/dev/raid/captures/\"\n}\n",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "fpga-api/api-docs/apiDoc.js"
  },
  {
    "type": "get",
    "url": "/captures/pcap",
    "title": "/captures/pcap",
    "description": "<p>Gets all the pcap captures in the CAPTURES_DIR</p>",
    "name": "CapturesPcap",
    "group": "Captures",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "field": "code",
            "optional": false,
            "description": "<p>Return code (&#39;success&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "type",
            "optional": false,
            "description": "<p>Return type (&#39;data&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "field": "captures",
            "optional": false,
            "description": "<p>Array of information for each capture</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "captures.name",
            "optional": false,
            "description": "<p>Name of the capture</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "captures.type",
            "optional": false,
            "description": "<p>Type of the capture (pcap)</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "field": "captures.size",
            "optional": false,
            "description": "<p>Size of the capture (in bytes)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "captures.date",
            "optional": false,
            "description": "<p>Date of the capture (yyyy-mm-dd hh:mm:ss)</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Example data on success",
          "content": "{\n \"code\": \"success\",\n \"type\": \"data\",\n \"captures\": [\n   {\n     \"name\": \"my_capture0.simple\",\n     \"type\": \"pcap\",\n     \"size\": 4981234712,\n     \"date\": \"2015-02-16 11:08:18\"\n   },\n   {\n     \"name\": \"capture_labs.pcap\",\n     \"type\": \"pcap\",\n     \"size\": 30563653141,\n     \"date\": \"2015-01-11 17:32:19\"\n   }\n ]\n}\n",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "fpga-api/api-docs/apiDoc.js"
  },
  {
    "type": "put",
    "url": "/captures/pcap/simple/:name/:convertedname",
    "title": "/captures/pcap/simple/",
    "description": "<p>Converts a capture from pcap to simple (the original is mantained)</p>",
    "name": "CapturesPcapSimple",
    "group": "Captures",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "field": "name",
            "optional": false,
            "description": "<p>Name of the capture pcap capture to be converted</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "field": "convertedname",
            "optional": false,
            "description": "<p>New name of the converted simple capture</p>"
          }
        ]
      }
    },
    "error": {
      "fields": {
        "Error 400": [
          {
            "group": "Error 400",
            "type": "String",
            "field": "code",
            "optional": false,
            "description": "<p>Return code (&#39;error&#39;)</p>"
          },
          {
            "group": "Error 400",
            "type": "String",
            "field": "type",
            "optional": false,
            "description": "<p>Return type (&#39;notification&#39;)</p>"
          },
          {
            "group": "Error 400",
            "type": "String",
            "field": "description",
            "optional": false,
            "description": "<p>Description of the error</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Example data on error 400",
          "content": "{\n\"code\": \"error\",\n\"type\": \"notification\",\n\"description\": \"The capture could not be converted. The capture has not a valid format or name, or the new name is already in use.\"\n}\n",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "field": "code",
            "optional": false,
            "description": "<p>Return code (&#39;success&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "type",
            "optional": false,
            "description": "<p>Return type (&#39;notification&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "description",
            "optional": false,
            "description": "<p>Description of the success code</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Example data on success",
          "content": "{\n\"code\": \"success\",\n\"type\": \"notification\",\n\"description\": \"The capture has been successfully converted.\"\n}\n",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "fpga-api/api-docs/apiDoc.js"
  },
  {
    "type": "put",
    "url": "/captures/rename/:oldname/:newname",
    "title": "/captures/rename/",
    "description": "<p>Renames a capture</p>",
    "name": "CapturesRename",
    "group": "Captures",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "field": "oldname",
            "optional": false,
            "description": "<p>Name of the capture to be renamed</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "field": "newname",
            "optional": false,
            "description": "<p>New name of the capture</p>"
          }
        ]
      }
    },
    "error": {
      "fields": {
        "Error 400": [
          {
            "group": "Error 400",
            "type": "String",
            "field": "code",
            "optional": false,
            "description": "<p>Return code (&#39;error&#39;)</p>"
          },
          {
            "group": "Error 400",
            "type": "String",
            "field": "type",
            "optional": false,
            "description": "<p>Return type (&#39;notification&#39;)</p>"
          },
          {
            "group": "Error 400",
            "type": "String",
            "field": "description",
            "optional": false,
            "description": "<p>Description of the error</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Example data on error 400",
          "content": "{\n\"code\": \"error\",\n\"type\": \"notification\",\n\"description\": \"The capture could not be renamed. The new name is already in use or the capture is being used.\"\n}\n",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "field": "code",
            "optional": false,
            "description": "<p>Return code (&#39;success&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "type",
            "optional": false,
            "description": "<p>Return type (&#39;notification&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "description",
            "optional": false,
            "description": "<p>Description of the success code</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Example data on success",
          "content": "{\n\"code\": \"success\",\n\"type\": \"notification\",\n\"description\": \"The capture has been successfully renamed.\"\n}\n",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "fpga-api/api-docs/apiDoc.js"
  },
  {
    "type": "get",
    "url": "/captures/simple",
    "title": "/captures/simple",
    "description": "<p>Gets all the simple captures in the CAPTURES_DIR</p>",
    "name": "CapturesSimple",
    "group": "Captures",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "field": "code",
            "optional": false,
            "description": "<p>Return code (&#39;success&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "type",
            "optional": false,
            "description": "<p>Return type (&#39;data&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "field": "captures",
            "optional": false,
            "description": "<p>Array of information for each capture</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "captures.name",
            "optional": false,
            "description": "<p>Name of the capture</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "captures.type",
            "optional": false,
            "description": "<p>Type of the capture (simple)</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "field": "captures.size",
            "optional": false,
            "description": "<p>Size of the capture (in bytes)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "captures.date",
            "optional": false,
            "description": "<p>Date of the capture (yyyy-mm-dd hh:mm:ss)</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Example data on success",
          "content": "{\n \"code\": \"success\",\n \"type\": \"data\",\n \"captures\": [\n   {\n     \"name\": \"2_flows_crc\",\n     \"type\": \"simple\",\n     \"size\": 956092345897,\n     \"date\": \"2015-03-05 13:42:15\"\n   }\n ]\n}\n",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "fpga-api/api-docs/apiDoc.js"
  },
  {
    "type": "put",
    "url": "/captures/simple/pcap/:name/:convertedname",
    "title": "/captures/simple/pcap/",
    "description": "<p>Converts a capture from simple to pcap (the original is mantained)</p>",
    "name": "CapturesSimplePcap",
    "group": "Captures",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "field": "name",
            "optional": false,
            "description": "<p>Name of the capture simple capture to be converted</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "field": "convertedname",
            "optional": false,
            "description": "<p>New name of the converted pcap capture</p>"
          }
        ]
      }
    },
    "error": {
      "fields": {
        "Error 400": [
          {
            "group": "Error 400",
            "type": "String",
            "field": "code",
            "optional": false,
            "description": "<p>Return code (&#39;error&#39;)</p>"
          },
          {
            "group": "Error 400",
            "type": "String",
            "field": "type",
            "optional": false,
            "description": "<p>Return type (&#39;notification&#39;)</p>"
          },
          {
            "group": "Error 400",
            "type": "String",
            "field": "description",
            "optional": false,
            "description": "<p>Description of the error</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Example data on error 400",
          "content": "{\n\"code\": \"error\",\n\"type\": \"notification\",\n\"description\": \"The capture could not be converted. The capture has not a valid format or name, or the new name is already in use.\"\n}\n",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "field": "code",
            "optional": false,
            "description": "<p>Return code (&#39;success&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "type",
            "optional": false,
            "description": "<p>Return type (&#39;notification&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "description",
            "optional": false,
            "description": "<p>Description of the success code</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Example data on success",
          "content": "{\n\"code\": \"success\",\n\"type\": \"notification\",\n\"description\": \"The capture has been successfully converted.\"\n}\n",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "fpga-api/api-docs/apiDoc.js"
  },
  {
    "type": "post",
    "url": "/player/init",
    "title": "/player/init",
    "description": "<p>Programs the FPGA as a player and reboots the system</p>",
    "name": "PlayerInit",
    "group": "Manager",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "Number",
            "field": "timestamp",
            "optional": false,
            "description": "<p>Milliseconds elapsed since 1 January 1970 00:00:00 UTC until now (output of Date.now())</p>"
          }
        ]
      }
    },
    "error": {
      "fields": {
        "Error 412": [
          {
            "group": "Error 412",
            "type": "String",
            "field": "code",
            "optional": false,
            "description": "<p>Return code (&#39;error&#39;)</p>"
          },
          {
            "group": "Error 412",
            "type": "String",
            "field": "type",
            "optional": false,
            "description": "<p>Return type (&#39;fpga_invalid_state&#39;)</p>"
          },
          {
            "group": "Error 412",
            "type": "String",
            "field": "description",
            "optional": false,
            "description": "<p>Description of the error</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Example data on error 412",
          "content": "{\n\"code\": \"error\",\n\"type\": \"fpga_invalid_state\",\n\"description\": \"Invalid State. The FPGA is already running, stop it to init the FPGA.\"\n}\n",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "field": "code",
            "optional": false,
            "description": "<p>Return code (&#39;success&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "type",
            "optional": false,
            "description": "<p>Return type (&#39;notification&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "description",
            "optional": false,
            "description": "<p>Description of the success code</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Example data on success",
          "content": "{\n\"code\": \"success\",\n\"type\": \"notification\",\n\"description\": \"The FPGA has been initialized. The host will reboot now.\"\n}\n",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "fpga-api/api-docs/apiDoc.js"
  },
  {
    "type": "post",
    "url": "/player/install",
    "title": "/player/install",
    "description": "<p>Installs the driver and mounts the FPGA as a player</p>",
    "name": "PlayerInstall",
    "group": "Manager",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "Number",
            "field": "timestamp",
            "optional": false,
            "description": "<p>Milliseconds elapsed since 1 January 1970 00:00:00 UTC until now (output of Date.now())</p>"
          }
        ]
      }
    },
    "error": {
      "fields": {
        "Error 412": [
          {
            "group": "Error 412",
            "type": "String",
            "field": "code",
            "optional": false,
            "description": "<p>Return code (&#39;error&#39;)</p>"
          },
          {
            "group": "Error 412",
            "type": "String",
            "field": "type",
            "optional": false,
            "description": "<p>Return type (&#39;fpga_invalid_state&#39;)</p>"
          },
          {
            "group": "Error 412",
            "type": "String",
            "field": "description",
            "optional": false,
            "description": "<p>Description of the error</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Example data on error 412",
          "content": "{\n\"code\": \"error\",\n\"type\": \"fpga_invalid_state\",\n\"description\": \"Invalid State. The FPGA must be programmed before mounted.\"\n}\n",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "field": "code",
            "optional": false,
            "description": "<p>Return code (&#39;success&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "type",
            "optional": false,
            "description": "<p>Return type (&#39;notification&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "description",
            "optional": false,
            "description": "<p>Description of the success code</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Example data on success",
          "content": "{\n\"code\": \"success\",\n\"type\": \"notification\",\n\"description\": \"The FPGA has been mounted and is ready to be used.\"\n}\n",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "fpga-api/api-docs/apiDoc.js"
  },
  {
    "type": "post",
    "url": "/player/start/:capturename/:mask/:ifg",
    "title": "/player/start/",
    "description": "<p>Reproduces a capture</p>",
    "name": "PlayerStart",
    "group": "Manager",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "Number",
            "field": "timestamp",
            "optional": false,
            "description": "<p>Milliseconds elapsed since 1 January 1970 00:00:00 UTC until now (output of Date.now())</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "field": "capturename",
            "optional": false,
            "description": "<p>Name of the capture to be reproduced (it must be in simple format)</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "field": "mask",
            "optional": false,
            "description": "<p>Mask (set of ports) where the capture is going to be reproduced (0-1-2-3)</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "field": "ifg",
            "optional": false,
            "description": "<p>Interframe gap (0 to original captured rate)</p>"
          }
        ]
      }
    },
    "error": {
      "fields": {
        "Error 412": [
          {
            "group": "Error 412",
            "type": "String",
            "field": "code",
            "optional": false,
            "description": "<p>Return code (&#39;error&#39;)</p>"
          },
          {
            "group": "Error 412",
            "type": "String",
            "field": "type",
            "optional": false,
            "description": "<p>Return type (&#39;fpga_invalid_state&#39;)</p>"
          },
          {
            "group": "Error 412",
            "type": "String",
            "field": "description",
            "optional": false,
            "description": "<p>Description of the error</p>"
          }
        ],
        "Error 400": [
          {
            "group": "Error 400",
            "type": "String",
            "field": "code",
            "optional": false,
            "description": "<p>Return code (&#39;error&#39;)</p>"
          },
          {
            "group": "Error 400",
            "type": "String",
            "field": "type",
            "optional": false,
            "description": "<p>Return type (&#39;notification&#39;)</p>"
          },
          {
            "group": "Error 400",
            "type": "String",
            "field": "description",
            "optional": false,
            "description": "<p>Description of the error</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Example data on error 412",
          "content": "{\n\"code\": \"error\",\n\"type\": \"fpga_invalid_state\",\n\"description\": \"Invalid State. The FPGA is not programmed and mounted as a player.\"\n}\n",
          "type": "json"
        },
        {
          "title": "Example data on error 400",
          "content": "{\n\"code\": \"error\",\n\"type\": \"notification\",\n\"description\": \"Invalid parameters. The FPGA could not start playing a capture.\"\n}\n",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "field": "code",
            "optional": false,
            "description": "<p>Return code (&#39;success&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "type",
            "optional": false,
            "description": "<p>Return type (&#39;notification&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "description",
            "optional": false,
            "description": "<p>Description of the success code</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Example data on success",
          "content": "{\n\"code\": \"success\",\n\"type\": \"notification\",\n\"description\": \"The FPGA has started playing a capture.\"\n}\n",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "fpga-api/api-docs/apiDoc.js"
  },
  {
    "type": "post",
    "url": "/player/start/loop/:capturename/:mask/:ifg",
    "title": "/player/start/loop/",
    "description": "<p>Reproduces a capture in loop</p>",
    "name": "PlayerStartLoop",
    "group": "Manager",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "Number",
            "field": "timestamp",
            "optional": false,
            "description": "<p>Milliseconds elapsed since 1 January 1970 00:00:00 UTC until now (output of Date.now())</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "field": "capturename",
            "optional": false,
            "description": "<p>Name of the capture to be reproduced</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "field": "mask",
            "optional": false,
            "description": "<p>Mask (set of ports) where the capture is going to be reproduced (0-1-2-3)</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "field": "ifg",
            "optional": false,
            "description": "<p>Interframe gap (0 to original captured rate)</p>"
          }
        ]
      }
    },
    "error": {
      "fields": {
        "Error 412": [
          {
            "group": "Error 412",
            "type": "String",
            "field": "code",
            "optional": false,
            "description": "<p>Return code (&#39;error&#39;)</p>"
          },
          {
            "group": "Error 412",
            "type": "String",
            "field": "type",
            "optional": false,
            "description": "<p>Return type (&#39;fpga_invalid_state&#39;)</p>"
          },
          {
            "group": "Error 412",
            "type": "String",
            "field": "description",
            "optional": false,
            "description": "<p>Description of the error</p>"
          }
        ],
        "Error 400": [
          {
            "group": "Error 400",
            "type": "String",
            "field": "code",
            "optional": false,
            "description": "<p>Return code (&#39;error&#39;)</p>"
          },
          {
            "group": "Error 400",
            "type": "String",
            "field": "type",
            "optional": false,
            "description": "<p>Return type (&#39;notification&#39;)</p>"
          },
          {
            "group": "Error 400",
            "type": "String",
            "field": "description",
            "optional": false,
            "description": "<p>Description of the error</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Example data on error 412",
          "content": "{\n\"code\": \"error\",\n\"type\": \"fpga_invalid_state\",\n\"description\": \"Invalid State. The FPGA is not programmed and mounted as a player.\"\n}\n",
          "type": "json"
        },
        {
          "title": "Example data on error 400",
          "content": "{\n\"code\": \"error\",\n\"type\": \"notification\",\n\"description\": \"Invalid parameters. The FPGA could not start playing a capture.\"\n}\n",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "field": "code",
            "optional": false,
            "description": "<p>Return code (&#39;success&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "type",
            "optional": false,
            "description": "<p>Return type (&#39;notification&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "description",
            "optional": false,
            "description": "<p>Description of the success code</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Example data on success",
          "content": "{\n\"code\": \"success\",\n\"type\": \"notification\",\n\"description\": \"The FPGA has started playing a capture.\"\n}\n",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "fpga-api/api-docs/apiDoc.js"
  },
  {
    "type": "post",
    "url": "/player/stop",
    "title": "/player/stop",
    "description": "<p>Stops the player</p>",
    "name": "PlayerStop",
    "group": "Manager",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "Number",
            "field": "timestamp",
            "optional": false,
            "description": "<p>Milliseconds elapsed since 1 January 1970 00:00:00 UTC until now (output of Date.now())</p>"
          }
        ]
      }
    },
    "error": {
      "fields": {
        "Error 412": [
          {
            "group": "Error 412",
            "type": "String",
            "field": "code",
            "optional": false,
            "description": "<p>Return code (&#39;error&#39;)</p>"
          },
          {
            "group": "Error 412",
            "type": "String",
            "field": "type",
            "optional": false,
            "description": "<p>Return type (&#39;fpga_invalid_state&#39;)</p>"
          },
          {
            "group": "Error 412",
            "type": "String",
            "field": "description",
            "optional": false,
            "description": "<p>Description of the error</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Example data on error 412",
          "content": "{\n\"code\": \"error\",\n\"type\": \"fpga_invalid_state\",\n\"description\": \"Invalid State. The FPGA is not playing a capture.\"\n}\n",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "field": "code",
            "optional": false,
            "description": "<p>Return code (&#39;success&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "type",
            "optional": false,
            "description": "<p>Return type (&#39;notification&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "description",
            "optional": false,
            "description": "<p>Description of the success code</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Example data on success",
          "content": "{\n\"code\": \"success\",\n\"type\": \"notification\",\n\"description\": \"The FPGA has stopped playing a capture.\"\n}\n",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "fpga-api/api-docs/apiDoc.js"
  },
  {
    "type": "post",
    "url": "/recorder/init",
    "title": "/recorder/init",
    "description": "<p>Programs the FPGA as a recorder and reboots the system</p>",
    "name": "RecorderInit",
    "group": "Manager",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "Number",
            "field": "timestamp",
            "optional": false,
            "description": "<p>Milliseconds elapsed since 1 January 1970 00:00:00 UTC until now (output of Date.now())</p>"
          }
        ]
      }
    },
    "error": {
      "fields": {
        "Error 412": [
          {
            "group": "Error 412",
            "type": "String",
            "field": "code",
            "optional": false,
            "description": "<p>Return code (&#39;error&#39;)</p>"
          },
          {
            "group": "Error 412",
            "type": "String",
            "field": "type",
            "optional": false,
            "description": "<p>Return type (&#39;fpga_invalid_state&#39;)</p>"
          },
          {
            "group": "Error 412",
            "type": "String",
            "field": "description",
            "optional": false,
            "description": "<p>Description of the error</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Example data on error 412",
          "content": "{\n\"code\": \"error\",\n\"type\": \"fpga_invalid_state\",\n\"description\": \"Invalid State. The FPGA is already running, stop it to init the FPGA.\"\n}\n",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "field": "code",
            "optional": false,
            "description": "<p>Return code (&#39;success&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "type",
            "optional": false,
            "description": "<p>Return type (&#39;notification&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "description",
            "optional": false,
            "description": "<p>Description of the success code</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Example data on success",
          "content": "{\n\"code\": \"success\",\n\"type\": \"notification\",\n\"description\": \"The FPGA has been initialized. The host will reboot now.\"\n}\n",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "fpga-api/api-docs/apiDoc.js"
  },
  {
    "type": "post",
    "url": "/recorder/install",
    "title": "/recorder/install",
    "description": "<p>Installs the driver and mounts the FPGA as a recorder</p>",
    "name": "RecorderInstall",
    "group": "Manager",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "Number",
            "field": "timestamp",
            "optional": false,
            "description": "<p>Milliseconds elapsed since 1 January 1970 00:00:00 UTC until now (output of Date.now())</p>"
          }
        ]
      }
    },
    "error": {
      "fields": {
        "Error 412": [
          {
            "group": "Error 412",
            "type": "String",
            "field": "code",
            "optional": false,
            "description": "<p>Return code (&#39;error&#39;)</p>"
          },
          {
            "group": "Error 412",
            "type": "String",
            "field": "type",
            "optional": false,
            "description": "<p>Return type (&#39;fpga_invalid_state&#39;)</p>"
          },
          {
            "group": "Error 412",
            "type": "String",
            "field": "description",
            "optional": false,
            "description": "<p>Description of the error</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Example data on error 412",
          "content": "{\n\"code\": \"error\",\n\"type\": \"fpga_invalid_state\",\n\"description\": \"Invalid State. The FPGA must be programmed before mounted.\"\n}\n",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "field": "code",
            "optional": false,
            "description": "<p>Return code (&#39;success&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "type",
            "optional": false,
            "description": "<p>Return type (&#39;notification&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "description",
            "optional": false,
            "description": "<p>Description of the success code</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Example data on success",
          "content": "{\n\"code\": \"success\",\n\"type\": \"notification\",\n\"description\": \"The FPGA has been mounted and is ready to be used.\"\n}\n",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "fpga-api/api-docs/apiDoc.js"
  },
  {
    "type": "post",
    "url": "/recorder/start/:capturename/:port/:bytes",
    "title": "/recorder/start/",
    "description": "<p>Records a capture</p>",
    "name": "RecorderStart",
    "group": "Manager",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "Number",
            "field": "timestamp",
            "optional": false,
            "description": "<p>Milliseconds elapsed since 1 January 1970 00:00:00 UTC until now (output of Date.now())</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "field": "capturename",
            "optional": false,
            "description": "<p>Name the recorded capture will have</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "field": "port",
            "optional": false,
            "description": "<p>Port the FPGA will be capturing from (0-1-2-3)</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "field": "bytes",
            "optional": false,
            "description": "<p>Bytes the FPGA will capture</p>"
          }
        ]
      }
    },
    "error": {
      "fields": {
        "Error 412": [
          {
            "group": "Error 412",
            "type": "String",
            "field": "code",
            "optional": false,
            "description": "<p>Return code (&#39;error&#39;)</p>"
          },
          {
            "group": "Error 412",
            "type": "String",
            "field": "type",
            "optional": false,
            "description": "<p>Return type (&#39;fpga_invalid_state&#39;)</p>"
          },
          {
            "group": "Error 412",
            "type": "String",
            "field": "description",
            "optional": false,
            "description": "<p>Description of the error</p>"
          }
        ],
        "Error 400": [
          {
            "group": "Error 400",
            "type": "String",
            "field": "code",
            "optional": false,
            "description": "<p>Return code (&#39;error&#39;)</p>"
          },
          {
            "group": "Error 400",
            "type": "String",
            "field": "type",
            "optional": false,
            "description": "<p>Return type (&#39;notification&#39;)</p>"
          },
          {
            "group": "Error 400",
            "type": "String",
            "field": "description",
            "optional": false,
            "description": "<p>Description of the error</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Example data on error 412",
          "content": "{\n\"code\": \"error\",\n\"type\": \"fpga_invalid_state\",\n\"description\": \"Invalid State. The FPGA is not programmed and mounted as a recorder.\"\n}\n",
          "type": "json"
        },
        {
          "title": "Example data on error 400",
          "content": "{\n\"code\": \"error\",\n\"type\": \"notification\",\n\"description\": \"Invalid capture name (must not exist).\"\n}\n",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "field": "code",
            "optional": false,
            "description": "<p>Return code (&#39;success&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "type",
            "optional": false,
            "description": "<p>Return type (&#39;notification&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "description",
            "optional": false,
            "description": "<p>Description of the success code</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Example data on success",
          "content": "{\n\"code\": \"success\",\n\"type\": \"notification\",\n\"description\": \"The FPGA has started recording data.\"\n}\n",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "fpga-api/api-docs/apiDoc.js"
  },
  {
    "type": "post",
    "url": "/recorder/stop",
    "title": "/recorder/stop",
    "description": "<p>Stops the recorder</p>",
    "name": "RecorderStop",
    "group": "Manager",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "Number",
            "field": "timestamp",
            "optional": false,
            "description": "<p>Milliseconds elapsed since 1 January 1970 00:00:00 UTC until now (output of Date.now())</p>"
          }
        ]
      }
    },
    "error": {
      "fields": {
        "Error 412": [
          {
            "group": "Error 412",
            "type": "String",
            "field": "code",
            "optional": false,
            "description": "<p>Return code (&#39;error&#39;)</p>"
          },
          {
            "group": "Error 412",
            "type": "String",
            "field": "type",
            "optional": false,
            "description": "<p>Return type (&#39;fpga_invalid_state&#39;)</p>"
          },
          {
            "group": "Error 412",
            "type": "String",
            "field": "description",
            "optional": false,
            "description": "<p>Description of the error</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Example data on error 412",
          "content": "{\n\"code\": \"error\",\n\"type\": \"fpga_invalid_state\",\n\"description\": \"Invalid State. The FPGA is not recording data.\"\n}\n",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "field": "code",
            "optional": false,
            "description": "<p>Return code (&#39;success&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "type",
            "optional": false,
            "description": "<p>Return type (&#39;notification&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "description",
            "optional": false,
            "description": "<p>Description of the success code</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Example data on success",
          "content": "{\n\"code\": \"success\",\n\"type\": \"notification\",\n\"description\": \"The FPGA has stopped recording data.\"\n}\n",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "fpga-api/api-docs/apiDoc.js"
  },
  {
    "type": "delete",
    "url": "/storage/raid",
    "title": "/storage/raid",
    "description": "<p>Deletes (format and reset) the storage raid</p>",
    "name": "StorageRaid",
    "group": "Manager",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "Number",
            "field": "timestamp",
            "optional": false,
            "description": "<p>Milliseconds elapsed since 1 January 1970 00:00:00 UTC until now (output of Date.now())</p>"
          }
        ]
      }
    },
    "error": {
      "fields": {
        "Error 412": [
          {
            "group": "Error 412",
            "type": "String",
            "field": "code",
            "optional": false,
            "description": "<p>Return code (&#39;error&#39;)</p>"
          },
          {
            "group": "Error 412",
            "type": "String",
            "field": "type",
            "optional": false,
            "description": "<p>Return type (&#39;notification&#39;)</p>"
          },
          {
            "group": "Error 412",
            "type": "String",
            "field": "description",
            "optional": false,
            "description": "<p>Description of the error</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Example data on error 412",
          "content": "{\n\"code\": \"error\",\n\"type\": \"notification\",\n\"description\": \"The RAID could not be formatted, RAID configuration option is not set or the FPGA is running.\"\n}\n",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "field": "code",
            "optional": false,
            "description": "<p>Return code (&#39;success&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "type",
            "optional": false,
            "description": "<p>Return type (&#39;notification&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "description",
            "optional": false,
            "description": "<p>Description of the success code</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Example data on success",
          "content": "{\n\"code\": \"success\",\n\"type\": \"notification\",\n\"description\": \"The RAID has been formatted and mounted properly.\"\n}\n",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "fpga-api/api-docs/apiDoc.js"
  },
  {
    "type": "post",
    "url": "/system/reboot",
    "title": "/system/reboot",
    "description": "<p>Reboot the FPGA Web Service server</p>",
    "name": "SystemReboot",
    "group": "Manager",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "Number",
            "field": "timestamp",
            "optional": false,
            "description": "<p>Milliseconds elapsed since 1 January 1970 00:00:00 UTC until now (output of Date.now())</p>"
          }
        ]
      }
    },
    "error": {
      "fields": {
        "Error 412": [
          {
            "group": "Error 412",
            "type": "String",
            "field": "code",
            "optional": false,
            "description": "<p>Return code (&#39;error&#39;)</p>"
          },
          {
            "group": "Error 412",
            "type": "String",
            "field": "type",
            "optional": false,
            "description": "<p>Return type (&#39;notification&#39;)</p>"
          },
          {
            "group": "Error 412",
            "type": "String",
            "field": "description",
            "optional": false,
            "description": "<p>Description of the error</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Example data on error 412",
          "content": "{\n\"code\": \"error\",\n\"type\": \"notification\",\n\"description\": \"The host can not be rebooted. The FPGA is being used.\"\n}\n",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "field": "code",
            "optional": false,
            "description": "<p>Return code (&#39;success&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "type",
            "optional": false,
            "description": "<p>Return type (&#39;notification&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "description",
            "optional": false,
            "description": "<p>Description of the success code</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Example data on success",
          "content": "{\n\"code\": \"success\",\n\"type\": \"notification\",\n\"description\": \"The host is rebooting now.\"\n}\n",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "fpga-api/api-docs/apiDoc.js"
  },
  {
    "type": "get",
    "url": "/info/delay",
    "title": "/info/delay",
    "description": "<p>Request the seconds of delay between the client and the server (of timestamps)</p>",
    "name": "InfoDelay",
    "group": "Statistics",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "Number",
            "field": "timestamp",
            "optional": false,
            "description": "<p>Milliseconds elapsed since 1 January 1970 00:00:00 UTC until now (output of Date.now())</p>"
          }
        ]
      }
    },
    "error": {
      "fields": {
        "Error 400": [
          {
            "group": "Error 400",
            "field": "TimestampNotFound",
            "optional": false,
            "description": "<p>Timestamp is not set in the request&#39;s header</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "field": "code",
            "optional": false,
            "description": "<p>Return code (&#39;success&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "type",
            "optional": false,
            "description": "<p>Return type (&#39;data&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "field": "delay",
            "optional": false,
            "description": "<p>Delay in seconds between the caller and the FPGA Web Service</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "field": "maxDelay",
            "optional": false,
            "description": "<p>Maximum delay allowed in seconds between the caller and the FPGA Web Service</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Example data on success",
          "content": "{\n\"code\": \"success\",\n\"type\": \"data\",\n\"delay\": 1,\n\"maxDelay\": 30\n}\n",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "fpga-api/api-docs/apiDoc.js"
  },
  {
    "type": "get",
    "url": "/info/ping",
    "title": "/info/ping",
    "description": "<p>Simple request to test if the server is up</p>",
    "name": "InfoPing",
    "group": "Statistics",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "field": "code",
            "optional": false,
            "description": "<p>Return code (&#39;success&#39;)</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Example data on success",
          "content": "{\n\"code\": \"success\"\n}\n",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "fpga-api/api-docs/apiDoc.js"
  },
  {
    "type": "get",
    "url": "/info/status",
    "title": "/info/status",
    "description": "<p>Request the status of the FPGA</p>",
    "name": "InfoStatus",
    "group": "Statistics",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "field": "status",
            "optional": false,
            "description": "<p>Current status of the FPGA. If the status is either &#39;playing&#39; or &#39;recording&#39;, additional data is returned (see examples below)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "description",
            "optional": false,
            "description": "<p>Description of the current status of the FPGA</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "capture",
            "optional": false,
            "description": "<p>[Only when status is &#39;playing&#39;]: name of the capture being reproduced</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "field": "size",
            "optional": false,
            "description": "<p>[Only when status is &#39;playing&#39;]: size of the capture being reproduced (in bytes)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "date",
            "optional": false,
            "description": "<p>[Only when status is &#39;playing&#39;]: date of the capture being reproduced (yyyy-mm-dd hh:mm:ss)</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "field": "elapsed_time",
            "optional": false,
            "description": "<p>[Only when status is &#39;playing&#39;]: time since the capture started being reproduced (in seconds)</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "field": "packets_sent",
            "optional": false,
            "description": "<p>[Only when status is &#39;playing&#39;]: packets sent</p>"
          },
          {
            "group": "Success 200",
            "type": "Boolean",
            "field": "loop",
            "optional": false,
            "description": "<p>[Only when status is &#39;playing&#39;]: true if the capture is being reproduced in loop, false otherwise</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "field": "interframe_gap",
            "optional": false,
            "description": "<p>[Only when status is &#39;playing&#39;]: interframe gap the capture is being reproduced at (0 means original captured rate)</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "field": "mask",
            "optional": false,
            "description": "<p>[Only when status is &#39;playing&#39;]: set of ports where the capture is being reproduced (0-1-2-3)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "bytes_captured",
            "optional": false,
            "description": "<p>[Only when status is &#39;recording&#39;]: bytes captured until now</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "bytes_total",
            "optional": false,
            "description": "<p>[Only when status is &#39;recording&#39;]: bytes to be recorded in total</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "port",
            "optional": false,
            "description": "<p>[Only when status is &#39;recording&#39;]: port the FPGA is capturing from (0-1-2-3)</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "HugePages Off",
          "content": "{\n \"status\": \"hugepages_off\",\n \"description\": \"HugePages is not enabled. To fix this, the host should be rebooted with this option selected on the GRUB menu.\"\n}\n",
          "type": "json"
        },
        {
          "title": "Init Off",
          "content": "{\n \"status\": \"init_off\",\n \"description\": \"The FPGA is not configured either as player or as recorder yet.\"\n}\n",
          "type": "json"
        },
        {
          "title": "Mount Off",
          "content": "{\n \"status\": \"mount_off\",\n \"description\": \"The FPGA is initialized but not mounted.\"\n}\n",
          "type": "json"
        },
        {
          "title": "Player Ready",
          "content": "{\n \"status\": \"player_ready\",\n \"description\": \"The FPGA is ready to reproduce a capture.\"\n}\n",
          "type": "json"
        },
        {
          "title": "Recorder Ready",
          "content": "{\n \"status\": \"recorder_ready\",\n \"description\": \"The FPGA is ready to record a capture.\"\n}\n",
          "type": "json"
        },
        {
          "title": "Playing",
          "content": "{\n \"status\": \"playing\",\n \"description\": \"The FPGA is reproducing a capture.\",\n \"capture\": \"my_capture0.simple\",\n \"size\": 714131923845,\n \"date\": \"2014-09-29 15:40:34\",\n \"elapsed_time\": 548,\n \"packets_sent\": 394578123,\n \"loop\": true,\n \"interframe_gap\": 0,\n \"mask\": 3\n}\n",
          "type": "json"
        },
        {
          "title": "Recording",
          "content": "{\n \"status\": \"recording\",\n \"description\": \"The FPGA is recording a capture.\",\n \"capture\": \"2flows_test\",\n \"elapsed_time\": 447,\n \"bytes_captured\": 5984234711238,\n \"bytes_total\": 234856352341724128,\n \"port\": 2\n}\n",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "fpga-api/api-docs/apiDoc.js"
  },
  {
    "type": "get",
    "url": "/storage/stats",
    "title": "/storage/stats",
    "description": "<p>Request statistics of the storage (disk space, write speed, RAID)</p>",
    "name": "StorageStats",
    "group": "Statistics",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "field": "code",
            "optional": false,
            "description": "<p>Return code (&#39;success&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "type",
            "optional": false,
            "description": "<p>Return type (&#39;data&#39;)</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "field": "total_space",
            "optional": false,
            "description": "<p>Total space in the device that stores the captures (in bytes)</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "field": "used_space",
            "optional": false,
            "description": "<p>Used space in the device that stores the captures (in bytes)</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "field": "raid_stats",
            "optional": false,
            "description": "<p>RAID statistics</p>"
          },
          {
            "group": "Success 200",
            "type": "Boolean",
            "field": "raid_stats.raid_active",
            "optional": false,
            "description": "<p>Flag to see if the RAID is being used for store captures. The rest of the fields below are only set if <code>raid_active</code> is <code>true</code></p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "raid_stats.raid_name",
            "optional": false,
            "description": "<p>Name of the RAID</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "field": "raid_stats.write_speed",
            "optional": false,
            "description": "<p>Write speed of the RAID (in bytes/second)</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "field": "raid_stats.disks",
            "optional": false,
            "description": "<p>Array of statistics for each disk of the RAID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "field": "raid_stats.disks.name",
            "optional": false,
            "description": "<p>Disk name</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "field": "raid_stats.disks.write_speed",
            "optional": false,
            "description": "<p>Write speed of the disk (in bytes/second)</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Example data on success",
          "content": "{\n \"code\": \"success\",\n \"type\": \"data\",\n \"total_space\": 240972104,\n \"used_space\": 70828412,\n \"raid_stats\": {\n   \"raid_active\": true,\n   \"raid_name\": \"/dev/md0\",\n   \"write_speed\": 4051114978890,\n   \"disks\": [\n     {\n       \"name\": \"/dev/sdc\",\n       \"write_speed\": 15435231341\n     },\n     {\n       \"name\": \"/dev/sdd\",\n       \"write_speed\": 32112351239\n     },\n     {\n       \"name\": \"/dev/sde\",\n       \"write_speed\": 19123843109\n     }\n   ]\n }\n}\n",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "fpga-api/api-docs/apiDoc.js"
  }
] });