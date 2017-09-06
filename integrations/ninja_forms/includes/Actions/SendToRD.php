<?php
use Mautic\Auth\ApiAuth;
use Mautic\MauticApi;

if ( ! defined( 'ABSPATH' ) || ! class_exists( 'NF_Abstracts_Action' )) exit;

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

    }


    public function process( $action_settings, $form_id, $data )
    {
        

        try {
            
        } catch (\Exception $e) {
            error_log("Não foi possível enviar para o RD");
        }


        $updatedData = [];
        foreach ($action_settings['field_map'] as $fieldMap) {
            //$updatedData[$fieldMap['mautic_field_alias']] = $fieldMap['value'];
        }

        return $data;
    }
}
