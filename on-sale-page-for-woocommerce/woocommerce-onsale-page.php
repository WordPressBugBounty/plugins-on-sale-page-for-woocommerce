<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://wpgenie.org
 * @since             1.0.0
 * @package           Woocommerce_onsale_page
 *
 * @wordpress-plugin
 * Plugin Name:       OnSale Page for WooCommerce
 * Plugin URI:        https://wordpress.org/plugins/last-users-order-column-for-woocommerce/
 * Description:       OnSale Page for WooCommerce is an extension for WooCommerce. We developed this plugin because WooCommerce has onsale widget and shortcode but it lacks paging, sorting and filtering which you can usually find on regular WooCommerce catalog page.
 * Version:           2.0.1
 * Author:            wpgenie
 * Author URI:        http://wpgenie.org/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       onsale-page-for-woocommerce
 * Domain Path:       /languages
 * Requires Plugins: woocommerce
 *
 * WC requires at least: 5.0
 * WC tested up to: 10.4
 *
 */
// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    die;
}
if ( function_exists( 'opfw_fs' ) ) {
    opfw_fs()->set_basename( false, __FILE__ );
} else {
    if ( !function_exists( 'opfw_fs' ) ) {
        /**
         * Create a helper function for easy SDK access.
         */
        function opfw_fs() {
            global $opfw_fs;
            if ( !isset( $opfw_fs ) ) {
                // Manually include the Freemius SDK (not needed if using Composer).
                require_once __DIR__ . '/vendor/freemius/start.php';
                $opfw_fs = fs_dynamic_init( array(
                    'id'             => '17293',
                    'slug'           => 'onsale-page-for-woocommerce',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_3c1381ebaad19c8e9213262a88a7e',
                    'is_premium'     => false,
                    'premium_suffix' => 'Professional',
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'trial'          => array(
                        'days'               => 3,
                        'is_require_payment' => false,
                    ),
                    'menu'           => array(
                        'first-path' => 'plugins.php',
                        'support'    => false,
                    ),
                    'is_live'        => true,
                ) );
            }
            return $opfw_fs;
        }

        // Init Freemius.
        opfw_fs();
        // Signal that SDK was initiated.
        do_action( 'opfw_fs_loaded' );
    }
    add_action( 'before_woocommerce_init', function () {
        if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
        }
    } );
    /**
     * The core plugin class that is used to define internationalization,
     * admin-specific hooks, and public-facing site hooks.
     */
    require plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-onsale-page.php';
    require plugin_dir_path( __FILE__ ) . 'includes/woocommerce-onsale-page-functions.php';
    /**
     * Begins execution of the plugin.
     *
     * Since everything within the plugin is registered via hooks,
     * then kicking off the plugin from this point in the file does
     * not affect the page life cycle.
     *
     * @since    1.0.0
     */
    function run_wc_onsale_page() {
        $plugin = new Woocommerce_Onsale_Page();
        $plugin->run();
    }

    add_action( 'woocommerce_init', 'run_wc_onsale_page' );
}