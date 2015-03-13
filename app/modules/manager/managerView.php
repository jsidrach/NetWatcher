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
        $this->renderModalRequest('rebootingModal', _('Rebooting Web Service'), 'rebootingProgress', 'rebootingLabel');
    }

    /**
     * Renders mode selection (player/recorder)
     */
    public function renderModeSelection()
    {
        $this->pLine('<div class="row">');
        /* Select one mode */
        $this->pLine('<div class="col-md-8 col-md-offset-2 text-center">', 1);
        $this->pLine('<h3 id="selectMode">' . _('Select a mode to initialize the FPGA') . '</h3><hr>', 1);
        $this->pLine('</div>', - 1);
        
        $this->pLine('</div>', - 1);
        $this->pLine('<div class="row">');
        
        /* Player */
        $this->pLine('<div class="col-md-4 col-md-offset-2 text-center">', 1);
        $this->pLine('<div class="alert alert-info" role="alert" id="initPlayer" data-toggle="modal" data-target="#initModal"  style="cursor: pointer">', 1);
        $this->pLine(_('Initialize the FPGA as a') . ' <strong>' . _('player') . '</strong>', 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        /* Recorder */
        $this->pLine('<div class="col-md-4 text-center">');
        $this->pLine('<div class="alert alert-info" role="alert" id="initRecorder" data-toggle="modal" data-target="#initModal"  style="cursor: pointer">', 1);
        $this->pLine(_('Initialize the FPGA as a') . ' <strong>' . _('recorder') . '</strong>', 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        
        $this->pLine('</div>', - 1);
        
        /* FPGA initialization progress modal */
        $this->renderModalRequest('initModal', _('Initializing the FPGA'), 'initProgress', 'initLabel');
    }

    /**
     * Renders recorder form
     */
    public function renderRecorderForm()
    {
        /* Form for start recording */
        $this->pLine('<form id="startRecording" class="form-horizontal" role="form" action="javascript:void(0);" method="post">');
        $this->pLine('<div class="row">', 1);
        $this->pLine('<div class="col-md-10 col-md-offset-1 text-center">', 1);
        $this->pLine('<h3>' . _('Configure the FPGA to start recording') . '</h3><hr>', 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        
        /* Capture name */
        $this->pLine('<div class="form-group has-feedback" id="recordCaptureNameControl">');
        $this->pLine('<label for="recordCaptureName" class="col-sm-3 col-sm-offset-1 control-label">' . _('Name of the new capture') . '</label>', 1);
        $this->pLine('<div class="col-sm-5">');
        $this->pLine('<input type="text" class="form-control" name="recordCaptureName" id="recordCaptureName">', 1);
        $this->pLine('<span class="glyphicon form-control-feedback" id="recordCaptureNameIcon" aria-hidden="true" ></span>');
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        
        /* Bytes */
        $this->pLine('<div class="form-group has-feedback" id="recordCaptureBytesControl">');
        $this->pLine('<label for="recordCaptureBytes" class="col-sm-3 col-sm-offset-1 control-label">' . _('Bytes to capture') . '</label>', 1);
        $this->pLine('<div class="col-sm-2">');
        $this->pLine('<input type="number" min="1" class="form-control" name="recordCaptureBytes" id="recordCaptureBytes">', 1);
        $this->pLine('<span class="glyphicon form-control-feedback" id="recordCaptureBytesIcon" aria-hidden="true" ></span>');
        $this->pLine('</div>', - 1);
        $this->pLine('<div class="col-sm-3">');
        $this->pLine('<div class="pull-right">', 1);
        $this->moveIndent(1);
        foreach (array(
            'Bytes',
            'K',
            'M',
            'G'
        ) as $type) {
            $this->pLine('<label class="radio-inline">');
            if ($type == 'Bytes') {
                $this->pLine('<input name="recordCaptureBytes" value="" type="radio" checked>', 1);
                $this->pLine($type, 1);
            } else {
                $this->pLine('<input name="recordCaptureBytes" value="' . $type . '" type="radio">', 1);
                $this->pLine($type . 'B', 1);
            }
            $this->pLine('</label>', - 2);
        }
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        
        /* Port */
        $this->pLine('<div class="form-group has-feedback" id="recordCapturePortControl">');
        $this->pLine('<label class="col-sm-3 col-sm-offset-1 control-label">' . _('Port to capture') . '</label>', 1);
        $this->pLine('<div class="col-sm-5">');
        $this->moveIndent(1);
        foreach (range(0, 3) as $port) {
            $this->pLine('<label class="radio-inline">');
            if ($port == 0) {
                $this->pLine('<input name="recordCapturePort" value="' . $port . '" type="radio" checked>', 1);
            } else {
                $this->pLine('<input name="recordCapturePort" value="' . $port . '" type="radio">', 1);
            }
            $this->pLine(_('Port') . ' ' . $port, 1);
            $this->pLine('</label>', - 2);
        }
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        
        /* Save button */
        $this->pLine('<div class="form-group">');
        $this->pLine('<div class="col-sm-offset-7 col-sm-2">', 1);
        $this->pLine('<button id="recordCaptureStart" type="submit" class="btn btn-default pull-right">' . _('Start') . '</button>', 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        
        $this->pLine('</form>', - 1);
        
        /* Change Mode */
        $this->pLine('<div class="row">');
        $this->pLine('<div class="col-md-5 col-md-offset-4 text-center"><hr>', 1);
        $this->pLine('<a href="manager/mode">', 1);
        $this->pLine('<span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>', 1);
        $this->pLine(_('Back to mode selection'));
        $this->pLine('</a>', - 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
    }

    /**
     * Renders the _currently_ recording page
     */
    public function renderRecording()
    {
        /* Values needed */
        $status = $this->model->getManagerStatus();
        $name = $status->capture;
        $port = $status->port;
        $capturedBytes = $this->formatBytes($status->bytes_captured);
        $totalBytes = $this->formatBytes($status->bytes_total);
        if ($status->elapsed_time <= 0) {
            $status->elapsed_time = 1;
        }
        $elapsedTime = $this->formatDateSeconds($status->elapsed_time);
        $averageRate = $this->formatBytes($bytesCaptured / $status->elapsed_time) . '/s';
        $percent = floor(100 * $bytesCaptured / $bytesTotal);
        
        /* Heading */
        $this->pLine('<div class="row" id="recordingControl">');
        $this->pLine('<div class="col-md-offset-2 col-md-8 text-center">', 1);
        $this->pLine('<h3 id="recordingTitle">' . _('Recording...') . '</h3><hr>', 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        /* Progress bar */
        $this->pLine('<div class="row">');
        $this->pLine('<div class="col-md-offset-2 col-md-8">', 1);
        $this->pLine('<div class="progress">', 1);
        $this->pLine('<span style="position:absolute;text-align:center;width:95%"><strong id="recordingLabel">' . $percent . '%</strong></span>', 1);
        $this->pLine('<div id="recordingProgress" class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" style="width: ' . $percent . '%"></div>');
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        
        /* Info */
        $this->printInfoElement('recordingName', _('Name of the capture'), $name);
        $this->printInfoElement('recordingPort', _('Port'), $port);
        $this->printInfoElement('recordingElapsedTime', _('Elapsed Time'), $elapsedTime);
        $this->printInfoElement('recordingBytesCaptured', _('Captured Bytes'), $capturedBytes);
        $this->printInfoElement('recordingBytesTotal', _('Total Bytes'), $totalBytes);
        $this->printInfoElement('recordingAverageRate', _('Average Rate'), $averageRate);
        $this->printInfoElement('recordingCurrentRate', _('Current Rate'), '--');
        
        /* Stop button */
        $this->pLine('<div class="row" style="text-align:center">');
        $this->pLine('<button type="button" class="btn btn-danger" id="stopRecording" data-toggle="modal" data-target="#confirmStopRecording">', 1);
        $this->pLine(_('Stop recording'), 1);
        $this->pLine('</button>', - 1);
        $this->pLine('</div>', - 1);
        /* Stop confirmation modal */
        $this->pLine('<!-- Stop confirmation -->');
        $this->pLine('<div id="confirmStopRecording" class="modal fade" tabindex="-2" role="dialog" aria-hidden="true">');
        $this->pLine('<div class="modal-dialog">', 1);
        $this->pLine('<div class="modal-content">', 1);
        $this->pLine('<div class="modal-body text-justify">', 1);
        $this->pLine(_('The current capture will be deleted. Are you sure you want to stop the recording?'), 1);
        $this->pLine('</div>', - 1);
        $this->pLine('<div class="modal-footer">');
        $this->pLine('<button type="button" data-dismiss="modal" class="btn btn-danger" id="confirmStop">' . _('Stop') . '</button>', 1);
        $this->pLine('<button type="button" data-dismiss="modal" class="btn btn-default">' . _('Cancel') . '</button>');
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
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
    private function renderModalRequest($modalId, $modalTitle, $progressBarId, $labelId)
    {
        $this->pLine('<!-- Request progress modal -->');
        $this->pLine('<div id="' . $modalId . '" data-backdrop="static" data-keyboard="false" class="modal fade" tabindex="-2" role="dialog" aria-hidden="true">');
        $this->pLine('<div class="modal-dialog">', 1);
        $this->pLine('<div class="modal-content">', 1);
        $this->pLine('<div class="modal-header alert-info">', 1);
        $this->pLine('<h4 class="modal-title">' . $modalTitle . '</h4>', 1);
        $this->pLine('</div>', - 1);
        $this->pLine('<div class="modal-body text-justify">');
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
    private function formatBytes($bytes)
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
    private function formatDateSeconds($seconds)
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
    private function printInfoElement($id, $label, $value)
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