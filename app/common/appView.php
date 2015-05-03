<?php
/**
 * View class of the app
 *
 * View of the app _may_ inherit from this one
 *
 * @package Common
 */

/**
 * Generic view for the app
 */
namespace App\Common;

/**
 * Generic View Class
 */
abstract class appView extends \Core\View
{

    /**
     * Generic constructor.
     * Associates a model to the view
     *
     * @param Model $model
     *            Model to associate with the controller
     */
    public function __construct($model)
    {
        parent::__construct($model);
    }

    /**
     * Renders the model (page)
     *
     * @param Callback $callback
     *            Callback to the function that renders the main content (optional)
     */
    public function render($callback = 'renderContent')
    {
        $this->renderHead();
        call_user_func(array(
            $this,
            $callback
        ));
        $this->renderTail();
    }

    /**
     * Renders the head of the page
     */
    protected function renderHead()
    {
        $this->renderHeader();
        $this->renderNavbar();
    }

    /**
     * Renders the tail of the page
     */
    protected function renderTail()
    {
        $this->renderModals();
        $this->renderFooter();
        $this->renderClose();
    }

    /**
     * Renders the header
     */
    protected function renderHeader()
    {
        $this->pLine('<!DOCTYPE html>');
        $this->pLine('<html lang="' . substr(\Core\Config::$LANGUAGES[\Core\Config::$DEFAULT_LANG], 0, 2) . '">');
        
        $this->pLine('<head>', 1);
        $this->printBase();
        $this->pLine('<meta name="viewport" content="width=device-width, initial-scale=1">', 1);
        $this->pLine('<meta charset="' . META_CHARSET . '">');
        
        $this->pLine('<title>');
        $this->pLine(_($this->title) . ' - ' . APP_NAME, 1);
        $this->pLine('</title>', - 1);
        
        $this->pLine('<link rel="icon" href="' . APP_FAVICON . '">');
        
        $this->pLine('<!-- CSS files -->');
        foreach ($this->model->cssLibraries as $cssLib) {
            if ((! strcmp($cssLib, 'theme')) && isset(\Core\Config::$CSS_THEMES[\Core\Config::$DEFAULT_CSS_THEME])) {
                $this->pLine('<link href="' . THEMES_DIR . \Core\Config::$CSS_THEMES[\Core\Config::$DEFAULT_CSS_THEME] . '" rel="stylesheet">');
            } else {
                $this->pLine('<link href="' . CSS_DIR . $cssLib . '" rel="stylesheet">');
            }
        }
        
        $this->pLine('<!-- JS files -->');
        foreach ($this->model->jsLibraries as $jsLib) {
            /* Refresh cache if needed (after saving new settings) */
            if (preg_match("/\.php$/", $jsLib)) {
                $jsLib .= '?' . \Core\Config::$CACHE_TIME;
            }
            $this->pLine('<script src="' . JS_DIR . $jsLib . '"></script>');
        }
        
        $this->pLine('</head>', - 1);
        $this->pLine('<body>');
    }

