function RDSMCustomFields() {
  
  this.formsSelectChanged = function() {
    var selectedForm = document.getElementById("forms_select");
    var type = selectedForm.dataset.integrationType;
    var post_id = selectedForm.dataset.postId;

    getCustomFieldsByFormId(selectedForm.value, type, post_id);
    selectedForm.onchange = function() {
      getCustomFieldsByFormId(selectedForm.value, type, post_id);
    }
  }

  function getCustomFieldsByFormId(form_id, type, post_id) {
    jQuery.ajax({
      url: ajaxurl,
      method: 'POST',
      data: { action: 'rdsm-custom-fields', form_id: form_id, type: type, post_id: post_id },
      success: function(data) {
        renderFieldMapping(data, type, form_id);
      }
    });
  }

  function renderFieldMapping(fieldMapping, type, form_id) {
    var select = "";

    for (i = 0; i < fieldMapping["select_items"].length; i++) {
      select += "<option value=" + fieldMapping["select_items"][i]["api_identifier"] + ">" + fieldMapping["select_items"][i]["value"] + "</option>";
    }

    if (type == "contact_form_7") {
      document.getElementById("custom_fields").innerHTML = getIntegrationFormHTML(fieldMapping, select, type, "cf7", form_id);
      setSelectedItems(fieldMapping, type, "cf7", form_id);
    }else if (type == "gravity_forms") {
      document.getElementById("custom_fields").innerHTML = getIntegrationFormHTML(fieldMapping, select, type, "gf", form_id);
      setSelectedItems(fieldMapping, type, "gf", form_id);
    }
  }

  function getIntegrationFormHTML(data, select, integrationType, initials, form_id) {
    var html = "";
    var fields = data["fields_" + integrationType];
    for (i = 0; i < fields.length; i++) {
      html += "<p class=\"rd-fields-mapping\"><span class=\"rd-fields-mapping-label\">" + fields[i]["label"] + 
              "</span> <span class=\"dashicons dashicons-arrow-right-alt\"></span><select name=\""+initials+"_mapped_fields["+fields[i]["id"]+"]\"><option value=\"\"></option>" + select + "</select></p>";
    }
    return html;
  }

  function setSelectedItems(data, integrationType, initials, form_id){
    var fields = data["fields_" + integrationType];
    for (i = 0; i < fields.length; i++) {
      select = document.getElementsByName(initials + "_mapped_fields["+fields[i]["id"]+"]")[0];
      select.value = fields[i]["value"];
    }
  }  
}

function load() {
  customFields = new RDSMCustomFields();  
  customFields.formsSelectChanged();
}

window.addEventListener('DOMContentLoaded', load);
