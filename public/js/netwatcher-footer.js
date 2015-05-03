// Sets the events
$(document).ready(function () {
  // Adapt footer size
  AdaptFooter.init();
});

//
// AdaptFootAdaptFooter.er module
//
(function (AdaptFooter, $, undefined) {

  // Optimization flag
  var flag;

  // Long text of the footer
  var textLong;

  // Short text of the footer
  var textShort;

  // Container of the footer text
  var textDiv;

  // Initializes the module
  AdaptFooter.init = function () {
    // Set the internal variables
    flag = true;
    textShort = 'HPCN';
    textLong = 'High-Performance Computing and Networking';
    textContainer = $('#researchGroup');
    AdaptFooter.resizeText();
    flag = false;

    // Set the events
    $(window).resize(function () {
      AdaptFooter.resizeText();
    });
  };

  // Parses a number of bytes into a string
  AdaptFooter.resizeText = function () {
    if ((window.innerWidth < 769) && flag) {
      textContainer.html(textShort);
      flag = false;
    } else if ((window.innerWidth > 768) && (!flag)) {
      textContainer.html(textLong);
      flag = true;
    };
  };

}(window.AdaptFooter = window.AdaptFooter || {}, jQuery));