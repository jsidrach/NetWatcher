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

#### Generate Automatic Documentation (back-end and front-end)
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
$ ./scripts/do_chmod.sh
```

#### Install dependencies
On the FPGA Web Service host
```sh
$ ./scripts/do_chmod.sh
```

#### Update server remotely
Modify the `./fpga-api/scripts/update_server.sh` and set the configuration (`SERVER_IP`, `SERVER_PATH`, `USER`). Run from the `./fpga-api/` folder:
```sh
$ ./scripts/update_server.sh
```
When the update finishes, login on the remote server and start the fpga-api service

#### Light update (only the routes folder and the configuration file)
Run from the `./fpga-api/` folder:
```sh
$ ./scripts/update_server.sh --light
```

#### Manage the FPGA Web Service
Once installed, use it as a regular service
```sh
$ sudo service fpga-api {start|stop|restart|status}
```

#### Remove the FPGA Web Service
On the FPGA Web Service host
```sh
$ ./scripts/remove_service.sh fpga-api
```
