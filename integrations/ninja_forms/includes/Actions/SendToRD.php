<?php

if ( ! defined( 'ABSPATH' ) || ! class_exists( 'NF_Abstracts_Action' )) exit;

class RDNinjaFormsIntegration extends LeadConversion {
    public function send_lead_conversion($form_id, $token, $identifier){
        parent::nf_generate_static_fields($form_id, $identifier, $token, 'Plugin Ninja Forms');
    }

    /*
    * Preparar dados do formulário do ninja form, pois vem vários campos desnecessários
    */
    public function prepare_conversion($form_data) {
        foreach($form_data as $itemKey => $item ){
            $id = $item['id'];
            $value = $item['value'];
            $label = $item['label'];
            $teste = (object) [
              'id' => $id,
              'value' => $value, 
              'label' => $label,
            ];
            $this->form_data[$itemKey] = $value;
            unset($item);
        }
        $data_reduzido = (array) $this->form_data;
        parent::conversion($data_reduzido);
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
        
        return $data;
    }
}
