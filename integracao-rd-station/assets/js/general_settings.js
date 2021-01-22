function RDSMGeneralSettings() {
  this.elements = {
    trackingCodeCheckbox: document.getElementById('rdsm-enable-tracking'),
    trackingCodeWarning: document.getElementById('rdsm-tracking-warning'),
    connectedAccount: document.querySelector('.rdsm-connected'),
    connectedBox: document.querySelector('.rdsm-connected-box'),
    disconnectedAccount: document.querySelector('.rdsm-disconnected'),
    disconnectedBox: document.querySelector('.rdsm-disconnected-box')
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
          settingElements.displayConnectedbox();
        } else {
          settingElements.displayDisconnectedAccountElements();
          settingElements.displayDisconnectedBox();
        }
      }
    });
  }

  this.displayDisconnectedAccountElements = function() {
    var elements = this.elements;
    elements.connectedAccount.classList.add('hidden');
    elements.disconnectedAccount.classList.remove('hidden');
    elements.connectedBox.classList.add('hidden');
    elements.disconnectedBox.classList.remove('hidden');
    elements.trackingCodeCheckbox.setAttribute('disabled', 'disabled');
    elements.trackingCodeWarning.classList.remove('hidden');
  }

  this.displayConnectedAccountElements = function() {
    var elements = this.elements;
    elements.connectedAccount.classList.remove('hidden');
    elements.disconnectedAccount.classList.add('hidden');
    elements.disconnectedBox.classList.add('hidden');
    elements.connectedBox.classList.remove('hidden');
    elements.trackingCodeCheckbox.removeAttribute('disabled');
    elements.trackingCodeWarning.classList.add('hidden');
  }

  this.displayDisconnectedBox = function() {
    var elements = this.elements;
    elements.connectedBox.classList.add('hidden');
    elements.disconnectedBox.classList.remove('hidden');
  }

  this.displayConnectedBox = function() {
    var elements = this.elements;
    elements.disconnectedBox.classList.add('hidden');
    elements.connectedBox.classList.remove('hidden');
  }
}
