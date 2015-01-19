# [NAME]

[NAME] is a web-based interface to manage network traffic capturer FPGAs.

Version
----
0.1 - Design


License
----

MIT


Tech
----

[NAME] uses a number of open source projects to work properly:

* [Twitter Bootstrap](http://twitter.github.com/bootstrap/index.html) - great UI boilerplate for modern web apps
    * [Bootswatch](http://bootswatch.com/) - themes for Bootstrap
    * [Bootstrap Growl](https://github.com/ifightcrime/bootstrap-growl) - notifications
* [io.js](https://iojs.org) - evented I/O for the backend server
* [jQuery](https://jquery.com) - JavaScript library
* [Composer](https://getcomposer.org) - PHP library for external dependencies
* [phpDocumentor](https://www.phpdoc.org) - documentation generator

Installation
----
* Download the repository and extract it. Change directory to the repository folder.
* Install packages and active them
```sh
$ ./scripts/build.sh --install
```

* Check Gettext
    * phpinfo(): GetText Support enabled
    * Status page

* Install libraries and dependencies
```sh
$ ./scripts/upgrade.sh
```

* Translations (implementing more languages)
    * Edit ./locale/ files with a PO file editor, ex: [POEdit](https://poedit.net)

Other Scripts
----

* Generate Documentation
```sh
$ ./scripts/gen_doc.sh
```

* Check PHP files syntax
```sh
$ ./scripts/check_php.sh
```


* Fix permissions
```sh
$ ./scripts/do_chmod.sh
```

Tools for Programming
----
[PHP Code - Eclipse](http://www.eclipse.org/pdt/)

Useful Commands
----
Start/Stop Apache
```sh
$ sudo service apache2 stop
$ sudo service apache2 start
```
