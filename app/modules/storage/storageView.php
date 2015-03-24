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
        /* Loading message */
        $this->pLine('<div id="loadingDiskStats" class="row">');
        $this->pLine('<div class="col-md-4 col-md-offset-4 text-center">', 1);
        $this->pLine('<h3>' . _('Gathering disk statistics...') . '</h3><hr>', 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        
        /* Chart placeholder */
        $this->pLine('<div id="chartRow" class="row hidden">');
        $this->pLine('<div class="col-md-8 col-md-offset-2">', 1);
        $this->pLine('<canvas id="chart" width="400" height="400">', 1);
        $this->pLine('</canvas>');
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        
        /* Detailed statistics and actions placeholder */
    }
}
?>