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
        //TODO
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