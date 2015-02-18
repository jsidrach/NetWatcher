<?php
/**
 * View class of the captures
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
 * capturesView class.
 * Renders the visual representation of the captures available
 */
class capturesView extends Common\appView
{

    /**
     * Constructor for the capturesView class.
     * Sets the page title
     *
     * @param CapturesModel $model
     *            Data model of the Captures page
     */
    public function __construct(CapturesModel $model)
    {
        parent::__construct($model);
        $this->title = _('Captures');
    }

    /**
     * Renders the main content of the captures info inside the rest of the page
     *
     * @see \App\Common\Views\appView::renderContent()
     */
    protected function renderContent()
    {
        /* Captures table */
        $this->pLine('<div class="row">');
        $this->pLine('<div class="col-md-8">', 1);
        /* Toolbar of the table */
        $this->pLine('<div id="toolbar">', 1);
        $this->pLine('<label><input type="checkbox" id="simple" checked>' . _('Simple') . '</label>', 1);
        $this->pLine('<label><input type="checkbox" id="pcap" checked>' . _('Pcap') . '</label>');
        $this->pLine('<label><input type="checkbox" id="autoRefresh">' . _('Auto Refresh') . '</label>');
        $this->pLine('</div>', -1);
        $this->pLine('<table data-toggle="table"');
        $this->pLine('       data-search="true"');
        $this->pLine('       data-show-refresh="true"');
        $this->pLine('       data-toolbar="#toolbar">');
        $this->pLine('<thead>', 1);
        $this->pLine('<tr>', 1);
        $this->pLine('<th data-field="name" data-sortable="true">' . _('Name') . '</th>', 1);
        $this->pLine('<th data-field="type" data-sortable="true">' . _('Type') . '</th>');
        $this->pLine('<th data-field="size" data-sortable="true">' . _('Size') . '</th>');
        $this->pLine('<th data-field="date" data-sortable="true">' . _('Date') . '</th>');
        $this->pLine('</tr>', -1);
        $this->pLine('</thead>', -1);
        $this->pLine('</table>', -1);
        $this->pLine('</div>', -1);
        /* Right action menu */
        $this->pLine('<div class="col-md-4">');
        $this->pLine('</div>');
        $this->pLine('</div>', -1);
    }

    /**
     * Renders the end of the document + localization strings
     */
    protected function renderClose()
    {
        parent::renderLocalization();
        parent::renderClose();
    }
}
?>