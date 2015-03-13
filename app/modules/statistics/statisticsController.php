<?php
/**
 * Controller class of the statistics
 *
 * Inherits from appController class
 *
 * @package App
 */

/**
 * App namespace (user defined behaviour)
 */
namespace App;

/**
 * statisticsController class
 * Displays different statistics as disk usage
 */
class statisticsController extends Common\appController
{

    /**
     * Default inherited constructor for the statisticsController class
     *
     * @param StatisticsModel $model
     *            Data model of the Statistics page
     * @param StatisticsView $view
     *            View representation of the Statistics page
     */
    public function __construct(StatisticsModel $model, StatisticsView $view)
    {
        parent::__construct($model, $view);
    }
}
?>