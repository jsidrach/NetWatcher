<?php
Header("content-type: application/x-javascript; charset=utf-8");
/* Autoload libraries */
require_once('../../lib/vendor/autoload.php');
/* Loads the config */
\Core\Config::load('../..');
?>
<!-- Localization of the bootstrap-table plugin -->
(function ($) {
  'use strict'
  var locale_strings = {
    formatLoadingMessage: function () { <?php echo 'return \'' . _('Loading, please wait...').'\';'.PHP_EOL; ?>
    },
    formatRecordsPerPage: function (pageNumber) { <?php echo 'return sprintf(\'%s '._('records per page').'\', pageNumber);'.PHP_EOL; ?>
    },
    formatShowingRows: function (pageFrom, pageTo, totalRows) { <?php echo 'return sprintf(\''._('Showing %s to %s of %s rows').'\', pageFrom, pageTo, totalRows);'.PHP_EOL; ?>
    },
    formatSearch: function () { <?php echo 'return \''._('Search').'\';'.PHP_EOL; ?>
    },
    formatNoMatches: function () { <?php echo 'return \''._('No matching records found').'\';'.PHP_EOL; ?>
    },
    formatPaginationSwitch: function () { <?php echo 'return \''._('Hide/Show pagination').'\';'.PHP_EOL; ?>
    },
    formatRefresh: function () { <?php echo 'return \''._('Refresh').'\';'.PHP_EOL; ?>
    },
    formatToggle: function () { <?php echo 'return \''._('Toggle').'\';'.PHP_EOL; ?>
    },
    formatColumns: function () { <?php echo 'return \''._('Columns').'\';'.PHP_EOL; ?>
    }
  };
  $.extend($.fn.bootstrapTable.defaults, locale_strings);
})(jQuery);