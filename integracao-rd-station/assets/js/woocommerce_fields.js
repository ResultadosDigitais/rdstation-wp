function RDSMWooCommerceFields() {

  this.getFields = function() {
    jQuery.ajax({
      url: ajaxurl,
      method: 'POST',
      data: { action: 'rdsm-woocommerce-fields' },
      success: function(data) {
        renderFieldMapping(data);
      }
    });
  }

  function renderFieldMapping(fieldMapping) {
    var select = "";

    for (i = 0; i < fieldMapping["select_items"].length; i++) {
      select += "<option value=" + fieldMapping["select_items"][i]["api_identifier"] + ">" + fieldMapping["select_items"][i]["value"] + "</option>";
    }

    var rdsmFields = document.getElementById("rdsm_fields");
    rdsmFields.innerHTML = getWooCommerceHTML(fieldMapping, select);    

    var fieldsMappingSelected = {
      "nome":         rdsmFields.dataset.nome,
      "sobrenome":    rdsmFields.dataset.sobrenome,
      "email":        rdsmFields.dataset.email,
      "telefone":     rdsmFields.dataset.telefone,
      "empresa":      rdsmFields.dataset.empresa,
      "país":         rdsmFields.dataset.país,
      "endereço":     rdsmFields.dataset.endereço,
      "endereço2":    rdsmFields.dataset.endereço2,
      "cidade":       rdsmFields.dataset.cidade,
      "estado":       rdsmFields.dataset.estado,
      "cep":          rdsmFields.dataset.cep,
      "produtos":     rdsmFields.dataset.produtos
    };

    setSelectedItems(fieldMapping, fieldsMappingSelected);
  }

  function getWooCommerceHTML(data, select) {
    var html = "";
    var fields = data["fields_woocommerce"];
    for (i = 0; i < fields.length; i++) {
      html += "<p class=\"rd-fields-mapping\">\
                <span class=\"rd-fields-mapping-label\" style=\"float: left; width: 200px; color: #4f6d83; font-weight: bold; margin-top: 4px;\">" + fields[i] + "</span> \
                <span class=\"dashicons dashicons-arrow-right-alt\" style=\"line-height: unset; margin-right: 15px;\"></span>\
                <select onchange=\"createFieldsRDSM(this.value)\" name=\"rdsm_woocommerce_settings[field_mapping]["+fields[i]+"]\">\
                  <option value=\"\"></option>\
                  <option value=\"company_name\">Nome da Empresa</option>\
                  <option value=\"company_site\">Site da Empresa</option>\
                  <option value=\"company_address\">Endereço da Empresa</option>"
                  + select + 
                  "</select></p>";
    }
    return html;
  }

  function setSelectedItems(fieldMapping, fieldsMappingSelected){
    var fields = fieldMapping["fields_woocommerce"];
    for (i = 0; i < fields.length; i++) {
      select = document.getElementsByName("rdsm_woocommerce_settings[field_mapping]["+fields[i]+"]")[0];
      select.value = fieldsMappingSelected[fields[i]];
    }
  }  
}

function showInfoCreateFieldRDSM(value) {
  var info_box = document.getElementById("info_create_fields");
  info_box.classList.remove("hidden");    
}

function load() {
  wooCommerceFields = new RDSMWooCommerceFields();
  wooCommerceFields.getFields();
}

window.addEventListener('DOMContentLoaded', load);
