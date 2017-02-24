<?php

class RDCustomPostType {

  public function __construct($slug) {
    $this->slug = $slug;
    require_once("metaboxes/$this->slug.php");
  }

  public function init(){
    add_action( 'init', array($this, 'rd_custom_post_type' ));
  }

	public function rd_custom_post_type() {
	    $labels = array(
	        'name'                  => _x( 'Integrações '.$this->acronym, 'post_type_general_name' ),
	        'singular_name'         => _x( 'Integração '.$this->acronym, 'post_type_singular_name' ),
	        'add_new'               => _x( 'Criar integração', 'integration' ),
	        'add_new_item'          => __( 'Criar Nova Integração' ),
	        'edit_item'             => __( 'Editar Integração' ),
	        'new_item'              => __( 'Nova Integração' ),
	        'all_items'             => __( 'Todas Integrações' ),
	        'view_item'             => __( 'Ver Integrações' ),
	        'search_items'          => __( 'Procurar Integrações' ),
	        'not_found'             => __( 'Nenhuma integração encontrada' ),
	        'not_found_in_trash'    => __( 'Nenhuma integração encontrada na lixeira' ),
	        'parent_item_colon'     => '',
	        'menu_name'             => 'RD Station '.$this->acronym
	    );

	    $args = array(
	        'labels'                => $labels,
	        'description'           => 'Integração do '.$this->name.' com o RD Station',
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
