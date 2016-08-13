<?php

/*
Plugin Name: 	Integração RD Station
Plugin URI: 	https://wordpress.org/plugins/integracao-rdstation
Description:  Integre seus formulários de contato do WordPress com o RD Station
Version:      2.3.1
Author:       Resultados Digitais
Author URI:   http://resultadosdigitais.com.br
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html

Integração RD Station is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Integração RD Station is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Integração RD Station. If not, see https://www.gnu.org/licenses/gpl-2.0.html.

*/

require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
require_once('RD_Custom_Post_Type.php');
require_once('lead-conversion.php');

function enqueue_rd_admin_style($hook) {
    if ( 'post.php' != $hook ) return;
    wp_enqueue_style( 'rd_admin_style', plugin_dir_url( __FILE__ ) . 'styles/admin.css' );
}
add_action( 'admin_enqueue_scripts', 'enqueue_rd_admin_style' );


$contact_form_7 = new RD_Custom_Post_Type ( 'CF7', 'Contact Form 7', 'rdcf7', 'contact-form-7/wp-contact-form-7.php' );
new LeadConversion('contact_form_7', 'wpcf7_mail_sent');

$gravity_forms = new RD_Custom_Post_Type ( 'GF', 'Gravity Forms', 'rdgf', 'gravityforms/gravityforms.php' );
new LeadConversion('gravity_forms', 'gform_after_submission');
