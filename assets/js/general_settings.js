var RDSMGeneralSettings = (function RDSMGeneralSettings() {
  var elements = {};

  window.onload = loadElements;

  function loadElements() {
    elements.trackingCodeCheckbox = document.getElementById('rdsm-enable-tracking')
    elements.trackingCodeWarning = document.getElementById('rdsm-tracking-warning')
    elements.connectedAccount = document.querySelector('.rdsm-connected');
    elements.disconnectedAccount = document.querySelector('.rdsm-disconnected');
  }

  function toggleElementsDisplay() {
    jQuery.ajax({
      url: ajaxurl,
      method: 'POST',
      data: { action: 'rdsm-authorization-check' },
      success: function(data) {
        if (data.token) {
          displayConnectedAccountElements();
        } else {
          displayDisconnectedAccountElements();
        }
      }
    });
  }

  function displayDisconnectedAccountElements() {
    elements.connectedAccount.classList.add('hidden');
    elements.disconnectedAccount.classList.remove('hidden');
    elements.trackingCodeCheckbox.setAttribute('disabled', 'disabled');
    elements.trackingCodeWarning.classList.remove('hidden');
  }

  function displayConnectedAccountElements() {
    elements.connectedAccount.classList.remove('hidden');
    elements.disconnectedAccount.classList.add('hidden');
    elements.trackingCodeCheckbox.removeAttribute('disabled');
    elements.trackingCodeWarning.classList.add('hidden');
  }

  return {
    toggleElementsDisplay: toggleElementsDisplay,
    displayConnectedAccountElements: displayConnectedAccountElements,
    displayDisconnectedAccountElements: displayDisconnectedAccountElements
  };
})();
