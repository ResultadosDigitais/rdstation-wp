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
	        'name'                  => __( 'Todas integrações: RD Station + ' . $this->acronym, 'integracao-rd-station'),
	        'singular_name'         => __( 'Integração ' . $this->acronym, 'integracao-rd-station' ),
	        'add_new'               => __( 'Criar integração', 'integracao-rd-station' ),
	        'add_new_item'          => __( 'Criar Nova Integração', 'integracao-rd-station' ),
	        'edit_item'             => __( 'Editar Integração', 'integracao-rd-station' ),
	        'new_item'              => __( 'Nova Integração', 'integracao-rd-station' ),
	        'all_items'             => __( 'Todas Integrações', 'integracao-rd-station' ),
	        'view_item'             => __( 'Ver Integrações', 'integracao-rd-station' ),
	        'search_items'          => __( 'Procurar Integrações', 'integracao-rd-station' ),
	        'not_found'             => __( 'Nenhuma integração encontrada', 'integracao-rd-station' ),
	        'not_found_in_trash'    => __( 'Nenhuma integração encontrada na lixeira', 'integracao-rd-station' ),
	        'parent_item_colon'     => '',
	        'menu_name'             => 'RD Station '.$this->acronym
	    );

	    $args = array(
	        'labels'                => $labels,
	        'description'           => __('Integração do ' . $this->name . ' com o RD Station', 'integracao-rd-station'),
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
