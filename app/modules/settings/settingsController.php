<?php
/**
 * Controller class of the settings
 *
 * Inherits from appController class
 *
 * @package App
 */

/**
 * App namespace (user defined behaviour)
 */
namespace App;

/**
 * settingsController class.
 * Saves the new settings
 */
class settingsController extends Common\appController
{

    /**
     * Default inherited constructor for the settingsController class
     *
     * @param SettingsModel $model
     *            Data model of the Settings page
     * @param SettingsView $view
     *            View representation of the Settings page
     */
    public function __construct(SettingsModel $model, SettingsView $view)
    {
        parent::__construct($model, $view);
    }

    /**
     * Saves the new settings and calls the render
     *
     * @param array $args
     *            Array containing the request
     */
    public function save(array $args)
    {
        /* Save the settings */
        if (isset($_REQUEST['language'])) {
            $settings['lang'] = $_REQUEST['language'];
        } else {
            $settings['lang'] = \Core\Config::$DEFAULT_LANG;
        }
        if (isset($_REQUEST['theme'])) {
            $settings['theme'] = $_REQUEST['theme'];
        } else {
            $settings['theme'] = \Core\Config::$DEFAULT_CSS_THEME;
        }
        /* Note: REMOTE_SERVER_IP must have http:// at the start of the string */
        if (isset($_REQUEST['serverIp'])) {
            $prefix = 'http://';
            $settings['serverIp'] = $_REQUEST['serverIp'];
            if(!(substr($settings['serverIp'], 0, strlen($prefix)) === $prefix)) {
              $settings['serverIp'] = $prefix . $settings['serverIp'];
            }
        } else {
            $settings['serverIp'] = \Core\Config::$REMOTE_SERVER_IP;
        }
        /* Cache string for the latest change in generated javascripts */
        $settings['cacheTime'] = time();
        $ctrl = $this->model->saveSettings($settings);
        
        /* Loads the new settings */
        \Core\Config::dynamicLoad();
        
        /* Reloads the model and renders the page */
        $this->model = new settingsModel();
        $this->view = new settingsView($this->model);
        $this->view->render();
        
        /* Renders the notification */
        if ($ctrl === TRUE) {
            $this->view->renderNotification(_('Settings saved successfully'), 'success');
        } else {
            $this->view->renderNotification(_('Error saving settings'), 'danger');
            \Core\Logger::logWarning('Failed to save settings');
        }
    }
}
?>