(function RDSMTrackingCode() {
  function bindTrackingCodeCheckbox() {
    var trackingCodeCheckbox = document.getElementById('rdsm-enable-tracking');
    trackingCodeCheckbox.onchange = toggleTrackingCode;
  }

  function toggleTrackingCode() {
    jQuery.ajax({
      url: ajaxurl,
      method: 'POST',
      data: {
        action: 'rdsm-update-tracking-code-status',
        checked: this.checked
      }
    });
  }

  function init() {
    bindTrackingCodeCheckbox();
  }

  window.addEventListener('load', init);
})();
