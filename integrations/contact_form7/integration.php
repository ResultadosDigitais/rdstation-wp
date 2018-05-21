<?php

require_once(PLUGIN_DIR . 'includes/integrations/rdsm_integrations.php');

class RDContactForm7Integration {
  const PLUGIN_DESCRIPTION = 'Plugin Contact Form 7';

  public $default_payload = array(
    'form_origem' => self::PLUGIN_DESCRIPTION
  );

  private $submitted_form_id;

  public function __construct($conversion) {
    $this->conversion = $conversion;
  }

  public function send_lead_conversion($submitted_form){
    $integrations = new RDSMIntegrations;
    $cf7_integrations = $integrations->get('rdcf7_integrations');
    $this->submitted_form_id = $submitted_form->id();

    $current_form_integrations = array_filter(
      $cf7_integrations,
      array($this, 'integrations_from_current_form')
    );

    $this->build_default_payload();

    foreach ($current_form_integrations as $integration) {
      $this->conversion->build_payload($integration->ID, $this->form_data);
      $conversions_api = new RDSMConversionsAPI($this->conversion);
      $conversions_api->create_lead_conversion();
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
