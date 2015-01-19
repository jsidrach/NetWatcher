<?php
/**
 * Controller class of the MVC Pattern
 * 
 * All the controllers of the app must inherit from this one
 *
 * @package Core
 */

/**
 * Basic functionality classes
 */
namespace Core;

/**
 * Basic Abstract Controller Class
 */
abstract class Controller
{

    /**
     * Model
     *
     * @var Model model associated to the controller
     */
    protected $model;

    /**
     * View
     *
     * @var View view associated to the controller
     */
    protected $view;

    /**
     * Basic constructor.
     * Associates a model and a view to the controller
     *
     * @param Model $model
     *            Model to associate with the controller
     * @param View $view
     *            View to associate with the controller
     */
    protected function __construct($model, $view)
    {
        /* Assign the parameters */
        $this->model = $model;
        $this->view = $view;
    }
}
?>
