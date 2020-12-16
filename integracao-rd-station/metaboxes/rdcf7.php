<?php

	require_once('RD_Metabox.php');

	class RDCF7 extends RD_Metabox {

		function form_id_box_content(){
		    $form_id = get_post_meta(get_the_ID(), 'form_id', true);
		    $args = array('post_type' => 'wpcf7_contact_form', 'posts_per_page' => 100);
		    $cf7Forms = get_posts( $args );

		    if ( !$cf7Forms ) : ?>
		    <p><?php _e("No forms have been found. <a href='admin.php?page=wpcf7-new'>Click here to create a new one.</a>", 'integracao-rd-station')?></p>
		    <?php else : ?>
		        <select id="forms_select" name="form_id" data-integration-type="contact_form_7" data-post-id="">
		            <option value=""></option>
		                <?php
		                foreach($cf7Forms as $cf7Form) {
		                    echo "<option value=".$cf7Form->ID.selected( $form_id, $cf7Form->ID, false) .">".$cf7Form->post_title."</option>";
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