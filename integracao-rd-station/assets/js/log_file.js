function RDSMLogFile() {
  var rdsm_log_screen = document.getElementById("rdsm_log_screen");

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
    rdsm_log_screen.value += log;
  }
}

function copyLogToClipboard() {
  var copyLog = document.getElementById("rdsm_log_screen");
  var value = copyLog.value;
  copyLog.value = btoa(value);
  copyLog.select();
  copyLog.setSelectionRange(0, 99999);
  document.execCommand("copy");
  copyLog.value = value;
}

function load() {
  logFile = new RDSMLogFile();  
  logFile.loadLogFile();
}

window.addEventListener('DOMContentLoaded', load);
