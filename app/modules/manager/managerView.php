<?php
/**
 * View class of the manager
 *
 * Inherits from appView class
 *
 * @package App
 */

/**
 * App namespace (user defined behaviour)
 */
namespace App;

/**
 * managerView class.
 * Renders the visual representation of the manager page
 */
class managerView extends Common\appView
{

    /**
     * Constructor for the managerView class.
     * Sets the page title
     *
     * @param ManagerModel $model
     *            Data model of the Manager page
     */
    public function __construct(ManagerModel $model)
    {
        parent::__construct($model);
        $this->title = _('Manager');
    }

    /**
     * Renders the id of the page
     *
     * @see \App\Common\Views\appView::renderContent()
     */
    protected function renderContent()
    { }
    
    /**
     * Renders a connection error panel
     */
    public function renderError() {
        $this->pLine('<div id="connectionError" class="bootstrap-growl alert alert-danger"
                           style="position: fixed; margin: 0px 0px 0px -175px; z-index: 9999; top: 60px; width: 350px; left: 50%;">');
        $this->pLine('<p>', 1);
        $this->pLine(_('Connection Error. Trying to refresh in') . ' <label id="connectionErrorCountdown"></label>...', 1);
        $this->pLine('</p>', -1);
        $this->pLine('<p>');
        $this->pLine(sprintf(_('Consider visiting the %ssettings%s and %sstatus%s pages'), '<a href="settings">', '</a>', '<a href="status">', '</a>'), 1);
        $this->pLine('</p>', -1);
        $this->pLine('</div>', -1);
    }
    
    /**
     * Renders an error with HugePages
     */
    public function renderErrorHP() {
        //TODO
    }
    
    /**
     * Renders mode selection (player/recorder)
     */
    public function renderModeSelection() {
        //TODO
    }
}
?>