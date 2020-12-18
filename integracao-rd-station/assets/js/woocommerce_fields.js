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
      "billing_first_name": rdsmFields.dataset.billing_first_name,
      "billing_last_name": rdsmFields.dataset.billing_last_name,
      "billing_email": rdsmFields.dataset.billing_email,
      "billing_phone": rdsmFields.dataset.billing_phone,
      "billing_company": rdsmFields.dataset.billing_company,
      "billing_country": rdsmFields.dataset.billing_country,
      "billing_address_1": rdsmFields.dataset.billing_address_1,
      "billing_address_2": rdsmFields.dataset.billing_address_2,
      "billing_city": rdsmFields.dataset.billing_city,
      "billing_state": rdsmFields.dataset.billing_state,
      "billing_postcode": rdsmFields.dataset.billing_postcode
    };

    setSelectedItems(fieldMapping, fieldsMappingSelected);
  }

  function getWooCommerceHTML(data, select) {
    var html = "";
    var fields = data["fields_woocommerce"];
    for (i = 0; i < fields.length; i++) {
      html += "<p class=\"rd-fields-mapping\"><span class=\"rd-fields-mapping-label\" style=\"float: left; width: 200px; color: #4f6d83; font-weight: bold; margin-top: 4px;\">" + fields[i] +
              "</span> <span class=\"dashicons dashicons-arrow-right-alt\" style=\"line-height: unset; margin-right: 15px;\"></span><select name=\"rdsm_woocommerce_settings[field_mapping]["+fields[i]+"]\"><option value=\"\"></option>" + select + "</select></p>";
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

function load() {
  wooCommerceFields = new RDSMWooCommerceFields();  
  wooCommerceFields.getFields();
}

window.addEventListener('DOMContentLoaded', load);
