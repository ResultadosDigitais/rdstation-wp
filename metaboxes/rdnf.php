<?php

	require_once('RD_Metabox.php');
	/* Não vai ser usado pois o NinjaForm não usa custom post */
	class RDNF extends RD_Metabox {

		function form_id_box_content(){
		    $form_id = get_post_meta(get_the_ID(), 'form_id', true);
		    $args = array('post_type' => 'ninja-forms', 'posts_per_page' => 100);
		    $ninjaForms = get_posts( $args );

		    if ( !$ninjaForms ) :
		        echo '<p>Não encontramos nenhum formulário cadastrado, entre no seu plugin de formulário de contato ou <a href="admin.php?page=ninja-forms#new-form">clique aqui para criar um novo.</a></p>';
		    else : ?>
		        <select name="form_id">
		            <option value=""></option>
		                <?php
		                foreach($ninjaForms as $ninjaForm) {
		                    echo "<option value=".$ninjaForm->ID.selected( $form_id, $ninjaForm->ID, false) .">".$ninjaForm->post_title."</option>";
		                }
		                ?>
		        </select>
		    <?php
		    endif;
		}

	}

?>
