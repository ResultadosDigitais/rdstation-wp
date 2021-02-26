function RDSMLogFile() {
  var rdsm_log_screen = document.getElementById("rdsm_log_screen");

  this.loadLogFile = function() {
    jQuery.ajax({
      url: ajaxurl,
      method: 'POST',
      data: { action: 'rdsm-log-file' },
      success: function(data) {
        debugger;
        data.forEach(renderLogScreen);
      }
    });
  }

  function renderLogScreen(log) {    
    rdsm_log_screen.value += log;
  }
}

function copyLogToClipboard() {
  var copyLog = document.getElementById("rdsm_log_screen");
  copyLog.select();
  copyLog.setSelectionRange(0, 99999);
  document.execCommand("copy");
}

function load() {
  logFile = new RDSMLogFile();  
  logFile.loadLogFile();
}

window.addEventListener('DOMContentLoaded', load);
