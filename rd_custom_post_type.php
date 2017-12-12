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
	        'name'                  => __( 'All integrations: RD Station + ' . $this->acronym, 'integracao-rd-station'),
	        'singular_name'         => __( 'Integration ' . $this->acronym, 'integracao-rd-station' ),
	        'add_new'               => __( 'Create integration', 'integracao-rd-station' ),
	        'add_new_item'          => __( 'Create new integration', 'integracao-rd-station' ),
	        'edit_item'             => __( 'Edit integration', 'integracao-rd-station' ),
	        'new_item'              => __( 'New integration', 'integracao-rd-station' ),
	        'all_items'             => __( 'All integrations', 'integracao-rd-station' ),
	        'view_item'             => __( 'View integrations', 'integracao-rd-station' ),
	        'search_items'          => __( 'Search integrations', 'integracao-rd-station' ),
	        'not_found'             => __( 'No integration found', 'integracao-rd-station' ),
	        'not_found_in_trash'    => __( 'No integration found in the trash', 'integracao-rd-station' ),
	        'parent_item_colon'     => '',
	        'menu_name'             => 'RD Station '.$this->acronym
	    );

	    $args = array(
	        'labels'                => $labels,
	        'description'           => __('Integration of ' . $this->name . ' with RD Station', 'integracao-rd-station'),
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
