<?php
/**
 * Model class of the app
 *
 * Model of the app _may_ inherit from this one
 *
 * @package Common
 */

/**
 * Generic model for the app
 */
namespace App\Common;

/**
 * Model Class
 */
abstract class appModel extends \Core\Model
{

    /**
     * Left navbar links
     */
    public $leftNavbar;

    /**
     * Right navbar links
     */
    public $rightNavbar;

    /**
     * Common css libraries
     */
    public $cssLibraries;

    /**
     * Common javascript libraries
     */
    public $jsLibraries;

    /**
     * App Model constructor.
     * Sets the navigation links and common javascript libraries
     */
    public function __construct()
    {
        $this->leftNavbar = array(
            'manager' => _('Manager'),
            'storage' => _('Storage'),
            'captures' => _('Captures')
        );
        $this->rightNavbar = array(
            'settings' => _('Settings')
        );
        /* 'theme' is the keyword for the user theme, so we can place it between other stylesheets */
        $this->cssLibraries = array(
            'bootstrap.min.css',
            'theme',
            'sticky-footer.css',
            'bootstrap-table.css'
        );
        $this->jsLibraries = array(
            'jquery.js',
            'bootstrap.js',
            'jquery.bootstrap-growl.js',
            'growl-settings.js'
        );
    }
}
?>