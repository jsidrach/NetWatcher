<?php
/**
 * View class of the statistics
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
 * statisticsView class.
 * Renders the visual representation of the statistics
 */
class statisticsView extends Common\appView
{

    /**
     * Constructor for the statisticsView class.
     * Sets the page title
     *
     * @param StatisticsModel $model
     *            Data model of the Statistics page
     */
    public function __construct(StatisticsModel $model)
    {
        parent::__construct($model);
        $this->title = _('Statistics');
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