<?php
/**
 * View class of the status
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
 * statusView class.
 * Renders the visual representation of the status tests
 */
class statusView extends Common\appView
{

    /**
     * Constructor for the statusView class.
     * Sets the page title
     *
     * @param StatusModel $model
     *            Data model of the Status page
     */
    public function __construct(StatusModel $model)
    {
        parent::__construct($model);
        $this->title = _('Status');
    }

    /**
     * Renders the main content of the page inside the rest of the page
     *
     * @see \App\Common\Views\appView::renderContent()
     */
    protected function renderContent()
    {
        $testsResults = $this->model->getTestsResults();
        $testsDescriptions = $this->model->getTestsDescriptions();
        $stats = $this->model->getTestsStatistics();
        $this->renderTestsSummary($stats);
        foreach ($this->model->getTestsResults() as $test => $result) {
            $this->renderTestPanel($test, $result, $testsDescriptions[$test]);
        }
    }

    /**
     * Renders the summary of the status
     *
     * @param Array $stats
     *            Array of statitics of the tests
     */
    private function renderTestsSummary(Array $stats)
    {
        $infoText = $this->model->getInfoText();
        $this->pLine('<hr>', 0);
        $this->pLine('<div class="progress">', 0);
        foreach ($stats as $stat => $number) {
            if ($stat != 'total') {
                $progress = ($number / $stats['total']) * 100;
                $this->pLine('<div class="progress-bar progress-bar-striped progress-bar-' . $stat . '" style="width: ' . number_format($progress, 2) . '%" role="progressbar"></div>', 1);
                $this->moveIndent(- 1);
            }
        }
        $this->pLine('</div>', 0);
        foreach ($stats as $stat => $number) {
            if ($stat == 'total') {
                $this->pLine('<span class="label label-primary">' . _('Total') . ': ' . $number . '</span>', 0);
            } else {
                $this->pLine('<span class="label label-' . $stat . '">' . $infoText[$stat] . ': ' . $number . '</span>', 0);
            }
        }
        $this->pLine('<hr>', 0);
    }

    /**
     * Renders a panel with the information of the test
     *
     * @param string $name
     *            Name of the test
     * @param string $result
     *            Result of the test
     * @param string $description
     *            Description of the test
     */
    private function renderTestPanel($name, $result, $description)
    {
        $infoText = $this->model->getInfoText();
        $this->pLine('<div class="panel panel-' . $result . '">', 0);
        $this->pLine('<div class="panel-heading">', 1);
        $this->pLine($name, 1);
        $this->pLine('<span class="panel-title pull-right">' . $infoText[$result] . '</span>', 0);
        $this->pLine('</div>', - 1);
        $this->pLine('<div class="panel-body">', 0);
        $this->pLine($description, 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
    }
}
?>