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
	        'name'                  => __( 'Todas integrações: RD Station + ' . $this->acronym, 'rdstation-wp'),
	        'singular_name'         => __( 'Integração ' . $this->acronym, 'rdstation-wp' ),
	        'add_new'               => __( 'Criar integração', 'rdstation-wp' ),
	        'add_new_item'          => __( 'Criar Nova Integração', 'rdstation-wp' ),
	        'edit_item'             => __( 'Editar Integração', 'rdstation-wp' ),
	        'new_item'              => __( 'Nova Integração', 'rdstation-wp' ),
	        'all_items'             => __( 'Todas Integrações', 'rdstation-wp' ),
	        'view_item'             => __( 'Ver Integrações', 'rdstation-wp' ),
	        'search_items'          => __( 'Procurar Integrações', 'rdstation-wp' ),
	        'not_found'             => __( 'Nenhuma integração encontrada', 'rdstation-wp' ),
	        'not_found_in_trash'    => __( 'Nenhuma integração encontrada na lixeira', 'rdstation-wp' ),
	        'parent_item_colon'     => '',
	        'menu_name'             => 'RD Station '.$this->acronym
	    );

	    $args = array(
	        'labels'                => $labels,
	        'description'           => __('Integração do ' . $this->name . ' com o RD Station', 'rdstation-wp'),
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
