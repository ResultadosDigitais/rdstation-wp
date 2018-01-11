<?php

add_filter('the_content', 'rdscript_display_hook',  10);

add_action('wp_head', 'rdscript_display_hook_header');
add_action('wp_footer', 'rdscript_display_hook_footer');


// execute the scripts on page and single posts
function rdscript_display_hook_header() {
  global $post;

  if(is_single() || is_page()) {
    echo html_entity_decode(get_post_meta($post->ID, '_rdscriptcontentinhead', true));
  }

  echo get_option('rdscript_all_head');
  return;
}

function rdscript_display_hook_footer() {
  global $post;
  if(is_single() || is_page()) {
    echo html_entity_decode(get_post_meta($post->ID, '_rdscriptcontentinfooter', true));
  }

  echo get_option('rdscript_all_body');
  return;
}


function rdscript_display_hook($content='') {
	global $post;
  $contents=$content;

  if(is_single() || is_page()) {
    $contents= html_entity_decode(get_post_meta($post->ID, '_rdscriptcontenttop', true)) . $content . html_entity_decode(get_post_meta($post->ID, '_rdscriptcontentbottom', true));
  }

  return $contents;
}

//Displays a box that allows users to insert the scripts for the post or page
function rdscript_metaboxs($post) {
  // Use nonce for verification
  wp_nonce_field( plugin_basename( __FILE__ ), 'rd_noncename' );
	?>

  <label for="rdscriptcontentinhead">
    <?php _e('Scripts added here will be inserted inside the <strong><code>&lt;head&gt</code> tag</strong>','integracao-rd-station') ?>
  </label>

  <br/>

  <textarea style="width:100%; min-height: 50px;" id="rdscriptcontentinhead" name="rdscriptcontentinhead" />
    <?php echo html_entity_decode(get_post_meta($post->ID,'_rdscriptcontentinhead',true)); ?>
  </textarea>

  <br/>

  <label for="rdscriptcontentinfooter">
    <?php _e('Scripts added here will be inserted <strong>before closing &lt;/body&gt; tag</strong>','integracao-rd-station') ?>
  </label>

  <br/>

  <textarea style="width:100%; min-height: 150px;" id="rdscriptcontentinfooter" name="rdscriptcontentinfooter" />
    <?php echo html_entity_decode(get_post_meta($post->ID,'_rdscriptcontentinfooter',true)); ?>
  </textarea>

  	<?php
}

//Add the meta box to post and page
function rd_custom_script_meta_box() {
	add_meta_box(
    'rd_custom_script',
    __('Add RD Station scripts', 'integracao-rd-station'),
    'rdscript_metaboxs',
    'post',
    'advanced'
  );

	add_meta_box(
    'rd_custom_script',
    __('Add RD Station scripts', 'integracao-rd-station'),
    'rdscript_metaboxs',
    'page',
    'advanced'
  );
}
add_action('admin_menu', 'rd_custom_script_meta_box');

// When the post is updating, save the script.

function rdscript_updates($pID) {
  if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;

  $rd_noncename = isset($_POST['rd_noncename']) ? $_POST['rd_noncename'] : '';
  if (!wp_verify_nonce($rd_noncename, plugin_basename( __FILE__ ))) return;

  if ( 'page' == $_POST['post_type'] )
  {
    if ( !current_user_can( 'edit_page', $pID ) )
        return;
  }
  else
  {
    if ( !current_user_can( 'edit_post', $pID ) )
        return;
  }

  // update the meta datas here
  $text = (isset($_POST['rdscript_content_top'])) ? $_POST['rdscript_content_top'] : '';
 $text= str_replace("\n", '', $text);
  $text= esc_js($text);
  update_post_meta($pID, '_rdscriptcontenttop', $text);

  $text = (isset($_POST['rdscript_content_bottom'])) ? $_POST['rdscript_content_bottom'] : '';
   $text= str_replace("\n", '', $text);
  $text= esc_js($text);
update_post_meta($pID, '_rdscriptcontentbottom', $text);

    $text = (isset($_POST['rdscriptcontentinhead'])) ? $_POST['rdscriptcontentinhead'] : '';
     $text= str_replace("\n", '', $text);
  $text= esc_js($text);
update_post_meta($pID, '_rdscriptcontentinhead', $text);

  $text = (isset($_POST['rdscriptcontentinfooter'])) ? $_POST['rdscriptcontentinfooter'] : '';
 $text= str_replace("\n", '', $text);
  $text= esc_js($text);
  update_post_meta($pID, '_rdscriptcontentinfooter', $text);
}
add_action('save_post', 'rdscript_updates');
