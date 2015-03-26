Below is a list of useful scripts:

## Web Interface

#### Check PHP files syntax
```sh
$ ./scripts/check_php.sh
```

#### Fix permissions
```sh
$ ./scripts/do_chmod.sh
```

#### Generate PHP Documentation
```sh
$ ./scripts/gen_doc.sh
```

#### Install required packages
```sh
$ ./scripts/install_dependencies.sh
```

#### Install/Upgrade libraries and components
```sh
$ ./scripts/upgrade.sh
```


## FPGA Web Service

#### Fix permissions
On the FPGA Web Service host
```sh
$ ./fpga-api/scripts/do_chmod.sh
```

#### Install dependencies
On the FPGA Web Service host
```sh
$ ./fpga-api/scripts/do_chmod.sh
```

#### Update server remotely
Modify the `./fpga-api/scripts/update_server.sh` and set the configuration (`SERVER_IP`, `SERVER_PATH`, `USER`). Run
```sh
$ ./fpga-api/scripts/do_chmod.sh
```

#### Manage the FPGA Web Service
Once installed, use it as a regular service
```sh
$ sudo service fpga-api {start|stop|restart|status}
```