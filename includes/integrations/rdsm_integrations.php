<?php

class RDSMIntegrations {
  public function get($type){
    $args = array(
      'post_type' => $type,
      'posts_per_page' => 100
    );
    return get_posts($args);
  }
}
