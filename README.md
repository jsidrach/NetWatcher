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
Code released under the [MIT license](LICENSE.md). Docs released under Creative Commons. Driver binaries of the FPGA are property of [High Performance Computing and Networking](http://www.hpcn.es/).

Tech
----

NetWatcher uses a number of open source projects to work properly:

* [Twitter Bootstrap](http://twitter.github.com/bootstrap/index.html) - great UI boilerplate for modern web apps
    * [Bootswatch](http://bootswatch.com/) - themes for Bootstrap
    * [Bootstrap Growl](https://github.com/ifightcrime/bootstrap-growl) - notifications
* [io.js](http://iojs.org/) - evented I/O for the backend server
* [Express](http://expressjs.com/) - web framework for io.js
* [jQuery](https://jquery.com) - JavaScript library
* [Composer](https://getcomposer.org) - PHP library for external dependencies

In addition, a full list of references used can be found [here](REFERENCES.md).

Installation
----
#### FPGA REST Service
* Edit `./fpga_server/scripts/update_server.sh` and set the SERVER_IP and USER. *NOTE*: selected user must exist and have superuser rights in the remote server
* Change path to `./fpga_server/`
* Run the update server script:
```sh
$ ./scripts/update_server.sh
```

#### Web Interface
* Download the repository and extract it (inside a PHP server). Change directory to the repository folder.
* Install required packages
```sh
$ ./scripts/install_dependencies.sh
```

Documentation
----
NetWatcher's web interface documentation is built with [phpDocumentor](https://www.phpdoc.org) and included in the [docs/front-end/](docs/front-end/) folder as a webpage. The FPGA REST service documentation is built with [Swagger](http://swagger.io/) and available on [docs/back-end](docs/back-end). Further documentation about the project architecture and additional reading can be found in the [project's wiki](wiki/).

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
