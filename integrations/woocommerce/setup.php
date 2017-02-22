<?php

require_once('integration.php');

$woocommerce_integration = new RDWoocommerceIntegration();
$woocommerce_integration->add_callback('woocommerce_checkout_order_processed', 'send_lead_conversion');
