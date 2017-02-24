<?php

require_once('integration.php');

$contact_form7_integration = new RDContactForm7Integration();
$contact_form7_integration->add_callback('wpcf7_mail_sent', 'send_lead_conversion');
