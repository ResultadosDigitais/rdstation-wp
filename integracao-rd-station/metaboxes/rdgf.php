<?php

	require_once('RD_Metabox.php');

	class RDGF extends RD_Metabox {

		function form_id_box_content(){
	    $form_id = get_post_meta(get_the_ID(), 'form_id', true);
	    $gForms = RGFormsModel::get_forms( null, 'title' );

			if( !$gForms ) : ?>
				<p><?php _e("No forms have been found. <a href='admin.php?page=gf_new_form'>Click here to create a new one.</a>", 'integracao-rd-station')?></p>
		  <?php else : ?>
				<?php echo "<select id=\"forms_select\" name=\"form_id\" data-integration-type=\"gravity_forms\" data-post-id=\"" . get_the_ID() . "\">" ?>
					<option value=""> </option>
	            <?php
                foreach($gForms as $gForm){
                  echo "<option value=".$gForm->id.selected( $form_id, $gForm->id, false) .">".$gForm->title."</option>";
                }
	            ?>
	        </select>	
	        <?php if (!empty($form_id)) { ?>
	        	<h4><?php _e('Map the fields below according to their names in RD Station.', 'integracao-rd-station') ?></h4>
	        <?php } ?>
	        <div id="custom_fields"></div>
		    <?php
			endif;
		}
	}

?>
