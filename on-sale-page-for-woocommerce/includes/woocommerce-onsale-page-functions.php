<?php

/**
 * Onsale for woocommerce functions
 *
 * Functions for plugin things.
 *
 * @package    Woocommerce_onsale_page
 * @version 1.2
 */
defined( 'ABSPATH' ) || exit;
if ( !function_exists( 'is_woocommerce_sale_page' ) ) {
    /**
     * Is woocommerce sale page
     *
     * @return string
     */
    function is_woocommerce_sale_page() {
        global $wp_query;
        if ( !isset( $wp_query ) ) {
            _doing_it_wrong( __FUNCTION__, esc_html__( 'Conditional query tags do not work before the query is run. Before then, they always return false.', 'onsale-page-for-woocommerce' ), '3.1.0' );
            return false;
        }
        return $wp_query->is_sale_page;
    }

}
if ( !function_exists( 'is_woocommerce_featured_page' ) ) {
    /**
     * Is woocommerce sale page
     *
     * @return string
     */
    function is_woocommerce_featured_page() {
        global $wp_query;
        if ( !isset( $wp_query ) ) {
            _doing_it_wrong( __FUNCTION__, esc_html__( 'Conditional query tags do not work before the query is run. Before then, they always return false.', 'onsale-page-for-woocommerce' ), '3.1.0' );
            return false;
        }
        return $wp_query->is_featured_page;
    }

}
if ( !function_exists( 'is_woocommerce_group_page' ) ) {
    /**
     * Is woocommerce sale page
     *
     * @return string
     */
    function is_woocommerce_group_page() {
        global $wp_query;
        if ( !isset( $wp_query ) ) {
            _doing_it_wrong( __FUNCTION__, esc_html__( 'Conditional query tags do not work before the query is run. Before then, they always return false.', 'onsale-page-for-woocommerce' ), '3.1.0' );
            return false;
        }
        return $wp_query->is_group_page;
    }

}
if ( !function_exists( 'onsale_woocommerce_page_content' ) ) {
    /**
     * Woocommerce_page_content
     *
     * @return void
     */
    function onsale_woocommerce_page_content() {
        global $wp_query;
        if ( $wp_query->is_sale_page ) {
            $onsale_page_id = Woocommerce_Onsale_Page_Public::get_main_wpml_id( wc_get_page_id( 'onsale' ) );
            $post = get_post( $onsale_page_id );
            $blocks = parse_blocks( $post->post_content );
            if ( !empty( $blocks ) && function_exists( 'render_block' ) ) {
                // render Gutenberg blocks.
                foreach ( $blocks as $block ) {
                    echo wp_kses_post( render_block( $block ) );
                }
            } else {
                // render classic content.
                $content = $post->post_content;
                echo wp_kses_post( apply_filters( 'the_content', $content ) );
            }
        }
    }

}