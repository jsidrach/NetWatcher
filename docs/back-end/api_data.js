define({ api: [
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
            "description": "<p>Milliseconds elapsed since 1 January 1970 00:00:00 UTC up until now (output of Date.now())</p>"
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
          "title": "Example data on success:",
          "content": "{\n\"code\": \"success\",\n\"delay\": 1,\n\"maxDelay\": 30\n}\n",
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
          "title": "Example data on success:",
          "content": "{\n\"code\": \"success\"\n}\n",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "fpga-api/api-docs/apiDoc.js"
  }
] });