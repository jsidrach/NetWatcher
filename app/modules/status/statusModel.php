<?php
/**
 * Model class of the status
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
 * statusModel class.
 * Checks the components
 */
class statusModel extends Common\appModel
{

    /**
     * Tests with its respectives checker function names
     */
    private $testsFunctions = array();

    /**
     * Tests and the results of the check function
     */
    private $testsResults = array();

    /**
     * Description of each test
     */
    private $testsDescriptions = array();

    /**
     * Strings of information about the tests results
     */
    private $infoText = array();

    /**
     * Constructor for the settingsModel class.
     * Sets the strings (with localization support)
     */
    public function __construct()
    {
        parent::__construct();

        /* Functions */
        $this->testsFunctions[_('Rewrite Module')] = 'checkModRewrite';
        $this->testsFunctions[_('Gettext Module')] = 'checkGettext';
        $this->testsFunctions[_('Session Variables')] = 'checkSession';
        $this->testsFunctions[_('Write Permissions')] = 'checkPermissions';
        $this->testsFunctions[_('FPGA API')] = 'checkRemoteServer';
        
        /* Status */
        $this->testsResults[_('Rewrite Module')] = 'warning';
        $this->testsResults[_('Gettext Module')] = 'warning';
        $this->testsResults[_('Session Variables')] = 'warning';
        $this->testsResults[_('Write Permissions')] = 'warning';
        $this->testsResults[_('FPGA API')] = 'warning';
        
        /* Descriptions */
        $this->testsDescriptions[_('Rewrite Module')] = _('URL rewrite support');
        $this->testsDescriptions[_('Gettext Module')] = _('Locale language support');
        $this->testsDescriptions[_('Session Variables')] = _('Support for the usage of sessions');
        $this->testsDescriptions[_('Write Permissions')] = _('Folder and file properties');
        $this->testsDescriptions[_('FPGA API')] = _('Remote FPGA Server');
        
        /* Info */
        $this->infoText['success'] = _('Passed');
        $this->infoText['warning'] = _('Not Implemented');
        $this->infoText['danger'] = _('Failed');
    }

    /**
     * Check all the modules and conditions
     */
    public function checkAll()
    {
        foreach ($this->testsFunctions as $test => $function) {
            if (method_exists($this, $function)) {
                $this->testsResults[$test] = $this->$function();
            }
        }
    }

    /**
     * Returns the tests results
     *
     * @return Array Name of the test => Result
     */
    public function getTestsResults()
    {
        return $this->testsResults;
    }

    /**
     * Returns the tests descriptions
     *
     * @return Array Description of the test => Result
     */
    public function getTestsDescriptions()
    {
        return $this->testsDescriptions;
    }

    /**
     * Returns the info strig of each result
     *
     * @return Array Info of each possible type of test result
     */
    public function getInfoText()
    {
        return $this->infoText;
    }

    /**
     * Returns the number of tests total/success/warning/danger
     *
     * @return Array Stats of the tests
     */
    public function getTestsStatistics()
    {
        $stats = array();
        $stats['total'] = 0;
        $stats['success'] = 0;
        $stats['warning'] = 0;
        $stats['danger'] = 0;
        
        foreach ($this->testsResults as $test) {
            $stats['total'] ++;
            if (isset($stats[$test])) {
                $stats[$test] ++;
            }
        }
        
        return $stats;
    }
    
    /*
     * Test Checkers
     */
    
    /**
     * Checks the rewrite module
     *
     * @return string Status of test over the rewrite module
     */
    private function checkModRewrite()
    {
        if (in_array('mod_rewrite', apache_get_modules())) {
            return 'success';
        }
        return 'danger';
    }

    /**
     * Checks the gettext support
     *
     * @return string Status of test over the gettext support
     */
    private function checkGettext()
    {
        $server_info = $this->phpinfo_array(true);
        if (isset($server_info['gettext']) && isset($server_info['gettext']['GetText Support']) && $server_info['gettext']['GetText Support'] == 'enabled') {
            return 'success';
        }
        return 'danger';
    }

