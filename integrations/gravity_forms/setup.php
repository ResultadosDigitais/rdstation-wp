<?php

require_once('integration.php');

$gravity_forms_integration = new RDGravityFormsIntegration();
$gravity_forms_integration->add_callback('gform_after_submission', 'send_lead_conversion');
