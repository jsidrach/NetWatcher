<?php
/**
 * Model class of the manager
 *
 * Inherits from appModel class
 *
 * @package App
 */

/**
 * App namespace (user defined behaviour)
 */
namespace App;

use Core\Config;

/**
 * managerMode class.
 * Checks the components
 */
class managerModel extends Common\appModel
{

    /**
     * JSON object (singleton)
     *
     * @var JSON object with the manager status (singleton)
     */
    private $status = null;

    /**
     * Constructor for the managerModel class.
     * Sets the strings (with localization support)
     */
    public function __construct()
    {
        parent::__construct();
        
        /* Additional libraries */
        array_push($this->cssLibraries, 'bootstrap-table.min.css');
        array_push($this->jsLibraries, 'bootstrap-table.min.js', 'bootstrap-table-localization.js.php', 'netwatcher-utils.js', 'netwatcher-manager.js.php');
    }

    /**
     * Get the current status of the FPGA
     */
    public function getManagerStatus()
    {
        if ($this->status == null) {
            $data = file_get_contents(\Core\Config::$REMOTE_SERVER_IP . '/info/status');
            if ($data === FALSE) {
                $tempStatus = new \stdClass();
                $tempStatus->status = 'error';
                return $tempStatus;
            }
            $this->status = json_decode($data);
        }
        return $this->status;
    }
}
?>