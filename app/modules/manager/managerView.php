<?php
/**
 * View class of the manager
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
 * managerView class.
 * Renders the visual representation of the manager page
 */
class managerView extends Common\appView
{

    /**
     * Constructor for the managerView class.
     * Sets the page title
     *
     * @param ManagerModel $model
     *            Data model of the Manager page
     */
    public function __construct(ManagerModel $model)
    {
        parent::__construct($model);
        $this->title = _('Manager');
    }

    /**
     * Renders the id of the page
     *
     * @see \App\Common\Views\appView::renderContent()
     */
    protected function renderContent()
    {}

    /**
     * Renders a connection error panel
     */
    public function renderError()
    {
        $this->pLine('<div class="row">');
        $this->pLine('<div class="col-md-8 col-md-offset-2">', 1);
        $this->pLine('<div class="alert alert-danger" role="alert" id="connectionError">', 1);
        $this->pLine('<strong>' . _('Connection Error') . '</strong>. ' . sprintf(_('Consider visiting the %ssettings%s and %sstatus%s pages'), '<a href="settings" class="alert-link">', '</a>', '<a href="status" class="alert-link">', '</a>') . '.', 1);
        $this->pLine(_('Trying to refresh in') . ' <label id="connectionErrorCountdown"></label>...', 0);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
    }

    /**
     * Renders an error with HugePages
     */
    public function renderErrorHP()
    {
        /* Error message */
        $this->pLine('<div class="row">');
        $this->pLine('<div class="col-md-10 col-md-offset-1">', 1);
        $this->pLine('<div class="alert alert-danger" role="alert" id="hugePagesOff">', 1);
        $this->pLine('<p><strong>' . _('Error') . '</strong>: ' . _('HugePages is off and it is required by the FPGA') . '.</p>', 1);
        $this->pLine('<p>');
        $this->pLine('<a href="#" data-toggle="modal" data-target="#confirmRebootModal" class="alert-link">', 1);
        $this->pLine(_('Click here to reboot the FPGA server'), 1);
        $this->pLine('</a>', - 1);
        $this->pLine(' ' . _('(it will fix the issue only if the remote server has HugePages as its default boot option)') . '.');
        $this->pLine('</p>', - 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        /* Reboot modal confirmation */
        $this->pLine('<!-- Reboot confirmation -->');
        $this->pLine('<div id="confirmRebootModal" class="modal fade" tabindex="-2" role="dialog" aria-hidden="true">');
        $this->pLine('<div class="modal-dialog">', 1);
        $this->pLine('<div class="modal-content">', 1);
        $this->pLine('<div class="modal-body text-justify">', 1);
        $this->pLine('<p>' . _('The remote server will be rebooted, and all its process stopped') . '.</p>', 1);
        $this->pLine('<p>' . _('Are you sure you want to reboot?') . '</p>');
        $this->pLine('</div>', - 1);
        $this->pLine('<div class="modal-footer">');
        $this->pLine('<button type="button" data-dismiss="modal" class="btn btn-danger" id="confirmReboot" data-toggle="modal" data-target="#rebootingModal">' . _('Reboot') . '</button>', 1);
        $this->pLine('<button type="button" data-dismiss="modal" class="btn btn-default">' . _('Cancel') . '</button>');
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        /* Rebooting progress modal */
        $this->renderModalRequest('rebootingModal', _('Rebooting Web Service'), 'rebootingProgress');
    }

    /**
     * Renders mode selection (player/recorder)
     */
    public function renderModeSelection()
    {
        // TODO: Two buttons, one for each option, click on the button > open modal, launch jquery
        // ID: selectMode
        // Buttons ids: initPlayer, initRecorder
        
        /* FPGA initialization progress modal */
        $this->renderModalRequest('initModal', _('Initializing the FPGA'), 'initProgress');
    }

    /**
     * Renders a modal overlay (for ajax petitions)
     */
    private function renderModalRequest($modalId, $modalTitle, $progressBarId)
    {
        $this->pLine('<!-- Request progress modal -->');
        $this->pLine('<div id="' . $modalId . '" data-backdrop="static" data-keyboard="false" class="modal fade" tabindex="-2" role="dialog" aria-hidden="true">');
        $this->pLine('<div class="modal-dialog">', 1);
        $this->pLine('<div class="modal-content">', 1);
        $this->pLine('<div class="modal-header panel-primary">', 1);
        $this->pLine('<h4 class="modal-title">' . $modalTitle . '</h4>', 1);
        $this->pLine('</div>', - 1);
        $this->pLine('<div class="modal-body text-justify">');
        $this->pLine('<div class="progress">', 1);
        $this->pLine('<div id="' . $progressBarId . '" class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" style="width: 0%">', 1);
        $this->pLine('<span><strong id="initLabel">' . _('Sending request...') . '</strong></span>', 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
    }
}
?>