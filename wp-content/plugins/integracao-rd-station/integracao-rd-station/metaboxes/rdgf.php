<?php

	require_once('RD_Metabox.php');

	class RDGF extends RD_Metabox {

		function form_id_box_content(){
	    $form_id = get_post_meta(get_the_ID(), 'form_id', true);
	    $gForms = RGFormsModel::get_forms( null, 'title' );

			if( !$gForms ) : ?>
				<p><?php _e("No forms have been found. <a href='admin.php?page=gf_new_form'>Click here to create a new one.</a>", 'integracao-rd-station')?></p>
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
						<h4><?php _e('Map the fields below according to their names in RD Station.', 'integracao-rd-station') ?></h4>
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
