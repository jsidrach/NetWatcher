<?php
/**
 * View class of the storage
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
 * storageView class.
 * Renders the visual representation of the storage statistics
 */
class storageView extends Common\appView
{

    /**
     * Constructor for the storageView class.
     * Sets the page title
     *
     * @param StorageModel $model
     *            Data model of the Storage page
     */
    public function __construct(StorageModel $model)
    {
        parent::__construct($model);
        $this->title = _('Storage');
    }

    /**
     * Renders the format RAID confirmation dialog
     */
    protected function renderModals()
    {
        parent::renderModals();
        
        /* Format RAID confirmation modal */
        $this->pLine('<!-- Format RAID confirmation -->');
        $this->pLine('<div id="confirmFormatRaid" class="modal fade" tabindex="-2" role="dialog" aria-hidden="true">');
        $this->pLine('<div class="modal-dialog">', 1);
        $this->pLine('<div class="modal-content">', 1);
        $this->pLine('<div class="modal-body text-justify">', 1);
        $this->pLine('<strong class="text-danger">' . _('All data will be lost, are you sure you want to format the RAID?') . '</strong>', 1);
        $this->pLine('</div>', - 1);
        $this->pLine('<div class="modal-footer">');
        $this->pLine('<button type="button" data-dismiss="modal" data-toggle="modal" data-target="#formatRaidModal" class="btn btn-danger">' . _('Format') . '</button>', 1);
        $this->pLine('<button type="button" data-dismiss="modal" class="btn btn-default">' . _('Cancel') . '</button>');
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
    }

    /**
     * Renders the main content of the page inside the rest of the page
     *
     * @see \App\Common\Views\appView::renderContent()
     */
    protected function renderContent()
    {
        /* Loading message */
        $this->pLine('<div id="loadingDiskStats" class="row">');
        $this->pLine('<div class="col-md-4 col-md-offset-4 text-center">', 1);
        $this->pLine('<h3>', 1);
        $this->pLine(_('Gathering storage statistics'), 1);
        $this->pLine('</h3>', - 1);
        $this->pLine('<span id="gatheringDots">.</span>');
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        
        /* Connection error placeholder */
        $this->pLine('<div id="storageConnectionError" class="row collapse">');
        $this->pLine('<div class="col-md-8 col-md-offset-2">', 1);
        $this->pLine('<div class="alert alert-danger text-center" role="alert">', 1);
        $this->pLine('<strong>' . _('Connection Error') . '</strong>. ' . sprintf(_('Consider visiting the %ssettings%s and %sstatus%s pages'), '<a href="settings" class="alert-link">', '</a>', '<a href="status" class="alert-link">', '</a>') . '.', 1);
        $this->pLine('<a href="storage" class="alert-link">' . _('Refresh the page') . '</a>');
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        
        /* RAID statistics placeholder */
        $this->pLine('<div id="raidStats" class="collapse">');
        /* Title */
        $this->pLine('<div class="row">', 1);
        $this->pLine('<div class="col-md-4 col-md-offset-4 text-center">', 1);
        $this->pLine('<h3>', 1);
        $this->pLine(_('RAID statistics'), 1);
        $this->pLine('</h3><hr>', - 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        /* Chart */
        $this->pLine('<div class="row">');
        $this->pLine('<div class="col-md-8 col-md-offset-2 text-center">', 1);
        $this->pLine('<h4>' . _('Individual write speeds') . ' (MB/s)' . '</h4>');
        $this->pLine('<canvas id="raidStatsChart" width="600" height="375">', 1);
        $this->pLine('</canvas>');
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        /* Stats with label */
        $this->pLine('<div class="row">&nbsp;</div>');
        $this->printInfoElement('raidSpeed', _('Global write speed of the RAID'), '');
        $this->pLine('</div>', - 1);
        
        /* Format RAID panel */
        $this->pLine('<div class="row">&nbsp;</div>');
        $this->pLine('<div id="raidSlow" class="row collapse">');
        $this->pLine('<div class="col-md-8 col-md-offset-2">', 1);
        $this->pLine('<div class="panel panel-danger">', 1);
        $this->pLine('<div class="panel-body">', 1);
        $this->pLine('<p>', 1);
        $this->pLine(_('RAID write speed is below the minimum acceptable threshold') . '. ', 1);
        $this->pLine(_('This can cause the capture/reproduction to be actually slower than 10Gb/s') . '. ');
        $this->pLine(_('One possible solution is to format the RAID disks and re-create it') . '. ');
        $this->pLine('</p>', - 1);
        $this->pLine('<p>');
        $this->pLine('<strong class="text-danger">' . _('Warning: all data will be lost') . '</strong>. ', 1);
        $this->pLine('</p>', - 1);
        $this->pLine('<p class="pull-right">');
        $this->pLine('<button type="button" id="dismissFormat" class="btn btn-default">', 1);
        $this->pLine(_('Dismiss'), 1);
        $this->pLine('</button>', - 1);
        $this->pLine('<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmFormatRaid">');
        $this->pLine(_('Format the RAID'), 1);
        $this->pLine('</button>', - 1);
        $this->pLine('</p>', - 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        
        /* Format in progress modal */
        $this->renderModalRequest('formatRaidModal', _('Formatting the RAID...'), 'formatProgress', 'formatLabel');
        
        /* Space usage placeholder */
        $this->pLine('<div id="spaceStats" class="collapse">');
        $this->pLine('<div class="row">&nbsp;</div>', 1);
        /* Title */
        $this->pLine('<div class="row">');
        $this->pLine('<div class="col-md-4 col-md-offset-4 text-center">', 1);
        $this->pLine('<h3>', 1);
        $this->pLine(_('Space statistics'), 1);
        $this->pLine('</h3><hr>', - 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        /* Chart */
        $this->pLine('<div class="row">');
        $this->pLine('<div class="col-md-4 col-md-offset-4 text-center">', 1);
        $this->pLine('<canvas id="spaceStatsChart" width="300" height="300">', 1);
        $this->pLine('</canvas>');
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        /* Stats with label */
        $this->printInfoElement('spaceTotal', _('Total space'), '');
        $this->printInfoElement('spaceUsed', _('Used space'), '');
        $this->printInfoElement('spaceFree', _('Free space'), '');
        $this->printInfoElement('spaceUsedP', _('Used space (%)'), '');
        $this->printInfoElement('spaceFreeP', _('Free space (%)'), '');
        $this->pLine('</div>', - 1);
        
        /* RAID not configured placeholder */
        /* Lower panel with info about adding external captures */
        $this->pLine('<div class="row">&nbsp;</div>');
        $this->pLine('<div id="raidNotConfigured" class="row collapse">');
        $this->pLine('<div class="col-md-8 col-md-offset-2">', 1);
        $this->pLine('<div class="panel panel-info">', 1);
        $this->pLine('<div class="panel-body">', 1);
        $this->pLine(_('The FPGA Web Service has the RAID option not set. To configure the storage as a RAID, edit the configuration of the remote server'), 1);
        $this->pLine(' (' . '<a target="_blank" href="https://github.com/JSidrach/NetWatcher/blob/master/docs/wiki/FPGA_Configuration.md">' . _('see the wiki documentation') . '</a>' . ')');
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
        $this->pLine('</div>', - 1);
    }
}
?>