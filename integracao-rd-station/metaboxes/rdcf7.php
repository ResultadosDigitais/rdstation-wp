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
		        <?php echo "<select id=\"forms_select\" name=\"form_id\" data-integration-type=\"contact_form_7\" data-post-id=\"" . get_the_ID() . "\">" ?>
		            <option value=""></option>
		                <?php
		                foreach($cf7Forms as $cf7Form) {
		                    echo "<option value=".$cf7Form->ID.selected( $form_id, $cf7Form->ID, false) .">".$cf7Form->post_title."</option>";
		                }
		                ?>
		        </select>
		        <h4 id="map_fields_title" class="hidden">
		        	<?php _e('Map the fields below according to their names in RD Station.', 'integracao-rd-station') ?>
		        	<a class="button pull-right" onclick="showInfoCreateFieldRDSM()" href="https://app.rdstation.com.br/campos-personalizados/novo" target="_blank">
		        		<?php _e("Create field in RDSM", 'integracao-rd-station')?>		        		
		        	</a>
		        </h4>
		        <h3 id="info_mapped_fields" class="hidden">
		        	<?php _e('The fields on this form have not yet been mapped, you can configure them below or ', 'integracao-rd-station') ?>
		        	<a href="https://ajuda.rdstation.com.br/hc/pt-br/articles/360054981272" target="_blank" style="color: white;">
		        		<?php _e('click here for more information', 'integracao-rd-station') ?>
		        	</a>
		        </h3>
		        <h3 id="info_check_login" class="hidden">
		        	<?php _e('You need to connect to RD Station to map the fields, ', 'integracao-rd-station') ?>
		        	<a href="options-general.php?page=rdstation-settings-page" style="color: white;">
		        		<?php _e('click here to go to Settings page and than \'Connect to RD Station\'', 'integracao-rd-station') ?>
		        	</a>
		        </h3>		        
		        <h3 id="info_create_fields" class="hidden"><?php _e('To see the fields created in RDSM reload page.', 'integracao-rd-station') ?></h3>	        
		        <?php if (RDSMLogFileHelper::has_error()) { ?>
				    <h3 class="alert-box"><?php _e('There are conversions that returned an error, check the log in \'RD Station Settings\' for more information', 'integracao-rd-station') ?></h3>
				<?php } ?>
		        <div id="custom_fields"></div>
		    <?php		    
		    endif;
		}

	}
?>
