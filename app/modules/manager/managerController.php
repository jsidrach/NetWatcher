<?php
/**
 * Controller class of the manager
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
 * managerController class.
 * Manages the fpga as a finite state machine
 */
class managerController extends Common\appController
{

    /**
     * Default inherited constructor for the managerController class
     *
     * @param ManagerModel $model
     *            Data model of the Manager page
     * @param ManagerView $view
     *            View representation of the Manager page
     */
    public function __construct(ManagerModel $model, ManagerView $view)
    {
        parent::__construct($model, $view);
    }
}
?>