<?php
/**
 * View class of the storage
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
 * storageView class.
 * Renders the visual representation of the storage statistics
 */
class storageView extends Common\appView
{

    /**
     * Constructor for the storageView class.
     * Sets the page title
     *
     * @param StorageModel $model
     *            Data model of the Storage page
     */
    public function __construct(StorageModel $model)
    {
        parent::__construct($model);
        $this->title = _('Storage');
    }

    /**
     * Renders the main content of the page inside the rest of the page
     *
     * @see \App\Common\Views\appView::renderContent()
     */
    protected function renderContent()
    {
        // TODO
    }
}
?>