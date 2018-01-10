<?php

class RD_Metabox {
	public function __construct($plugin_prefix){
		$this->plugin_prefix = $plugin_prefix;
		add_action( 'add_meta_boxes', array($this, 'rd_create_meta_boxes' ) );
		add_action( 'save_post', array($this, 'rd_save_meta_boxes' ) );
	}

	public function rd_create_meta_boxes(){
		add_meta_box(
      'form_identifier_box',
      __('Conversion identifier', 'integracao-rd-station'),
      array($this, 'form_identifier_box_content'),
      $this->plugin_prefix.'_integrations',
      'normal'
	  );

    add_meta_box(
      'token_rdstation_box',
      __('RD Station Token', 'integracao-rd-station'),
      array($this, 'token_rdstation_box_content'),
      $this->plugin_prefix.'_integrations',
      'normal'
    );

	  add_meta_box(
      'form_id_box',
      __('Select a form to integrate with RD Station', 'integracao-rd-station'),
      array($this, 'form_id_box_content'),
      $this->plugin_prefix.'_integrations',
      'normal'
	  );
	}

	public function form_identifier_box_content() {
	    $identifier = get_post_meta(get_the_ID(), 'form_identifier', true);
	    $use_post_title = get_post_meta(get_the_ID(), 'use_post_title', true); ?>
	    <input type="text" name="form_identifier" value="<?php echo $identifier; ?>">
	    <span class="rd-integration-tips">
				<?php _e('This identifier will help you to identify the Lead source.', 'integracao-rd-station') ?>
			</span>
	    <?php
	}

	public function token_rdstation_box_content(){
	    $token = get_post_meta(get_the_ID(), 'token_rdstation', true); ?>
	    <input type="text" name="token_rdstation" size="32" value="<?php echo $token ?>">
	    <span class="rd-integration-tips">
				<a href="https://app.rdstation.com.br/integracoes" target="blank"><?php _e('Click here to get your token', 'integracao-rd-station') ?></a></span>
	    <?php
	}

	public function rd_save_meta_boxes($post_id){
		if ( isset( $_POST['form_identifier'] ) ) update_post_meta( $post_id, 'form_identifier', $_POST['form_identifier'] );
		if ( isset( $_POST['use_post_title'] ) )  update_post_meta( $post_id, 'use_post_title', $_POST['use_post_title'] );
		if ( isset( $_POST['token_rdstation'] ) ) update_post_meta( $post_id, 'token_rdstation', $_POST['token_rdstation'] );
		if ( isset( $_POST['form_id'] ) ) update_post_meta( $post_id, 'form_id', $_POST['form_id'] );
		if ( isset( $_POST['gf_mapped_fields'] ) ) update_post_meta( $post_id, 'gf_mapped_fields', $_POST['gf_mapped_fields'] );
	}
}

?>
