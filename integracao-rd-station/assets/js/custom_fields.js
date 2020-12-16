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
        var html = "";
        var select = "<select name=\"selected_form\"><option value=\"\"></option>";

        for (i = 0; i < data["select_items"].length; i++) {
          select += "<option value=" + data["select_items"][i]["id"] + ">" + data["select_items"][i]["value"] + "</option>";
        }

        select += "</select>";

        for (i = 0; i < data["fields_cf7"].length; i++) {
          html += "<p class=\"rd-fields-mapping\"><span class=\"rd-fields-mapping-label\">" + data["fields_cf7"][i] + "</span> <span class=\"dashicons dashicons-arrow-right-alt\"></span>" + select + "</p>";          
        }

        document.getElementById("custom_fields").innerHTML = html;
      }
    });
  }
}

function load() {
  customFields = new RDSMCustomFields();  
  customFields.formsSelectChanged();
}

window.addEventListener('DOMContentLoaded', load);
