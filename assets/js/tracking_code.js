var RDSMTrackingCode = (function RDSMTrackingCode() {
  function bindTrackingCodeCheckbox() {
    var trackingCodeCheckbox = document.getElementById('rdsm-enable-tracking');

    trackingCodeCheckbox.onchange = function() {
      toggleTrackingCodeCheckbox(trackingCodeCheckbox);
      updateTrackingCodeStatus(event);
    }
  }

  function updateTrackingCodeStatus(event) {
    jQuery.ajax({
      url: ajaxurl,
      method: 'POST',
      data: {
        action: 'rdsm-update-tracking-code-status',
        checked: event.target.checked
      }
    });
  }

  function toggleTrackingCodeCheckbox(checkbox) {
    if (checkbox.checked) {
      jQuery('.checkbox-slider-off').addClass('hidden');
      jQuery('.checkbox-slider-on').removeClass('hidden');
    } else {
      jQuery('.checkbox-slider-on').addClass('hidden');
      jQuery('.checkbox-slider-off').removeClass('hidden');
    }
  }

  function init() {
    var trackingCodeCheckbox = document.getElementById('rdsm-enable-tracking')
    bindTrackingCodeCheckbox();
    toggleTrackingCodeCheckbox(trackingCodeCheckbox);
  }

  return {
    init: init
  }
})();

window.addEventListener('load', RDSMTrackingCode.init);
