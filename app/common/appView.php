<?php
/**
 * View class of the app
 *
 * View of the app _may_ inherit from this one
 *
 * @package Common
 */

/**
 * Generic view for the app
 */
namespace App\Common;

/**
 * Generic View Class
 */
abstract class appView extends \Core\View
{

    /**
     * Left navbar links
     */
    private static $leftNavbar;

    /**
     * Right navbar links
     */
    private static $rightNavbar;

    /**
     * Generic constructor.
     * Associates a model to the view
     *
     * @param Model $model
     *            Model to associate with the controller
     */
    protected function __construct($model)
    {
        parent::__construct($model);
        self::$leftNavbar = array(
            '#notImplemented1' => _('Manager'),
            '#notImplemented2' => _('Statistics'),
            'captures' => _('Captures')
        );
        self::$rightNavbar =  array(
            'settings' => _('Settings')
        );
    }

    /**
     * Renders the model
     */
    public function render()
    {
        $this->renderHeader();
        $this->renderNavbar();
        $this->renderContent();
        $this->renderModals();
        $this->renderFooter();
        $this->renderClose();
    }

    /**
     * Renders the header
     */
    protected function renderHeader()
    {
        $this->pLine('<!DOCTYPE html>', 0);
        $this->pLine('<html>', 0);
        
        $this->pLine('<head>', 1);
        $this->printBase();
        $this->pLine('<meta charset="' . META_CHARSET . '">', 1);
        
        $this->pLine('<title>', 0);
        $this->pLine(_($this->title) . ' - ' . APP_NAME, 1);
        $this->pLine('</title>', - 1);
        
        $this->pLine('<link rel="icon" href="' . APP_FAVICON . '">', 0);
        
        $this->pLine('<!-- CSS files -->', 0);
        foreach (\Core\Config::$CSS_LIBRARIES as $cssLib) {
            if (!strcmp($cssLib,'theme')) {
                $this->pLine('<link href="' . THEMES_DIR . \Core\Config::$CSS_THEMES[\Core\Config::$DEFAULT_CSS_THEME] . '" rel="stylesheet">', 0);
            } else {
                $this->pLine('<link href="' . CSS_DIR . $cssLib . '" rel="stylesheet">', 0);
            }
        }
        
        $this->pLine('<!-- JS files -->', 0);
        foreach (\Core\Config::$JS_LIBRARIES as $jsLib) {
            if(preg_match("/\.php$/", $jsLib)) {
              $jsLib .= '?' .time();
            }
            $this->pLine('<script src="' . JS_DIR . $jsLib . '"></script>', 0);
        }
        
        $this->pLine('</head>', - 1);
        $this->pLine('<body>', 0);
    }

