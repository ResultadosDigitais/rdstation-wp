function RDSMCustomFields() {

  this.checkAuthenticationRDSM = function() {
    jQuery.ajax({
      url: ajaxurl,
      method: 'POST',
      data: { action: 'rdsm-authorization-check' },
      success: function(data) {
        if (data.token) {
          displayConnectedAccountElements();
          loadMappingFields();
        } else {
          displayDisconnectedAccountElements();
        }
      }
    });
  }

  function getCustomFieldsByFormId(form_id, type, post_id) {
    jQuery.ajax({
      url: ajaxurl,
      method: 'POST',
      data: { action: 'rdsm-custom-fields', form_id: form_id, type: type, post_id: post_id },
      success: function(data) {
        if (data != null){
          renderFieldMapping(data, type, form_id);
          
          if (data["mapped_fields"]) {
            hideMappedFieldsAlert();
          }else {
            showMappedFieldsAlert();
          }
        }
      }
    });
  }

  function loadMappingFields() {
    var selectedForm = document.getElementById("forms_select");
    var type = selectedForm.dataset.integrationType;
    var post_id = selectedForm.dataset.postId;

    getCustomFieldsByFormId(selectedForm.value, type, post_id);
    selectedForm.onchange = function() {
      getCustomFieldsByFormId(selectedForm.value, type, post_id);
    }
  }

  function displayDisconnectedAccountElements() {
    document.getElementById('map_fields_title').classList.add('hidden');
    document.getElementById('info_check_login').classList.remove('hidden');
  }

  function displayConnectedAccountElements() {
    document.getElementById('map_fields_title').classList.remove('hidden');
    document.getElementById('info_check_login').classList.add('hidden');
  }

  function showMappedFieldsAlert() {
    document.getElementById('info_mapped_fields').classList.remove('hidden');
  }

  function hideMappedFieldsAlert() {
    document.getElementById('info_mapped_fields').classList.add('hidden');
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
      html += "<p class=\"rd-fields-mapping\">\
                <span class=\"rd-fields-mapping-label\">" + fields[i]["label"] + "</span> \
                <span class=\"dashicons dashicons-arrow-right-alt\"></span>\
                <select name=\""+initials+"_mapped_fields["+fields[i]["id"]+"]\">\
                  <option value=\"\"></option>\
                  <option value=\"company_name\">Nome da Empresa</option>\
                  <option value=\"company_site\">Site da Empresa</option>\
                  <option value=\"company_address\">Endereço da Empresa</option>"                  
                  + select + 
                  "<option value=\"communications\">Consentimento de Comunicação</option>\
                </select>\
              </p>";
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

function showInfoCreateFieldRDSM(value) {
  var info_box = document.getElementById("info_create_fields");
  info_box.classList.remove("hidden");    
}

function load() {
  customFields = new RDSMCustomFields();  
  customFields.checkAuthenticationRDSM();
}

window.addEventListener('DOMContentLoaded', load);