    /**
     * Checks the session
     *
     * @return string Status of test over the session vars
     */
    private function checkSession()
    {
        if (isset($_SESSION)) {
            return 'success';
        }
        return 'danger';
    }

    /**
     * Checks the folder and file permissions
     *
     * @return string Status of test over the folder and file permissions
     */
    private function checkPermissions()
    {
        /* Internal files and directories */
        $write_dirs = array();
        $write_dirs[] = LOGGER_DIR;
        $write_dirs[] = CONFIG_DIR;
        foreach ($write_dirs as $dir) {
            if (! is_writable($dir)) {
                return 'danger';
            }
        }
        if (is_writable(APP_DIR)) {
            return 'danger';
        }
        return 'success';
    }

    /**
     * Checks the remote server status
     *
     * @return string Status of test over the remote server
     */
    private function checkRemoteServer()
    {
        $context = stream_context_create(array(
            'http' => array(
                'timeout' => 2
            )
        ));
        if (file_get_contents(Config::$REMOTE_SERVER_IP . '/ping', 0, $context) === FALSE) {
            return 'danger';
        }
        return 'success';
    }
    
    /*
     * Auxiliary functions
     */
    /**
     * Function to parse phpinfo() as an array.
     * From php.net/phpinfo comments section
     *
     * @param boolean $return
     *            Flag to determine if return an object or a string
     * @return Ambigous Object with the info or a formatted string with the info
     */
    private function phpinfo_array($return = false)
    {
        /* Andale! Andale! Yee-Hah! */
        ob_start();
        phpinfo(- 1);
        
        $pi = preg_replace(array(
            '#^.*<body>(.*)</body>.*$#ms',
            '#<h2>PHP License</h2>.*$#ms',
            '#<h1>Configuration</h1>#',
            "#\r?\n#",
            "#</(h1|h2|h3|tr)>#",
            '# +<#',
            "#[ \t]+#",
            '#&nbsp;#',
            '#  +#',
            '# class=".*?"#',
            '%&#039;%',
            '#<tr>(?:.*?)" src="(?:.*?)=(.*?)" alt="PHP Logo" /></a>' . '<h1>PHP Version (.*?)</h1>(?:\n+?)</td></tr>#',
            '#<h1><a href="(?:.*?)\?=(.*?)">PHP Credits</a></h1>#',
            '#<tr>(?:.*?)" src="(?:.*?)=(.*?)"(?:.*?)Zend Engine (.*?),(?:.*?)</tr>#',
            "# +#",
            '#<tr>#',
            '#</tr>#'
        ), array(
            '$1',
            '',
            '',
            '',
            '</$1>' . "\n",
            '<',
            ' ',
            ' ',
            ' ',
            '',
            ' ',
            '<h2>PHP Configuration</h2>' . "\n" . '<tr><td>PHP Version</td><td>$2</td></tr>' . "\n" . '<tr><td>PHP Egg</td><td>$1</td></tr>',
            '<tr><td>PHP Credits Egg</td><td>$1</td></tr>',
            '<tr><td>Zend Engine</td><td>$2</td></tr>' . "\n" . '<tr><td>Zend Egg</td><td>$1</td></tr>',
            ' ',
            '%S%',
            '%E%'
        ), ob_get_clean());
        
        $sections = explode('<h2>', strip_tags($pi, '<h2><th><td>'));
        unset($sections[0]);
        
        $pi = array();
        foreach ($sections as $section) {
            $n = substr($section, 0, strpos($section, '</h2>'));
            preg_match_all('#%S%(?:<td>(.*?)</td>)?(?:<td>(.*?)</td>)?(?:<td>(.*?)</td>)?%E%#', $section, $askapache, PREG_SET_ORDER);
            foreach ($askapache as $m) {
                if (isset($m[2])) {
                    $pi[$n][$m[1]] = (! isset($m[3]) || $m[2] == $m[3]) ? $m[2] : array_slice($m, 2);
                }
            }
        }
        
        return ($return === false) ? print_r($pi) : $pi;
    }
}
?>