function RDSMGeneralSettings() {
  this.elements = {
    trackingCodeCheckbox: document.getElementById('rdsm-enable-tracking'),
    trackingCodeWarning: document.getElementById('rdsm-tracking-warning'),
    connectedAccount: document.querySelector('.rdsm-connected'),
    disconnectedAccount: document.querySelector('.rdsm-disconnected')
  };

  this.toggleElementsDisplay = function() {
    var settingElements = this;
    jQuery.ajax({
      url: ajaxurl,
      method: 'POST',
      data: { action: 'rdsm-authorization-check' },
      success: function(data) {
        if (data.token) {
          settingElements.displayConnectedAccountElements();
        } else {
          settingElements.displayDisconnectedAccountElements();
        }
      }
    });
  }

  this.displayDisconnectedAccountElements = function() {
    var elements = this.elements;
    elements.connectedAccount.classList.add('hidden');
    elements.disconnectedAccount.classList.remove('hidden');
    elements.trackingCodeCheckbox.setAttribute('disabled', 'disabled');
    elements.trackingCodeWarning.classList.remove('hidden');
  }

  this.displayConnectedAccountElements = function() {
    var elements = this.elements;
    elements.connectedAccount.classList.remove('hidden');
    elements.disconnectedAccount.classList.add('hidden');
    elements.trackingCodeCheckbox.removeAttribute('disabled');
    elements.trackingCodeWarning.classList.add('hidden');
  }
}