    /**
     * Renders the navbar
     */
    protected function renderNavbar()
    {
        $this->pLine('<!-- Top navigation bar -->', 1);
        $this->pLine('<header class="navbar navbar-default navbar-static-top" id="top" role="banner">', 0);
        $this->pLine('<div class="container">', 1);
        $this->pLine('<div class="navbar-header">', 1);
        $this->pLine('<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">', 1);
        $this->pLine('<span class="sr-only">Toggle navigation</span>', 1);
        $this->pLine('<span class="icon-bar"></span>', 0);
        $this->pLine('<span class="icon-bar"></span>', 0);
        $this->pLine('<span class="icon-bar"></span>', 0);
        $this->pLine('</button>', - 1);
        $this->pLine('<div class="navbar-brand">' . _(APP_NAME) . '</div>', 0);
        $this->pLine('</div>', - 1);
        $this->pLine('<nav id="navbar" class="collapse navbar-collapse" role="navigation">', 0);
        
        $this->pLine('<ul class="nav navbar-nav">', 1);
        $this->moveIndent(1);
        foreach (self::$leftNavbar as $leftURL => $leftItem) {
            if ($this->title == _($leftItem)) {
                $this->pLine('<li class="active">', 0);
            } else {
                $this->pLine('<li>', 0);
            }
            $this->pLine('<a href="' . $leftURL . '">' . _($leftItem) . '</a>', 1);
            $this->pLine('</li>', - 1);
        }
        $this->pLine('</ul>', - 1);
        
        $this->pLine('<ul class="nav navbar-nav navbar-right">', 0);
        $this->moveIndent(1);
        foreach (self::$rightNavbar as $rightURL => $rightItem) {
            if ($this->title == _($rightItem)) {
                $this->pLine('<li class="active">', 0);
            } else {
                $this->pLine('<li>', 0);
            }
            $this->pLine('<a href="' . $rightURL . '">' . _($rightItem) . '</a>', 1);
            $this->pLine('</li>', - 1);
        }
        $this->pLine('</ul>', - 1);
        
        $this->pLine('</nav>', - 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</header>', - 1);
        
        $this->pLine('<!-- Page content -->', 0);
        $this->pLine('<div class="container" role="main">', 0);
        $this->moveIndent(1);
    }

    /**
     * Renders the content.
     * This function must be coded in the child class
     */
    abstract protected function renderContent();

    /**
     * Renders the modal dialogs
     */
    protected function renderModals()
    {
        $this->pLine('<!-- License -->');
        $this->pLine('<div class="modal fade" id="license" tabindex="-2" role="dialog" aria-hidden="true">', 0);
        $this->pLine('<div class="modal-dialog">', 1);
        $this->pLine('<div class="modal-content">', 1);
        $this->pLine('<div class="modal-header">', 1);
        $this->pLine('<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>', 1);
        $this->pLine('<h4 class="modal-title" id="myModalLabel">' . _('License') . ' - ' . APP_NAME . '</h4>', 0);
        $this->pLine('</div>', - 1);
        $this->pLine('<div class="modal-body text-justify">', 0);
        
        /* Reads and formats the license file */
        $file = fopen(LICENSE_FILE, "r");
        /* Header */
        $line = trim(preg_replace('/\s+/', ' ', htmlspecialchars(fgets($file))));
        $this->pLine('<b>' . $line . '</b><br>', 1);
        /* Body */
        while (($line = fgets($file)) !== false) {
            $line = htmlspecialchars($line);
            $this->pline(trim(preg_replace('/\s+/', ' ', $line)) . '<br>', 0);
        }
        fclose($file);
        
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', -1);
    }

    /**
     * Renders the footer
     */
    protected function renderFooter()
    {
        $this->pLine('</div>', - 1);
        
        $this->pLine('<!-- Footer -->', 0);
        $this->pLine('<footer class="footer" role="contentinfo">', 0);
        $this->pLine('<div class="container text-center">', 1);
        
        $this->pLine('<p class="muted credit">', 1);
        $this->pLine('High Performance Computing and Networking', 0);
        $this->pLine('&middot;', 0);
        $this->pLine('<a href="status">' . _('Status') . '</a>', 0);
        $this->pLine('&middot;', 0);
        $this->pLine('<a target="_blank" href="https://github.com/JSidrach/NetWatcher/wiki">' . _('Wiki') . '</a>', 0);
        $this->pLine('&middot;', 0);
        $this->pLine('<a target="_blank" href="https://github.com/JSidrach/NetWatcher">' . _('Source') . '</a>', 0);
        $this->pLine('&middot;', 0);
        $this->pLine('<a href="" data-toggle="modal" data-target="#license">' . _('License') . '</a>', 0);
        $this->pLine('</p>', 0);
        
        $this->pLine('</div>', - 1);
        
        $this->pLine('</footer>', - 1);
    }

    /**
     * Renders the end of the document
     */
    protected function renderClose()
    {
        $this->pLine('</body>', - 1);
        $this->pLine('</html>', - 1);
    }

    /**
     * Renders the localization strings of the plugins. Not used by default.
     * To enable it, override renderClose() and call first to this function and then to the parent renderClose().
     */
    protected function renderLocalization()
    {
        $this->pLine('<script>');
        $this->pLine('<!-- Localization of the bootstrap-table plugin -->', 1);
        $this->pLine('(function ($) {');
        $this->pLine('\'use strict\'', 1);
        $this->pLine('var locale_strings = {');
        $this->pLine('formatLoadingMessage: function () {', 1);
        $this->pLine('return \'' . _('Loading, please wait...') . '\';', 1);
        $this->pLine('},', -1);
        $this->pLine('formatRecordsPerPage: function (pageNumber) {');
        $this->pLine('return sprintf(\'%s ' . _('records per page') . '\', pageNumber);', 1);
        $this->pLine('},', -1);
        $this->pLine('formatShowingRows: function (pageFrom, pageTo, totalRows) {');
        $this->pLine('return sprintf(\'' . _('Showing %s to %s of %s rows') . '\', pageFrom, pageTo, totalRows);', 1);
        $this->pLine('},', -1);
        $this->pLine('formatSearch: function () {');
        $this->pLine('return \'' . _('Search') . '\';', 1);
        $this->pLine('},', -1);
        $this->pLine('formatNoMatches: function () {');
        $this->pLine('return \'' . _('No matching records found') . '\';', 1);
        $this->pLine('},', -1);
        $this->pLine('formatPaginationSwitch: function () {');
        $this->pLine('return \'' . _('Hide/Show pagination') . '\';', 1);
        $this->pLine('},', -1);
        $this->pLine('formatRefresh: function () {');
        $this->pLine('return \'' . _('Refresh') . '\';', 1);
        $this->pLine('},', -1);
        $this->pLine('formatToggle: function () {');
        $this->pLine('return \'' . _('Toggle') . '\';', 1);
        $this->pLine('},', -1);
        $this->pLine('formatColumns: function () {');
        $this->pLine('return \'' . _('Columns') . '\';', 1);
        $this->pLine('}', -1);
        $this->pLine('};', -1);
        $this->pLine('$.extend($.fn.bootstrapTable.defaults, locale_strings);');
        $this->pLine('})(jQuery);', -1);
        $this->pLine('</script>', -1);
    }
}
?>