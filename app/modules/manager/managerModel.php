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
     * Constructor for the managerModel class.
     * Sets the strings (with localization support)
     */
    public function __construct()
    {
        parent::__construct();
        
        /* Additional libraries */
        array_push($this->jsLibraries, 'bootstrap-table.js', 'bootstrap-table-localization.js.php', 'netwatcher-manager.js.php');
    }

    /**
     * Get the current status of the FPGA
     */
    public function getStatus()
    {
        $context = stream_context_create(array(
            'http' => array(
                'timeout' => 2
            )
        ));
        $data = file_get_contents(\Core\Config::$REMOTE_SERVER_IP . '/info/status', 0, $context);
        if ($data === FALSE) {
            return 'error';
        } else {
            return json_decode($data)->status;
        }
    }
}
?>