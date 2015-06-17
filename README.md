# NetWatcher

NetWatcher is a web-based interface to manage FPGA-based network probes (devices capable of capturing or injecting network traffic). The project also brings together other relevant aspects of the capture and injection of web traffic, such as trace storage management, write speed of the used disks or trace format detection and conversion. NetWatcher is divided in two parts: a (REST-like) web service to execute commands in the FPGA and monitor it, and a graphical web interface to connect to this service, make calls to it visually and display the results. It was developed as an End-of-Degree Project in collaboration with the [High-Performance Computing and Networking](http://www.hpcn.es/) research group.

## Table of contents

- [License](#license)
- [Tech](#tech)
- [Installation](#installation)
     - [FPGA Web Service](#fpga-web-service)
     - [Web Interface](#web-interface)
- [Documentation](#documentation)
- [Bugs and feature requests](#bugs-and-feature-requests)


License
----
Code released under the [MIT license](LICENSE.md). Docs are released under a [Creative Commons Attribution 4.0 International License](http://creativecommons.org/licenses/by/4.0/). FPGA binary drivers are property of [High-Performance Computing and Networking](http://www.hpcn.es/).

Tech
----

NetWatcher uses a number of open source projects to work properly:

* [Bootstrap](http://getbootstrap.com/) - great UI boilerplate for modern web apps
    * [Bootswatch](http://bootswatch.com/) - themes for Bootstrap
    * [Notify](https://github.com/mouse0270/bootstrap-notify) - notifications
    * [Chart.js](http://www.chartjs.org/) - charts and data visualization
    * [animate.css](http://daneden.github.io/animate.css/) - CSS animations
* [node.js](https://nodejs.org/) - evented I/O for the backend server
    * [Express](http://expressjs.com/) - web framework for io.js
    * [Async](https://github.com/caolan/async) - functions for working with asynchronous JavaScript
    * [nodemon](http://nodemon.io/) - monitor for any changes in the app automatically restart the server
* [jQuery](https://jquery.com) - JavaScript library
* [Composer](https://getcomposer.org) - PHP library for back-end external dependencies
    * [BowerPHP](http://bowerphp.org/) - a PHP implementation of Bower (for front-end dependencies)

The FPGA design and implementation has been developed by [jfzazo](https://github.com/jfzazo). In addition, a full list of references used can be found [here](docs/wiki/References.md).

Installation
----
**Prerequisite**: the FPGA service and the apache server must be in the same local network.

#### FPGA Web Service
1. **Prerequisites**: The host must have installed everything necessary to make the FPGA traffic capturer/recorder work properly (on a linux-x64 OS). In addition, HugePages must be the default selected option in the GRUB menu, in case there are options available to boot without HugePages active
2. Edit the file `./fpga-api/scripts/update_server.sh` setting the `SERVER_IP` and `SERVER_PATH` vars. **Note**: root user must exist.+
3. Change path to `./fpga-api/`
4. Copy the appropiate binaries to `./fpga-api/bin/` (you can use the ones in `bin.dagda/` or `bin.berilio` if they fit in your linux kernel)
5. Deploy the io.js server on the remote host

        $ ./scripts/update_server.sh
6. Log into the FPGA Web Service host
7. Configure the server (if needed) by editing the `config.js` file ([detailed explanation here](docs/wiki/FPGA_Configuration.md)).
8. Start the service

        $ sudo service fpga-api start

#### Web Interface
1. **Prerequisites**: apache http server installed, with mod_rewrite support enabled
2. Download the repository and extract it (inside a PHP server). Change directory to the repository folder
3. Install required packages and libraries

        $ sudo ./scripts/build.sh --install
4. Connect the web interface with the FPGA Web Service visiting the settings page on your browser and editing the IP

If you run into any issue, visit the [troubleshooting page](docs/wiki/Troubleshooting.md).

Documentation
----
NetWatcher's web interface documentation is built with [phpDocumentor](https://www.phpdoc.org) and included in the [docs/front-end/](docs/front-end/) folder as a webpage. The FPGA Web Service documentation is build with [apiDoc](http://apidocjs.com/) and included in the [docs/back-end/](docs/back-end/) folder as a webpage too. To explore the available calls, it is also possible to import the file `fpga-api/api-docs/fpga-api.json.postman_collection` into [Postman](http://www.getpostman.com). Further documentation about the project architecture and additional reading can be found in the [project's docs](docs/).

Bugs and feature requests
----
New features can be requested opening a GitHub Issue. If you have found a bug and you have a *tested* fix for it, feel encouraged to submit a pull request.
