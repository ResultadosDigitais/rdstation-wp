<?php

require_once('integration.php');

$cf7_integration = new RDContactForm7Integration();
$cf7_integration->add_callback('wpcf7_mail_sent', array($cf7_integration, 'send_lead_conversion'));