    /**
     * Renders the navbar
     */
    protected function renderNavbar()
    {
        $this->pLine('<!-- Top navigation bar -->', 1);
        $this->pLine('<header class="navbar navbar-default navbar-static-top" id="top" role="banner">');
        $this->pLine('<div class="container">', 1);
        $this->pLine('<div class="navbar-header">', 1);
        $this->pLine('<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">', 1);
        $this->pLine('<span class="sr-only">Toggle navigation</span>', 1);
        $this->pLine('<span class="icon-bar"></span>');
        $this->pLine('<span class="icon-bar"></span>');
        $this->pLine('<span class="icon-bar"></span>');
        $this->pLine('</button>', - 1);
        $this->pLine('<div class="navbar-brand">' . _(APP_NAME) . '</div>');
        $this->pLine('</div>', - 1);
        $this->pLine('<nav id="navbar" class="collapse navbar-collapse" role="navigation">');
        
        $this->pLine('<ul class="nav navbar-nav">', 1);
        $this->moveIndent(1);
        foreach ($this->model->leftNavbar as $leftURL => $leftItem) {
            if ($this->title == _($leftItem)) {
                $this->pLine('<li class="active">');
            } else {
                $this->pLine('<li>');
            }
            $this->pLine('<a href="' . $leftURL . '">' . _($leftItem) . '</a>', 1);
            $this->pLine('</li>', - 1);
        }
        $this->pLine('</ul>', - 1);
        
        $this->pLine('<ul class="nav navbar-nav navbar-right">');
        $this->moveIndent(1);
        foreach ($this->model->rightNavbar as $rightURL => $rightItem) {
            if ($this->title == _($rightItem)) {
                $this->pLine('<li class="active">');
            } else {
                $this->pLine('<li>');
            }
            $this->pLine('<a href="' . $rightURL . '">' . _($rightItem) . '</a>', 1);
            $this->pLine('</li>', - 1);
        }
        $this->pLine('</ul>', - 1);
        
        $this->pLine('</nav>', - 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</header>', - 1);
        
        $this->pLine('<!-- Page content -->');
        $this->pLine('<div class="container" role="main">');
        $this->moveIndent(1);
    }

    /**
     * Renders the content.
     * This function must be coded in the child class
     */
    abstract protected function renderContent();

    /**
     * Renders the modal dialogs
     */
    protected function renderModals()
    {
        $this->pLine('<!-- License -->');
        $this->pLine('<div class="modal fade" id="license" tabindex="-2" role="dialog" aria-hidden="true">');
        $this->pLine('<div class="modal-dialog">', 1);
        $this->pLine('<div class="modal-content">', 1);
        $this->pLine('<div class="modal-header">', 1);
        $this->pLine('<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>', 1);
        $this->pLine('<h4 class="modal-title" id="licenseTitle">' . _('License') . ' - ' . APP_NAME . '</h4>');
        $this->pLine('</div>', - 1);
        $this->pLine('<div class="modal-body text-justify">');
        
        /* Reads and formats the license file */
        $file = fopen(LICENSE_FILE, "r");
        /* Header */
        $line = trim(preg_replace('/\s+/', ' ', htmlspecialchars(fgets($file))));
        $this->pLine('<b>' . $line . '</b><br>', 1);
        /* Body */
        while (($line = fgets($file)) !== false) {
            $line = htmlspecialchars($line);
            $this->pline(trim(preg_replace('/\s+/', ' ', $line)) . '<br>');
        }
        fclose($file);
        
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
    }

    /**
     * Renders the footer
     */
    protected function renderFooter()
    {
        $this->pLine('</div>', - 1);
        
        $this->pLine('<!-- Footer -->');
        $this->pLine('<footer class="footer" role="contentinfo">');
        $this->pLine('<div class="container text-center">', 1);
        
        $this->pLine('<p class="muted credit">', 1);
        $this->pLine('<span id="researchGroup">', 1);
        $this->pLine('High-Performance Computing and Networking', 1);
        $this->pLine('</span>', - 1);
        $this->pLine('&middot;');
        $this->pLine('<a href="status">' . _('Status') . '</a>');
        $this->pLine('&middot;');
        $this->pLine('<a target="_blank" href="https://github.com/JSidrach/NetWatcher/tree/master/docs">' . _('Wiki') . '</a>');
        $this->pLine('&middot;');
        $this->pLine('<a target="_blank" href="https://github.com/JSidrach/NetWatcher">' . _('Source') . '</a>');
        $this->pLine('&middot;');
        $this->pLine('<a href="" data-toggle="modal" data-target="#license">' . _('License') . '</a>');
        $this->pLine('</p>', - 1);
        
        $this->pLine('</div>', - 1);
        
        $this->pLine('</footer>', - 1);
    }

    /**
     * Renders the end of the document
     */
    protected function renderClose()
    {
        $this->pLine('</body>', - 1);
        $this->pLine('</html>', - 1);
    }

    /**
     * Renders a modal overlay (for ajax petitions)
     *
     * @param ModalId $view
     *            Id of the modal div
     * @param ModalTitle $view
     *            Id of the modal's title
     * @param ProgressBarId $view
     *            Id of the modal's progress bar
     * @param LabelId $view
     *            Id of the modal's progress bar label
     */
    protected function renderModalRequest($modalId, $modalTitle, $progressBarId, $labelId)
    {
        $this->pLine('<!-- Request progress modal -->');
        $this->pLine('<div id="' . $modalId . '" data-backdrop="static" data-keyboard="false" class="modal fade" tabindex="-2" role="dialog" aria-hidden="true">');
        $this->pLine('<div class="modal-dialog">', 1);
        $this->pLine('<div class="modal-content">', 1);
        $this->pLine('<div class="modal-header alert-info">', 1);
        $this->pLine('<h4 class="modal-title">' . $modalTitle . '</h4>', 1);
        $this->pLine('</div>', - 1);
        $this->pLine('<div class="modal-body text-center">');
        $this->pLine('<div class="progress">', 1);
        $this->pLine('<div id="' . $progressBarId . '" class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" style="width: 0%">', 1);
        $this->pLine('<span><strong id="' . $labelId . '">' . _('Sending request...') . '</strong></span>', 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
    }

    /**
     * Parses a number of bytes into a string
     *
     * @param Bytes $bytes
     *            Number of bytes
     * @return string Bytes parsed into a string
     */
    protected function formatBytes($bytes)
    {
        $bytesFormat = array(
            'K' => 1024,
            'M' => 1024,
            'G' => 1024
        );
        $suffix = '';
        foreach ($bytesFormat as $key => $scale) {
            if ($bytes >= $scale) {
                $suffix = $key;
                $bytes /= $scale;
            }
        }
        return sprintf('%.2f %sB', round($bytes, 2), $suffix);
    }

    /**
     * Parses a number of seconds into a time string
     *
     * @param Seconds $seconds
     *            Number of seconds
     * @return string Date parsed into a string
     */
    protected function formatDateSeconds($seconds)
    {
        $dateFormat = array(
            'd' => 86400,
            'h' => 3600,
            'm' => 60,
            's' => 1
        );
        $date = '';
        foreach ($dateFormat as $scale) {
            if (($date != '') || ($seconds >= $scale)) {
                $date .= sprintf('%02d:', floor($seconds / $scale));
                $seconds = $seconds % $scale;
            }
        }
        return substr($date, 0, - 1);
    }

    /**
     * Prints an info element
     *
     * @param Id $id
     *            Id of the info value element
     * @param Label $label
     *            Label of the info
     * @param Value $value
     *            Value of the info
     */
    protected function printInfoElement($id, $label, $value)
    {
        $this->pLine('<div class="row">');
        $this->pLine('<div class="col-xs-6 control-label">', 1);
        $this->pLine('<label class="pull-right">' . $label . '</label>', 1);
        $this->pLine('</div>', - 1);
        $this->pLine('<div class="col-xs-6">');
        $this->pLine('<span id="' . $id . '">' . $value . '</span>', 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
    }
}
?>