<?php
/**
 * Plugin Name: WP Generation Mapping
 * Plugin URI:  https://github.com/ChasmSolutions/wp-gen-mapper
 * Description: Wordpress Generation Mapping Plugin
 * Version: 0.1
 * Author: Chasm.Solutions
 * Author URI: https://github.com/ChasmSolutions
 * Requires at least: 4.1.0
 * Tested up to: 4.7.3
 *
 * @package                 Generation_Mapping
 * @author                  Daniel Vopalecky
 * @author_url              https://github.com/dvopalecky
 * @forked_url              https://github.com/dvopalecky/gen-mapper
 * @author_wp_plugin        ChrisChasm
 * @author_wp_plugin_url    https://github.com/ChrisChasm
 * @link                    https://github.com/ChasmSolutions
 * @license                 MIT
 * @version                 0.1
 *
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Activation Hook
 * The code that runs during plugin activation.
 * This action is documented in includes/admin/class-activator.php
 */
function activate_generation_mapping() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-activator.php';
    Generation_Mapping_Activator::activate();
}

/**
 * Deactivation Hook
 * The code that runs during plugin deactivation.
 * This action is documented in includes/admin/class-deactivator.php
 */
function deactivate_generation_mapping() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-deactivator.php';
    Generation_Mapping_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_generation_mapping');
register_deactivation_hook(__FILE__, 'deactivate_generation_mapping');



/**
 * Returns the main instance of Generation_Mapping to prevent the need to use globals.
 *
 * @since  0.1
 * @return object Generation_Mapping
 */

// Adds the Generation_Mapping Plugin after plugins load
add_action( 'plugins_loaded', 'Generation_Mapping' );

// Creates the instance
function Generation_Mapping() {
    return Generation_Mapping::instance();
}


/**
 * Main Generation_Mapping Class
 *
 * @class Generation_Mapping
 * @since 0.1
 * @package	Generation_Mapping
 */
class Generation_Mapping {
    /**
     * Generation_Mapping The single instance of Generation_Mapping.
     * @var 	object
     * @access  private
     * @since  0.1
     */
    private static $_instance = null;

    /**
     * The token.
     * @var     string
     * @access  public
     * @since   0.1
     */
    public $token;

    /**
     * The version number.
     * @var     string
     * @access  public
     * @since   0.1
     */
    public $version;

    /**
     * The version number.
     * @var     string
     * @access  public
     * @since   0.1
     */
    public $mapper;

    /**
     * The plugin directory URL.
     * @var     string
     * @access  public
     * @since   0.1
     */
    public $url;

    /**
     * The plugin directory path.
     * @var     string
     * @access  public
     * @since   0.1
     */
    public $path;

    /**
     * Main Generation_Mapping Instance
     *
     * Ensures only one instance of Generation_Mapping is loaded or can be loaded.
     *
     * @since 0.1
     * @static
     * @see Generation_Mapping()
     * @return Generation_Mapping instance
     */
    public static function instance () {
        if ( is_null( self::$_instance ) )
            self::$_instance = new self();
        return self::$_instance;
    } // End instance()

    /**
     * Constructor function.
     * @access  public
     * @since   0.1
     */
    public function __construct () {
        /**
         * Prepare variables
         *
         */
        $this->token 			= 'generation_mapping';
        $this->version 			= '0.1';
        $this->url 		        = plugin_dir_url( __FILE__ );
        $this->path 		    = plugin_dir_path( __FILE__ );
        $this->mapper           = plugin_dir_url( __FILE__ ) . 'gen-mapper/';
        /* End prep variables */


        /**
         * Admin panel
         *
         * Contains all those features that only run if in the Admin panel
         * or those things directly supporting Admin panel features.
         */
        if ( is_admin() ) {
            // Generation_Mapping admin settings page configuration
            add_action("admin_menu", array($this, "add_gen_map_menu") );

        }
        /* End Admin configuration section */

        add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

    } // End __construct()

    /**
     *
     */
    public function add_gen_map_menu () {
        add_submenu_page( 'index.php', __( 'Gen Mapper', $this->token ), __( 'Gen Mapper', $this->token ), 'manage_options', $this->token, array( $this, 'gen_map_page' ) );
    }


    public function gen_map_page () {
        $html = '';

        $html .= '<h2>Gen Mapping</h2>';

        $html .= '<iframe src="'. $this->mapper .'index.html" width="100%" height="1000px"></iframe>';

        echo $html;
    }

    /**
     * Load the localisation file.
     * @access  public
     * @since   0.1
     */
    public function load_plugin_textdomain() {
        load_plugin_textdomain( 'generation_mapping', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    } // End load_plugin_textdomain()

    /**
     * Log the plugin version number.
     * @access  private
     * @since   0.1
     */
    public function _log_version_number () {
        // Log the version number.
        update_option( $this->token . '-version', $this->version );
    } // End _log_version_number()

    /**
     * Cloning is forbidden.
     * @access public
     * @since 0.1
     */
    public function __clone () {
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
    } // End __clone()

    /**
     * Unserializing instances of this class is forbidden.
     * @access public
     * @since 0.1
     */
    public function __wakeup () {
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
    } // End __wakeup()

} // End Class


