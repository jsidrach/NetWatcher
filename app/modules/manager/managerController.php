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
     * Callbacks depending on the model status
     */
    private $subPages = array(
        'error' => 'renderError',
        'hugepages_off' => 'renderErrorHP',
        'init_off' => 'renderModeSelection',
        'mount_off' => 'renderModeSelection'
    );

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

    /**
     * Overwrites the parent display to control the different pages depending on the FPGA status
     *
     * @see \App\Common\Controllers\appController::display()
     *
     * @param array $args
     *            Params of the display (unused)
     */
    public function display(Array $args)
    {
        $status = $this->model->getStatus();
        
        if (isset($this->subPages[$status]) && method_exists($this->view, $this->subPages[$status])) {
            $callback = $this->subPages[$status];
        } else {
            $callback = 'renderModeSelection';
        }
        $this->view->render($callback);
    }
}
?>