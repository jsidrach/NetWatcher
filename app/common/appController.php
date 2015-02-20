<?php
/**
 * Controller class of the app
 *
 * Controllers of the app _may_ inherit from this one
 *
 * @package Common
 */

/**
 * Generic controller for the app
 */
namespace App\Common;

/**
 * Generic Controller Class
 */
abstract class appController extends \Core\Controller
{

    /**
     * Generic constructor.
     * Associates a model and a view to the controller
     *
     * @param Model $model
     *            Model to associate with the controller (appModel)
     * @param View $view
     *            View to associate with the controller (appView)
     */
    public function __construct($model, $view)
    {
        parent::__construct($model, $view);
    }

    /**
     * Calls the render of the model
     *
     * @param array $args
     *            Params of the display
     */
    public function display(Array $args)
    {
        $this->view->render();
    }
}
?>