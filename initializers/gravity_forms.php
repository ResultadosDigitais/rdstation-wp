<?php

$setup = new RD_Custom_Post_Type('rdgf');
$setup->acronym = 'GF';
$setup->name = 'Gravity Forms';
$setup->plugin_path = 'gravityforms/gravityforms.php';
$setup->init();
