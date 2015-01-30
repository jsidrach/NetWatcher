# NetWatcher

NetWatcher is a web-based interface to manage network traffic capturer FPGAs, developed as an End-of-Degree Project in collaboration with the [High Performance Computing and Networking](http://www.hpcn.es/) research group. The project has been released for educational purposes, since no new features are going to be added.

## Table of contents

- [Version](#version)
- [License](#license)
- [Tech](#tech)
- [Installation](#installation)
- [Documentation](#documentation)
- [Bugs and feature requests](#bugs-and-feature-requests)
- [Other Scripts](#other-scripts)


Version
----
0.1 - Design Prototype ([changelog](changelog.md))


License
----
Code released under the [MIT license](LICENSE). Docs released under Creative Commons.

Tech
----

NetWatcher uses a number of open source projects to work properly:

* [Twitter Bootstrap](http://twitter.github.com/bootstrap/index.html) - great UI boilerplate for modern web apps
    * [Bootswatch](http://bootswatch.com/) - themes for Bootstrap
    * [Bootstrap Growl](https://github.com/ifightcrime/bootstrap-growl) - notifications
* [node.js](http://nodejs.org/) - evented I/O for the backend server
* [Express](http://expressjs.com/) - web framework for node.js
* [jQuery](https://jquery.com) - JavaScript library
* [Composer](https://getcomposer.org) - PHP library for external dependencies

Installation
----
* Download the repository and extract it. Change directory to the repository folder.
* Install required packages
```sh
$ ./scripts/install_dependencies.sh
```

* Translations (implementing more languages)
    * Edit ./locale/ files with a PO file editor, ex: [POEdit](https://poedit.net)

Documentation
----
NetWatcher's documentation is built with [phpDocumentor](https://www.phpdoc.org) and included in the [docs/api/](docs/api/) folder as a webpage. Further documentation about the project architecture and additional reading can be found in the [docs/](docs/) folder too.

Bugs and feature requests
----
Although no new features are going to be added, if you have found a bug and you have a *tested* fix for it, you can submit a pull request.

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
    * Open /etc/apache2/ports.conf, change `Listen 80` to `Listen 127.0.0.1:80`
