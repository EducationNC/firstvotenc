/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 *
 * .noConflict()
 * The routing is enclosed within an anonymous function so that you can
 * always reference jQuery with $, even when in .noConflict() mode.
 * ======================================================================== */

(function($) {

  // Use this variable to set up the common and page specific functions. If you
  // rename this variable, you will also need to rename the namespace below.
  var Sage = {
    // All pages
    'common': {
      init: function() {
        // JavaScript to be fired on all pages
      },
      finalize: function() {
        // JavaScript to be fired on all pages, after page specific JS is fired
      }
    },
    // Home page
    'home': {
      init: function() {
        // JavaScript to be fired on the home page
      },
      finalize: function() {
        // JavaScript to be fired on the home page, after the init JS
      }
    },
    // About us page, note the change from about-us to about_us.
    'audit': {
      init: function() {

        // Set up variables to show progress
        window.pollingPeriod = 1000;
        window.updatePeriod  = 250;
        window.lastData = null;

        // Update progress bar
        function updateDisplay(data){
          if ( $('#progress-bar').length < 1 ) {
            $('#script-progress').append($('<div id="progress" class="progress"><div id="progress-bar" class="progress-bar"><span></span></div></div>'));
            $('#progress-bar').css('width', '0%')
              .attr('aria-valuenow', 0)
              .attr('aria-valuemin', 0)
              .attr('aria-valuemax', 100);
            console.log("Created Status Bars");
          }

          var percent = data.percentComplete;

          $('#progress-bar')
            .attr('aria-valuenow', percent*100)
            .css('width', Math.ceil(percent*100) + "%");
          $('#progress-bar span').text(Math.ceil(percent*100) + "%");

        }

        // Check progress of long php script
        function checkProgress(createStatusBars){
          if(typeof createStatusBars === "undefined") {createStatusBars = false;}
          if(window.finished === true) {return;}

          url = countAjax.uploads + "/count-progress.json";

          var d = new Date();
          var n = d.getTime();

          if((n - window.lastUpdate) > window.pollingPeriod || window.lastData == null) {
            // Refetch progress
            $.getJSON(url, function(data){
              var d = new Date();
              window.lastUpdate = d.getTime();
              window.lastData = data;
              updateDisplay(data);
              return null;
            }).fail(function(){
              clearInterval(window.progressInterval);
            });

          } else {
            // Use most recent data
            var data = $.extend({},window.lastData);
            updateDisplay(data);

          }
        }

        // Count votes when button is clicked
        $('#count-votes').on('click', function() {

          // Start the count
          $.ajax({
            type:"POST",
            url: countAjax.ajaxurl,
            data: {
              action: 'do-count',
              countNonce: countAjax.ajaxNonce
            },
            success: function(response) {
              clearInterval(window.progressInterval);

              $('#progress-bar')
                .attr('aria-valuenow', '100')
                .css('width', "100%");
              $('#progress-bar span').text("100%");

              $('#script-progress').append(response);
            },
            error: function(errorThrown){
              console.log(errorThrown);
            }
          });

          // Show progress
          window.progressInterval = setInterval(checkProgress, window.updatePeriod);

        });

      }
    }
  };

  // The routing fires all common scripts, followed by the page specific scripts.
  // Add additional events for more control over timing e.g. a finalize event
  var UTIL = {
    fire: function(func, funcname, args) {
      var fire;
      var namespace = Sage;
      funcname = (funcname === undefined) ? 'init' : funcname;
      fire = func !== '';
      fire = fire && namespace[func];
      fire = fire && typeof namespace[func][funcname] === 'function';

      if (fire) {
        namespace[func][funcname](args);
      }
    },
    loadEvents: function() {
      // Fire common init JS
      UTIL.fire('common');

      // Fire page-specific init JS, and then finalize JS
      $.each(document.body.className.replace(/-/g, '_').split(/\s+/), function(i, classnm) {
        UTIL.fire(classnm);
        UTIL.fire(classnm, 'finalize');
      });

      // Fire common finalize JS
      UTIL.fire('common', 'finalize');
    }
  };

  // Load Events
  $(document).ready(UTIL.loadEvents);

})(jQuery); // Fully reference jQuery after this point.
