To configure the FPGA Web service to your needs, edit the `config.js` file and set the variables listed in it:

| Variable     | Type             | Description                                                                                                                              |
|:-------------|:-----------------|:-----------------------------------------------------------------------------------------------------------------------------------------|
| BASE_PREFIX  | String           | Base prefix for every API resource                                                                                                       |
| PORT         | Integer          | Port the server is listening to                                                                                                          |
| MAX_DELAY    | Integer          | Maximum delay between the petition's timestamp and the server's timestamp. Set it to <= 0 to not discard any petition based on its timestamp |
| CAPTURES_DIR | String           | Directory where the captures are stored (end it with /)                                                                                  |
| RAID         | Boolean          | RAID active flag. Set it to true only if the `CAPTURES_DIR` is within a RAID and `RAID_DEV/RAID_DISKS` are set                               |
| RAID_DEV     | String           | RAID device                                                                                                                              |
| RAID_DISKS   | Array of Strings | RAID physical devices (e.g., `/dev/sdc`, `/dev/sdd`)                                                                                         |