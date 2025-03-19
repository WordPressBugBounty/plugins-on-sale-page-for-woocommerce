<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://wpgenie.org
 * @since      1.0.0
 *
 * @package    Woocommerce_onsale_page
 * @subpackage Woocommerce_onsale_page/admin
 */
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woocommerce_onsale_page
 * @subpackage Woocommerce_onsale_page/admin
 * @author     Your Name <email@example.com>
 */
class Woocommerce_Onsale_Page_Admin {
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param string $plugin_name       The name of this plugin.
     * @param string $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Add plugin row meta
     *
     * @param  mixed $links .
     * @param  mixed $file .
     *
     * @return array
     */
    public function add_plugin_row_meta( $links, $file ) {
        if ( opfw_fs()->get_plugin_basename() === $file ) {
            $row_meta = array(
                'support' => '<a href="' . esc_url( 'https://wpgenie.org/support/' ) . '" target="_blank" aria-label="' . esc_attr__( 'Ticket support', 'onsale-page-for-woocommerce' ) . '" style="font-weight:700;">' . esc_html__( 'Ticket support', 'onsale-page-for-woocommerce' ) . '</a>',
                'upgrade' => '<a href="' . esc_url( opfw_fs()->get_upgrade_url() ) . '" target="_blank" aria-label="' . esc_attr__( 'Upgrade to pro', 'onsale-page-for-woocommerce' ) . '" style="color:Magenta;font-weight:700;">' . esc_html__( 'Upgrade to pro', 'onsale-page-for-woocommerce' ) . '</a>',
            );
            return array_merge( $links, $row_meta );
        }
        return (array) $links;
    }

    /**
     * Add options for plugin.
     *
     * @param  array $settings settings array.
     * @return array
     */
    public function add_salepage_on_option_page( $settings ) {
        $featuredpageid = false;
        $groupproductspageid = false;
        $onsalepageid = array(
            'title'    => esc_html__( 'Onsale Page', 'onsale-page-for-woocommerce' ),
            'id'       => 'woocommerce_onsale_page_id',
            'type'     => 'single_select_page',
            'default'  => '',
            'class'    => 'wc-enhanced-select-nostd',
            'css'      => 'min-width:300px;',
            'desc_tip' => esc_html__( 'This sets the onsale page of your shop - this is where your product on sale archive will be.', 'onsale-page-for-woocommerce' ),
        );
        if ( opfw_fs()->is_premium() ) {
            $featuredpageid = array(
                'title'    => esc_html__( 'Featured products Page', 'onsale-page-for-woocommerce' ),
                'id'       => 'woocommerce_featured_page_id',
                'type'     => 'single_select_page',
                'default'  => '',
                'class'    => 'wc-enhanced-select-nostd',
                'css'      => 'min-width:300px;',
                'desc_tip' => esc_html__( 'This sets the featured products page of your shop - this is where your featured products archive will be.', 'onsale-page-for-woocommerce' ),
            );
            $groupproductspageid = array(
                'title'    => esc_html__( 'Group products Page', 'onsale-page-for-woocommerce' ),
                'id'       => 'woocommerce_group_page_id',
                'type'     => 'single_select_page',
                'default'  => '',
                'class'    => 'wc-enhanced-select-nostd',
                'css'      => 'min-width:300px;',
                'desc_tip' => esc_html__( 'This sets the group products page of your shop - this is where your group products archive will be.', 'onsale-page-for-woocommerce' ),
            );
        }
        $modifsettings = array_slice(
            $settings,
            0,
            2,
            true
        );
        array_push(
            $modifsettings,
            $onsalepageid,
            $featuredpageid,
            $groupproductspageid
        );
        return array_merge( $modifsettings, array_slice(
            $settings,
            2,
            count( $settings ),
            true
        ) );
    }

    /**
     * Update lookup table
     *
     * @param  mixed $post_id post id.
     * @return void
     */
    public function update_lookup_table( $post_id ) {
        global $wpdb;
        $product = wc_get_product( $post_id );
        if ( !$product ) {
            return;
        }
        $product_id = absint( $product->get_id() );
        if ( $product->get_type() === 'grouped' ) {
            self::check_grouped_product_for_sale( $product->get_id() );
        }
        $results = $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM {$wpdb->prefix}postmeta WHERE meta_key = '_children' AND meta_value LIKE %s", '%' . $product_id . '%' ) );
        if ( $results ) {
            foreach ( $results as $product_id ) {
                self::check_grouped_product_for_sale( $product_id );
            }
        }
    }

    /**
     * Check grouped product for_sale
     *
     * @param  mixed $product_id product id.
     * @return void
     */
    public function check_grouped_product_for_sale( $product_id ) {
        global $wpdb;
        $product = wc_get_product( $product_id );
        if ( !$product ) {
            return;
        }
        if ( $product->get_type() !== 'grouped' ) {
            return;
        }
        $id = absint( $product->get_id() );
        $table = 'wc_product_meta_lookup';
        $existing_data = wp_cache_get( 'lookup_table', 'object_' . $id );
        $price_meta = (array) get_post_meta( $id, '_price', false );
        $manage_stock = get_post_meta( $id, '_manage_stock', true );
        $stock = ( 'yes' === $manage_stock ? wc_stock_amount( get_post_meta( $id, '_stock', true ) ) : null );
        $price = wc_format_decimal( get_post_meta( $id, '_price', true ) );
        $sale_price = wc_format_decimal( get_post_meta( $id, '_sale_price', true ) );
        $update_data = array(
            'product_id'     => absint( $id ),
            'sku'            => get_post_meta( $id, '_sku', true ),
            'virtual'        => ( 'yes' === get_post_meta( $id, '_virtual', true ) ? 1 : 0 ),
            'downloadable'   => ( 'yes' === get_post_meta( $id, '_downloadable', true ) ? 1 : 0 ),
            'min_price'      => reset( $price_meta ),
            'max_price'      => end( $price_meta ),
            'onsale'         => ( $product->is_on_sale() ? 1 : 0 ),
            'stock_quantity' => $stock,
            'stock_status'   => get_post_meta( $id, '_stock_status', true ),
            'rating_count'   => array_sum( (array) get_post_meta( $id, '_wc_rating_count', true ) ),
            'average_rating' => get_post_meta( $id, '_wc_average_rating', true ),
            'total_sales'    => get_post_meta( $id, 'total_sales', true ),
        );
        if ( $update_data !== $existing_data ) {
            $wpdb->replace( $wpdb->{$table}, $update_data );
            wp_cache_set( 'lookup_table', $update_data, 'object_' . $id );
        }
    }

}
