<?php

require_once('integration.php');

$conversion = new LeadConversion();
$conversion->add_callback('wpcf7_mail_sent', 'send_lead_conversion');
