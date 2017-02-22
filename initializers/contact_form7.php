<?php

require_once('RD_Custom_Post_Type.php');

$setup = new RD_Custom_Post_Type('rdcf7');
$setup->acronym = 'CF7';
$setup->name = 'Contact Form 7';
$setup->plugin_path = 'contact-form-7/wp-contact-form-7.php';
$setup->init();
