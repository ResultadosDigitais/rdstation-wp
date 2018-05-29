var RDSMGeneralSettings = (function RDSMGeneralSettings() {
  var elements = {};

  window.onload = loadElements;

  function loadElements() {
    elements.connectButton = document.getElementById('rdsm-oauth-connect')
    elements.disconnectButton = document.getElementById('rdsm-oauth-disconnect')
    elements.trackingCodeCheckbox = document.getElementById('rdsm-enable-tracking')
    elements.trackingCodeWarning = document.getElementById('rdsm-tracking-warning')
  }

  function toggleElementsDisplay() {
    jQuery.ajax({
      url: ajaxurl,
      method: 'POST',
      data: { action: 'rdsm-authorization-check' },
      success: function(data) {
        if (data.token) {
          displayDisconnectedAccountElements();
        } else {
          displayConnectedAccountElements();
        }
      }
    });
  }

  function displayConnectedAccountElements() {
    elements.trackingCodeCheckbox.setAttribute('disabled', 'disabled');
    elements.trackingCodeWarning.classList.remove('hidden');
    elements.disconnectButton.classList.add('hidden');
    elements.connectButton.classList.remove('hidden');
  }

  function displayDisconnectedAccountElements() {
    elements.trackingCodeCheckbox.removeAttribute('disabled');
    elements.trackingCodeWarning.classList.add('hidden');
    elements.disconnectButton.classList.remove('hidden');
    elements.connectButton.classList.add('hidden');
  }

  return {
    toggleElementsDisplay: toggleElementsDisplay,
    displayConnectedAccountElements: displayConnectedAccountElements,
    displayDisconnectedAccountElements: displayDisconnectedAccountElements
  };
})();
