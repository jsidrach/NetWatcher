# NetWatcher

NetWatcher is a web-based interface to manage network traffic capturer FPGAs, developed as an End-of-Degree Project in collaboration with the [High Performance Computing and Networking](http://www.hpcn.es/) research group. NetWatcher is divided in two parts: a FPGA Web Service to execute commands and monitor them in the FPGA, and a Web Interface to visually make those calls.

## Table of contents

- [Version](#version)
- [License](#license)
- [Tech](#tech)
- [Installation](#installation)
     - [FPGA Web Service](#fpga-web-service)
     - [Web Interface](#web-interface)
- [Documentation](#documentation)
- [Bugs and feature requests](#bugs-and-feature-requests)


Version
----
0.1 - Design Prototype ([changelog](changelog.md))


License
----
Code released under the [MIT license](LICENSE.md). Docs released under Creative Commons. FPGA binary drivers are property of [High Performance Computing and Networking](http://www.hpcn.es/).

Tech
----

NetWatcher uses a number of open source projects to work properly:

* [Twitter Bootstrap](https://twitter.github.com/bootstrap/index.html) - great UI boilerplate for modern web apps
    * [Bootswatch](http://bootswatch.com/) - themes for Bootstrap
    * [Bootstrap Growl](https://github.com/ifightcrime/bootstrap-growl) - notifications
* [io.js](https://iojs.org/) - evented I/O for the backend server
* [Express](http://expressjs.com/) - web framework for io.js
* [Supervisor](https://github.com/isaacs/node-supervisor) - supervisor and hot-code reloader
* [jQuery](https://jquery.com) - JavaScript library
* [Composer](https://getcomposer.org) - PHP library for external dependencies

In addition, a full list of references used can be found [here](REFERENCES.md).

Installation
----
**Prerequisite**: the FPGA service and the apache server must be in the same local network.

#### FPGA Web Service
1. **Prerequisites**: The host must have installed everything necessary to make the FPGA traffic capturer/recorder work properly (on a linux-x64 OS). In addition, HugePages must be the default selected option in the GRUB menu, in case there are options available to boot without HugePages active
2. Edit the file `./fpga_api/scripts/update_server.sh` setting the `SERVER_IP` and `USER` vars. **Note**: selected user must exist and have superuser rights in the remote server
3. Change path to `./fpga_api/`
4. Deploy the io.js server on the remote host

        $ ./scripts/update_server.sh
5. Log into the FPGA Web Service host
6. Configure the server (if needed) by editing the `config.js` file
7. Start the service

        $ sudo service fpga_api start

#### Web Interface
1. **Prerequisites**: apache http server installed, with mod_rewrite support enabled
2. Download the repository and extract it (inside a PHP server). Change directory to the repository folder
3. Install required packages

        $ ./scripts/install_dependencies.sh
4. Connect the web interface with the FPGA Web Service visiting the settings page on your browser and editing the IP

If you run into any issue, visit the [troubleshooting page](https://github.com/JSidrach/NetWatcher/wiki/Troubleshooting).

Documentation
----
NetWatcher's web interface documentation is built with [phpDocumentor](https://www.phpdoc.org) and included in the [docs/front-end/](docs/front-end/) folder as a webpage. The FPGA Web Service documentation is available on [docs/back-end/](docs/back-end/). Further documentation about the project architecture and additional reading can be found in the [project's wiki](https://github.com/JSidrach/NetWatcher/wiki).

Bugs and feature requests
----
New features can be requested opening a GitHub Issue. If you have found a bug and you have a *tested* fix for it, feel encouraged to submit a pull request.
