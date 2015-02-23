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
        $testsIds = $this->model->getTestsIds();
        $this->renderTestsSummary($stats);
        foreach ($this->model->getTestsResults() as $test => $result) {
            $this->renderTestPanel($test, $result, $testsDescriptions[$test], $testsIds[$test]);
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
        $this->pLine('<hr>');
        $this->pLine('<div class="progress">');
        foreach ($stats as $stat => $number) {
            if ($stat != 'total') {
                $progress = ($number / $stats['total']) * 100;
                $this->pLine('<div id="progressBar-' . $stat . '" class="progress-bar progress-bar-striped progress-bar-' . $stat . '" style="width: ' . number_format($progress, 2) . '%" role="progressbar"></div>', 1);
                $this->moveIndent(- 1);
            }
        }
        $this->pLine('</div>');
        foreach ($stats as $stat => $number) {
            if ($stat == 'total') {
                $this->pLine('<span id="progressLabel-total" class="label label-primary">' . _('Total') . ': ' . $number . '</span>');
            } else {
                $this->pLine('<span id="progressLabel-' . $stat . '" class="label label-' . $stat . '">' . $infoText[$stat] . ': ' . $number . '</span>');
            }
        }
        $this->pLine('<hr>');
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
     * @param string $id
     *            Id of the test
     */
    private function renderTestPanel($name, $result, $description, $id)
    {
        $infoText = $this->model->getInfoText();
        $this->pLine('<div class="panel panel-' . $result . '" id="' . $id . '">');
        $this->pLine('<div class="panel-heading">', 1);
        $this->pLine($name, 1);
        $this->pLine('<span class="panel-title pull-right" id="' . $id . 'Info">' . $infoText[$result] . '</span>');
        $this->pLine('</div>', - 1);
        $this->pLine('<div class="panel-body" id="' . $id . 'Desc">');
        $this->pLine($description, 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
    }
}
?>