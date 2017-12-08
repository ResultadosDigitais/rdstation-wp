<?php

class RDCustomPostType {

  private $text_domain = 'rdstation-wp';

  public function __construct($slug) {
    $this->slug = $slug;
    require_once("metaboxes/$this->slug.php");
  }

  public function init(){
    add_action( 'init', array($this, 'rd_custom_post_type' ));
  }

	public function rd_custom_post_type() {
	    $labels = array(
	        'name'                  => __( 'Todas integrações: RD Station + ' . $this->acronym, $this->text_domain),
	        'singular_name'         => __( 'Integração ' . $this->acronym, $this->text_domain ),
	        'add_new'               => __( 'Criar integração', $this->text_domain ),
	        'add_new_item'          => __( 'Criar Nova Integração', $this->text_domain ),
	        'edit_item'             => __( 'Editar Integração', $this->text_domain ),
	        'new_item'              => __( 'Nova Integração', $this->text_domain ),
	        'all_items'             => __( 'Todas Integrações', $this->text_domain ),
	        'view_item'             => __( 'Ver Integrações', $this->text_domain ),
	        'search_items'          => __( 'Procurar Integrações', $this->text_domain ),
	        'not_found'             => __( 'Nenhuma integração encontrada', $this->text_domain ),
	        'not_found_in_trash'    => __( 'Nenhuma integração encontrada na lixeira', $this->text_domain ),
	        'parent_item_colon'     => '',
	        'menu_name'             => 'RD Station '.$this->acronym
	    );

	    $args = array(
	        'labels'                => $labels,
	        'description'           => __('Integração do ' . $this->name . ' com o RD Station', $this->text_domain),
	        'public'                => true,
	        'menu_position'         => 50,
	        'supports'              => array( 'title' ),
	        'has_archive'           => false,
	        'exclude_from_search'   => true,
	        'show_in_admin_bar'     => false,
	        'show_in_nav_menus'     => false,
	        'publicly_queryable'    => false,
	        'query_var'             => false
	    );
	    if (is_plugin_active($this->plugin_path)) register_post_type( $this->slug.'_integrations', $args );

	    $class = strtoupper($this->slug);
	    $metabox = new $class($this->slug);
	}
}

?>
