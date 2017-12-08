<?php

	require_once('RD_Metabox.php');

	class RDGF extends RD_Metabox {

		function form_id_box_content(){
	    $form_id = get_post_meta(get_the_ID(), 'form_id', true);
	    $gForms = RGFormsModel::get_forms( null, 'title' );

			if( !$gForms ) : ?>
				<p><?php _e('Não encontramos nenhum formulário cadastrado, entre no seu plugin de formulário de contato ou <a href="admin.php?page=gf_new_form">clique aqui para criar um novo.</a>', 'rdstation-wp') ?></p>
		  <?php else : ?>
				<div class="rd-select-form">
					<select name="form_id">
						<option value=""> </option>
	            <?php
                foreach($gForms as $gForm){
                  echo "<option value=".$gForm->id.selected( $form_id, $gForm->id, false) .">".$gForm->title."</option>";
                }
	            ?>
	        </select>
		    </div>
		    <?php
		    $gf_forms = GFAPI::get_forms();
				$form_map = get_post_meta(get_the_ID(), 'gf_mapped_fields', true);

				foreach ($gf_forms as $form) {
					if ($form['id'] == $form_id) { ?>
						<h4><?php _e('Como os campos abaixo irão se chamar no RD Station?', 'rdstation-wp') ?></h4>
						<?php foreach ($form['fields'] as $field) {
							if(!empty($form_map[$field['id']])){
								$value = $form_map[$field['id']];
							}
							else {
								$value = '';
							}
							echo '<p class="rd-fields-mapping"><span class="rd-fields-mapping-label">' . $field['label'] . '</span> <span class="dashicons dashicons-arrow-right-alt"></span> <input type="text" name="gf_mapped_fields['.$field['id'].']" value="'.$value.'">';
						}
					}
				}
			endif;
		}
	}

?>
