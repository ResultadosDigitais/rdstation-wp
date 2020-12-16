function RDSMCustomFields() {
  
  this.formsSelectChanged = function() {
    var selectedForm = document.getElementById("forms_select");
    var type = "wpcf7";
    var post_id = document.getElementById("post_id").innerHTML;
    
    if (selectedForm.classList.contains('wpgf')) {
      type = "wpgf";
    }

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
        var html = "";
        var select = "";

        for (i = 0; i < data["select_items"].length; i++) {
          select += "<option value=" + data["select_items"][i]["value"] + ">" + data["select_items"][i]["value"] + "</option>";
        }

        if (type == "wpcf7") {
          document.getElementById("custom_fields").innerHTML = getCF7HTML(data, select);
        }else {
          document.getElementById("custom_fields").innerHTML = getGFHTML(data, select);
          setSelectedItems(data);
        }
      }
    });
  }

  function getCF7HTML(data, select) {
    var html = "";
    for (i = 0; i < data["fields_cf7"].length; i++) {
      html += "<p class=\"rd-fields-mapping\"><span class=\"rd-fields-mapping-label\">" + data["fields_cf7"][i] + 
              "</span> <span class=\"dashicons dashicons-arrow-right-alt\"></span><select name=\"gf_mapped_fields["+data["fields_cf7"][i]+"]\"><option value=\"\"></option>" + select + "</select></p>";          
    }
    return html;
  }

  function getGFHTML(data, select) {
    var html = "";
    for (i = 0; i < data["fields_gf"].length; i++) {
      html += "<p class=\"rd-fields-mapping\"><span class=\"rd-fields-mapping-label\">" + data["fields_gf"][i]["label"] + 
              "</span> <span class=\"dashicons dashicons-arrow-right-alt\"></span><select name=\"gf_mapped_fields["+data["fields_gf"][i]["id"]+"]\"><option value=\"\"></option>" + select + "</select></p>";
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
