<?php
/**
 * View class of the MVC Pattern
 * 
 * All the views of the app must inherit from this one
 *
 * @package Core
 */

/**
 * Basic functionality classes
 */
namespace Core;

/**
 * Basic Abstract View Class
 */
abstract class View
{

    /**
     * Model
     *
     * @var Model model associated to the view
     */
    protected $model;

    /**
     * Title
     *
     * @var Title title of the page
     */
    public $title;

    /**
     * Indent
     *
     * @var Indent actual indent of the printed page
     */
    private $indent;

    /**
     * Basic constructor.
     * Associates a model to the view
     *
     * @param Model $model
     *            Model to associate with the controller
     */
    protected function __construct($model)
    {
        /* Assign the parameters */
        $this->model = $model;
        $this->indent = 0;
    }

    /**
     * Prints the base path of the page
     */
    public function printBase()
    {
        $basePath = '<base href="';
        $numberOfSubPaths = RELATIVE_SUBPATHS - 1;
        for ($i = 0; $i < RELATIVE_SUBPATHS - 1; ++ $i) {
            $basePath .= '../';
        }
        $basePath .= '." />';
        $this->pLine($basePath, 0);
    }

    /**
     * Prints a string line indented
     *
     * @param String $string
     *            String to print
     * @param Int $offset
     *            Offset change of the indent
     */
    public function pLine($string, $offset = 0)
    {
        $this->indent += $offset;
        for ($i = 0; $i < $this->indent; ++ $i) {
            for ($j = 0; $j < INDENT_SPACES; ++ $j) {
                echo ' ';
            }
        }
        echo $string . PHP_EOL;
    }

    /**
     * Changes the indent
     *
     * @param Int $offset
     *            Offset change of the indent
     */
    public function moveIndent($offset)
    {
        $this->indent += $offset;
    }

    /**
     * Prints a notification popup
     *
     * @param String $string
     *            Message in the popup
     * @param String $type
     *            Type of the notification
     */
    public function renderNotification($string, $type)
    {
        $this->pLine('<script>', 0);
        
        $this->pLine('$.notify({', 1);
        $this->pLine('message: "' . $string . '"', 1);
        $this->pLine('},{', - 1);
        $this->pLine('type: "' . $type . '"', 1);
        $this->pLine('});', - 1);
        
        $this->pLine('</script>', - 1);
    }
}
?>
