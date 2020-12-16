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
        renderFieldMapping(data, type);
      }
    });
  }

  function renderFieldMapping(fieldMapping, type) {
    var select = "";

    for (i = 0; i < fieldMapping["select_items"].length; i++) {
      select += "<option value=" + fieldMapping["select_items"][i]["value"] + ">" + fieldMapping["select_items"][i]["value"] + "</option>";
    }

    if (type == "contact_form_7") {
      document.getElementById("custom_fields").innerHTML = getContactForm7HTML(fieldMapping, select);
    }else if (type == "gravity_forms") {
      document.getElementById("custom_fields").innerHTML = getGravityFormHTML(fieldMapping, select);
      setSelectedItems(fieldMapping);
    }
  }

  function getContactForm7HTML(data, select) {
    var html = "";
    for (i = 0; i < data["fields_contact_form_7"].length; i++) {
      html += "<p class=\"rd-fields-mapping\"><span class=\"rd-fields-mapping-label\">" + data["fields_contact_form_7"][i] + 
              "</span> <span class=\"dashicons dashicons-arrow-right-alt\"></span><select name=\"gf_mapped_fields["+data["fields_contact_form_7"][i]+"]\"><option value=\"\"></option>" + select + "</select></p>";          
    }
    return html;
  }

  function getGravityFormHTML(data, select) {
    var html = "";
    for (i = 0; i < data["fields_gravity_forms"].length; i++) {
      html += "<p class=\"rd-fields-mapping\"><span class=\"rd-fields-mapping-label\">" + data["fields_gravity_forms"][i]["label"] + 
              "</span> <span class=\"dashicons dashicons-arrow-right-alt\"></span><select name=\"gf_mapped_fields["+data["fields_gravity_forms"][i]["id"]+"]\"><option value=\"\"></option>" + select + "</select></p>";
    }
    return html;
  }

  function setSelectedItems(data){
    for (i = 0; i < data["fields_gf"].length; i++) {
      select = document.getElementsByName("gf_mapped_fields["+data["fields_gf"][i]["id"]+"]")[0];
      select.value = data["fields_gf"][i]["value"];
    }
  }  
}

function load() {
  customFields = new RDSMCustomFields();  
  customFields.formsSelectChanged();
}

window.addEventListener('DOMContentLoaded', load);
