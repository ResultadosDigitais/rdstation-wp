function RDSMCustomFields() {
  
  this.formsSelectChanged = function() {
    var selectedForm = document.getElementById("forms_select");
    getCustomFieldsByFormId(selectedForm.value);
    selectedForm.onchange = function() {
      getCustomFieldsByFormId(selectedForm.value);
    }
  }
  
  function getCustomFieldsByFormId(form_id) {
    jQuery.ajax({
      url: ajaxurl,
      method: 'POST',
      data: { action: 'rdsm-custom-fields', form_id: form_id },
      success: function(data) {  
        document.getElementById("custom_fields").innerHTML = data;
      }
    });
  }
}

function load() {
  customFields = new RDSMCustomFields();  
  customFields.formsSelectChanged();
}

window.addEventListener('DOMContentLoaded', load);