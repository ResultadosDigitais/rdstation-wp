function RDSMLogFile() {

  this.loadLogFile = function() {
    jQuery.ajax({
      url: ajaxurl,
      method: 'POST',
      data: { action: 'rdsm-log-file' },
      success: function(data) {
        data.forEach(renderLogScreen);
      }
    });
  }

  function renderLogScreen(log) {
    debugger;
  }
}

function load() {
  logFile = new RDSMLogFile();  
  logFile.loadLogFile();
}

window.addEventListener('DOMContentLoaded', load);
