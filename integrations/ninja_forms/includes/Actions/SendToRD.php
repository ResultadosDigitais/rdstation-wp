<?php

if ( ! defined( 'ABSPATH' ) || ! class_exists( 'NF_Abstracts_Action' )) exit;

class RDNinjaFormsIntegration extends LeadConversion {
    public function send_lead_conversion($form_id, $token, $identifier){
        /* LOG */
        $fp = fopen('../logs/send_lead_conversion.txt', 'w');
        fwrite($fp, json_encode($form_id));
        fwrite($fp, json_encode($token));
        fwrite($fp, json_encode($identifier));
        fclose($fp);

        parent::nf_generate_static_fields($form_id, $token, $identifier, 'Plugin Ninja Forms');
        //parent::conversion($this->form_data);
    }

    public function prepare_conversion($form_data) {
        $form_data_reduced = unset($a['b']); 
        /* LOG */
        $fp = fopen('../logs/prepare_conversion.txt', 'w');
        fwrite($fp, json_encode($form_data["email"]["value"]));
        fclose($fp);


    }
}

/**
 * Class NF_ResultadosDigitais_Actions_SendToRD
 */
final class NF_ResultadosDigitais_Actions_SendToRD extends NF_Abstracts_Action
{
    /**
     * @var string
     */
    protected $_name  = 'resultadosdigitais';

    /**
     * @var array
     */
    protected $_tags = array();

    /**
     * @var string
     */
    protected $_timing = 'normal';

    /**
     * @var int
     */
    protected $_priority = '10';

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->_nicename = __( 'Integração RD', 'ninja-forms' );

        /*
        * Settings
        */
        $this->_settings = array(
            'token_rdstation' => array(
                'name' => 'token_rdstation',
                'type' => 'textbox',
                'group' => 'primary',
                'label' => __( 'Identificador', 'ninja-forms' ),
                'placeholder' => '',
                'value' => '',
                'help' => __( 'Esse identificador irá lhe ajudar a saber o formulário de origem do lead.', 'ninja-forms' ),
                'width' => 'full',
                'use_merge_tags' => FALSE,
            ),
            'form_identifier' => array(
                'name' => 'form_identifier',
                'type' => 'textbox',
                'group' => 'primary',
                'label' => __( 'Token', 'ninja-forms' ),
                'placeholder' => '',
                'value' => '',
                'help' => __( 'Não sabe seu token? acesse https://www.rdstation.com.br/integracoes', 'ninja-forms' ),
                'width' => 'full',
                'use_merge_tags' => FALSE,
            ),          
        );

    }


    public function process( $action_settings, $form_id, $data )
    {
        $token = $action_settings["token_rdstation"];
        $identifier = $action_settings["form_identifier"];
        $form_data = $data["fields_by_key"];

        if( isset( $data['settings']['is_preview'] ) && $data['settings']['is_preview'] ){
            return $data;
        }

        try {
            $ninjaform_integration = new RDNinjaFormsIntegration();
            $ninjaform_integration->send_lead_conversion($form_id, $token, $identifier);
            $ninjaform_integration->prepare_conversion($form_data);
        } catch (\Exception $e) {
            error_log("Não foi possível enviar para o RD");
        }

        /* LOG */
        $fp = fopen('../logs/process.txt', 'w');
        fwrite($fp, json_encode($form_data));
        fclose($fp);
        
        return $data;
    }
}
