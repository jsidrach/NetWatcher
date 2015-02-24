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
        array_push($this->jsLibraries, 'netwatcher-manager.js.php');
    }
}
?>