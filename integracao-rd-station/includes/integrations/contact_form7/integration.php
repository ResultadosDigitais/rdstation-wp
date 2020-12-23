<?php

require_once(RDSM_SRC_DIR . '/integrations/rdsm_integrations.php');

class RDContactForm7Integration {
  const PLUGIN_DESCRIPTION = 'Plugin Contact Form 7';
  const MAIL_SENT_TRIGGER = 'wpcf7_mail_sent';

  public $form_data;

  public $default_payload = array(
    'form_origem' => self::PLUGIN_DESCRIPTION
  );

  private $submitted_form_id;

  public function __construct($resource, $api_client) {
    $this->resource = $resource;
    $this->api_client = $api_client;
    $this->integrations = new RDSMIntegrations;
  }

  public function setup() {
    add_filter(self::MAIL_SENT_TRIGGER, array($this, 'send_resource_to_rd'), 10, 2);
  }

  public function send_resource_to_rd($submitted_form){
    $cf7_integrations = $this->integrations->get('rdcf7_integrations');
    $this->submitted_form_id = $submitted_form->id();

    $current_form_integrations = array_filter(
      $cf7_integrations,
      array($this, 'integrations_from_current_form')
    );

    $this->build_default_payload();

    foreach ($current_form_integrations as $integration) {
      $this->resource->build_payload($this->form_data, $integration->ID, 'contact_form_7');
      $this->api_client->post($this->resource);
    }
  }

  private function build_default_payload() {
    $submission = WPCF7_Submission::get_instance();
    if (!$submission) return;
    $this->form_data = array_merge($submission->get_posted_data(), $this->default_payload);
  }

  private function integrations_from_current_form($integration) {
    $integrated_form_id = get_post_meta($integration->ID, 'form_id', true);
    return $integrated_form_id == $this->submitted_form_id;
  }
}
