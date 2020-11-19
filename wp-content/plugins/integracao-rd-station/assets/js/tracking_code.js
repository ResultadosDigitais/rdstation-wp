var RDSMTrackingCode = (function RDSMTrackingCode() {
  function bindTrackingCodeCheckbox() {
    var trackingCodeCheckbox = document.getElementById('rdsm-enable-tracking');

    trackingCodeCheckbox.onchange = function() {
      toggleTrackingCodeCheckbox();
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

  function toggleTrackingCodeCheckbox() {
    var trackingCodeCheckbox = document.getElementById('rdsm-enable-tracking');
    var trackingCodeWarning = document.querySelector('.rdsm-tracking-code-validation-warning');

    if (trackingCodeCheckbox.checked) {
      jQuery('.checkbox-slider-off').addClass('hidden');
      jQuery('.checkbox-slider-on').removeClass('hidden');
      trackingCodeWarning.classList.remove('hidden');
    } else {
      jQuery('.checkbox-slider-on').addClass('hidden');
      jQuery('.checkbox-slider-off').removeClass('hidden');
      trackingCodeWarning.classList.add('hidden');
    }
  }

  function init() {
    bindTrackingCodeCheckbox();
    toggleTrackingCodeCheckbox();
  }

  return {
    init: init
  }
})();

window.addEventListener('load', RDSMTrackingCode.init);
