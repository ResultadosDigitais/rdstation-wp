function RDSMWooCommerceFields() {
  
  this.getFields = function() {
    jQuery.ajax({
      url: ajaxurl,
      method: 'POST',
      data: { action: 'rdsm-woocommerce-fields' },
      success: function(data) {
        var html = "";
        var select = "";

        for (i = 0; i < data["select_items"].length; i++) {
          select += "<option value=" + data["select_items"][i]["value"] + ">" + data["select_items"][i]["value"] + "</option>";
        }

        document.getElementById("rdsm_fields").innerHTML = getWooCommerceHTML(data, select);
      }
    });
  }

  function getWooCommerceHTML(data, select) {
    var html = "";
    for (i = 0; i < data["fields_woocommerce"].length; i++) {
      html += "<p class=\"rd-fields-mapping\"><span class=\"rd-fields-mapping-label\" style=\"float: left; width: 200px; color: #4f6d83; font-weight: bold; margin-top: 4px;\">" + data["fields_woocommerce"][i] + 
              "</span> <span class=\"dashicons dashicons-arrow-right-alt\" style=\"line-height: unset; margin-right: 15px;\"></span><select name=\"gf_mapped_fields["+data["fields_woocommerce"][i]+"]\"><option value=\"\"></option>" + select + "</select></p>";          
    }
    return html;
  }  
}

function load() {
  wooCommerceFields = new RDSMWooCommerceFields();  
  wooCommerceFields.getFields();
}

window.addEventListener('DOMContentLoaded', load);
