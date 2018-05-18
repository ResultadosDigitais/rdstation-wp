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

class RDContactForm7Integration {
  const PLUGIN_DESCRIPTION = 'Plugin Contact Form 7';

  public $form_data = array(
    'form_origem' => PLUGIN_DESCRIPTION
  );

  public function __construct($conversion) {
    $this->conversion = $conversion;
  }

  public function send_lead_conversion($submitted_form){
    $integrations = new RDSMIntegrations;
    $cf7_integrations = $integrations->get('rdcf7_integrations');

    foreach ($cf7_integrations as $integration) {
      $form_id = get_post_meta($integration->ID, 'form_id', true);
      if ($form_id == $submitted_form->id() ) {
        $submission = WPCF7_Submission::get_instance();
        if ($submission) $this->form_data = $submission->get_posted_data();
        $payload = $conversion->build_payload($integration->ID, $this->form_data);
        $conversion->send($payload);
      }
    }
  }
}
