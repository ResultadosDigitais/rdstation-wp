<?php

require_once('integration.php');

$conversion_builder = new LeadConversion();
$integration = new RDContactForm7Integration($conversion_builder);
$conversion_builder->add_callback('wpcf7_mail_sent', $integration);
