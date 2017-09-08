<?php if ( ! defined( 'ABSPATH' ) ) exit;

/*
 * Description: Integração entre Ninja Forms e RD.
 * Version: 1.0.0
 * Author: João Mota <joaodfmota@outlook.com>
 * Text Domain: ninja-forms-rd
 * License: GPLv3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * Copyright 2017 João Mota .
 */

if( version_compare( get_option( 'ninja_forms_version', '0.0.0' ), '3', '<' ) || get_option( 'ninja_forms_load_deprecated', FALSE ) ) {

    throw new \Exception("Must update Ninja Forms to version 3.0 or later");

} else {

    /**
     * Class NF_ResultadosDigitais
     */
    final class NF_ResultadosDigitais
    {
        const VERSION = '1.0.0';
        const SLUG    = 'rd';
        const NAME    = 'ResultadosDigitais';
        const AUTHOR  = 'João Mota <joaodfmota@outlook.com>';
        const PREFIX  = 'NF_ResultadosDigitais';

        /**
         * @var NF_ResultadosDigitais
         * @since 3.0
         */
        private static $instance;

        /**
         * Plugin Directory
         *
         * @since 3.0
         * @var string $dir
         */
        public static $dir = '';

        /**
         * Plugin URL
         *
         * @since 3.0
         * @var string $url
         */
        public static $url = '';

        /**
         * Main Plugin Instance
         *
         * Insures that only one instance of a plugin class exists in memory at any one
         * time. Also prevents needing to define globals all over the place.
         *
         * @since 3.0
         * @static
         * @static var array $instance
         * @return NF_ResultadosDigitais Highlander Instance
         */
        public static function instance()
        {
            if (!isset(self::$instance) && !(self::$instance instanceof NF_ResultadosDigitais)) {
                self::$instance = new NF_ResultadosDigitais();

                self::$dir = plugin_dir_path(__FILE__);

                self::$url = plugin_dir_url(__FILE__);

                /*
                 * Register our autoloader
                 */
                spl_autoload_register(array(self::$instance, 'autoloader'));
            }

            return self::$instance;
        }

        public function __construct()
        {

            /*
             * Optional. If your extension processes or alters form submission data on a per form basis...
             */
            add_filter( 'ninja_forms_register_actions', array($this, 'register_actions'));
        }

        /**
         * Optional. If your extension processes or alters form submission data on a per form basis...
         */
        public function register_actions($actions)
        {
            $actions[ 'resultadosdigitais' ] = new NF_ResultadosDigitais_Actions_SendToRD();

            return $actions;
        }

        /*
         * Optional methods for convenience.
         */

        public function autoloader($class_name)
        {
            if (class_exists($class_name)) return;

            if ( false === strpos( $class_name, self::PREFIX ) ) return;

            $class_name = str_replace( self::PREFIX, '', $class_name );
            $classes_dir = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
            $class_file = str_replace('_', DIRECTORY_SEPARATOR, $class_name) . '.php';

            if (file_exists($classes_dir . $class_file)) {
                require_once $classes_dir . $class_file;
            }
        }

    }

    /**
     * The main function responsible for returning The Highlander Plugin
     * Instance to functions everywhere.
     *
     * Use this function like you would a global variable, except without needing
     * to declare the global.
     *
     * @since 3.0
     * @return {class} Highlander Instance
     */
    function NF_ResultadosDigitais()
    {
        return NF_ResultadosDigitais::instance();
    }

    NF_ResultadosDigitais();
}
