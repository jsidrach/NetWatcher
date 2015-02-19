# NetWatcher

NetWatcher is a web-based interface to manage network traffic capturer FPGAs, developed as an End-of-Degree Project in collaboration with the [High Performance Computing and Networking](http://www.hpcn.es/) research group. NetWatcher is divided in two parts: a web interface to manage the FPGA, and a FPGA REST-based service to execute commands and monitor it in the FPGA host.

## Table of contents

- [Version](#version)
- [License](#license)
- [Tech](#tech)
- [Installation](#installation)
     - [FPGA REST Service](#fpga-rest-service)
     - [Web Interface](#web-interface)
- [Documentation](#documentation)
- [Bugs and feature requests](#bugs-and-feature-requests)
- [Other Scripts](#other-scripts)


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
* [Supervisor](https://github.com/isaacs/node-supervisor) - watches code changes and reloads them
* [jQuery](https://jquery.com) - JavaScript library
* [Composer](https://getcomposer.org) - PHP library for external dependencies

In addition, a full list of references used can be found [here](REFERENCES.md).

Installation
----
**Prerequisite**: the three parties involved (the FPGA service, the web interface and the client -browser) must be in the same local network.

#### FPGA REST Service
1. **Prerequisites**: The host must have installed everything necessary to make the FPGA traffic capturer/recorder work properly (on a linux-x64 OS). In addition, HugePages must be the default selected option in the GRUB menu, in case there are options available to boot without HugePages active.
2. Edit the file `./fpga_api/scripts/update_server.sh` setting the `SERVER_IP` and `USER` vars. **Note**: selected user must exist and have superuser rights in the remote server
3. Change path to `./fpga_api/`
4. Deploy the io.js server on the remote host

        $ ./scripts/update_server.sh
5. Start the service

        $ sudo service fpga_api start

#### Web Interface
1. **Prerequisites**: apache http server installed, with mod_rewrite support enabled.
2. Download the repository and extract it (inside a PHP server). Change directory to the repository folder.
3. Install required packages

        $ ./scripts/install_dependencies.sh

Documentation
----
NetWatcher's web interface documentation is built with [phpDocumentor](https://www.phpdoc.org) and included in the [docs/front-end/](docs/front-end/) folder as a webpage. The FPGA REST service documentation is built with [Swagger](http://swagger.io/) and available on [docs/back-end](docs/back-end). Further documentation about the project architecture and additional reading can be found in the [project's wiki](https://github.com/JSidrach/NetWatcher/wiki).

Bugs and feature requests
----
New features can be requested opening a GitHub Issue. If you have found a bug and you have a *tested* fix for it, you can submit a pull request.

Other Scripts
----

* Install required packages
```sh
$ ./scripts/install_dependencies.sh
```

* Install/Upgrade libraries and dependencies
```sh
$ ./scripts/upgrade.sh
```

* Generate Documentation
```sh
$ ./scripts/gen_doc.sh
```

* Update libraries and dependencies
```sh
$ ./lib/vendor/composer.phar update
```

* Check PHP files syntax
```sh
$ ./scripts/check_php.sh
```

* Fix permissions
```sh
$ ./scripts/do_chmod.sh
```

* Check Gettext
    * phpinfo(): GetText Support enabled
    * Status page
    
* Only localhost
    * Open `/etc/apache2/ports.conf`, change `Listen 80` to `Listen 127.0.0.1:80`

* Translations (implementing more languages)
    * Edit ./locale/ files with a PO file editor, ex: [POEdit](https://poedit.net)
    * Restart apache
