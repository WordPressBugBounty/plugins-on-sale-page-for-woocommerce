<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://wpgenie.org
 * @since      1.0.0
 *
 * @package    Woocommerce_onsale_page
 * @subpackage Woocommerce_onsale_page/public
 */
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woocommerce_onsale_page
 * @subpackage Woocommerce_onsale_page/public
 * @author     Your Name <email@example.com>
 */
class Woocommerce_Onsale_Page_Public {
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
     * @param string $plugin_name       The name of the plugin.
     * @param string $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Template loader
     *
     * @param  string $template template path.
     * @return string
     */
    public function template_loader( $template ) {
        $find = array('woocommerce.php');
        $file = '';
        if ( is_post_type_archive( 'product' ) || is_page( wc_get_page_id( 'onsale' ) ) ) {
            $file = 'archive-product.php';
            $find[] = $file;
            $find[] = WC()->template_path() . $file;
        }
        if ( !empty( $file ) ) {
            $template = locate_template( array_unique( $find ) );
            if ( !$template || WC_TEMPLATE_DEBUG_MODE ) {
                $template = WC()->plugin_path() . '/templates/' . $file;
            }
        }
        return $template;
    }

    /**
     * Pre get posts
     *
     * @param  object $q query object.
     * @return object
     */
    public function pre_get_posts( $q ) {
        if ( !$q->query ) {
            return;
        }
        $onsale_page_id = $this->get_main_wpml_id( wc_get_page_id( 'onsale' ) );
        if ( $onsale_page_id && -1 !== $onsale_page_id ) {
            if ( is_page( $onsale_page_id ) ) {
                $q->set( 'post_type', 'product' );
                $q->set( 'page', '' );
                $q->set( 'pagename', '' );
                // Fix conditional Functions.
                $q->is_archive = true;
                $q->is_post_type_archive = true;
                $q->is_singular = false;
                $q->is_page = false;
                $q->is_sale_page = true;
                add_filter( 'woocommerce_is_filtered', array($this, 'add_is_filtered'), 99 );
                // hack for displaying when Shop Page Display is set to show categories.
                // Fix WP SEO.
                if ( class_exists( 'WPSEO_Meta' ) ) {
                    add_filter( 'wpseo_metadesc', array($this, 'wpseo_metadesc') );
                    add_filter( 'wpseo_metakey', array($this, 'wpseo_metakey') );
                    add_filter( 'wpseo_title', array($this, 'wpseo_title') );
                    add_filter( 'wpseo_canonical', array($this, 'wpseo_canonical_url') );
                }
                if ( class_exists( 'RankMath' ) ) {
                    add_filter( 'rank_math/frontend/title', array($this, 'rankmathseo_title') );
                    add_filter( 'rank_math/frontend/description', array($this, 'rankmathseo_metadesc') );
                    add_filter( 'rank_math/frontend/canonical', array($this, 'rankmathseo_canonical_url') );
                    add_filter( 'rank_math/frontend/keywords', array($this, 'rankmathseo_metakey') );
                }
            }
        }
    }

    /**
     *  Mod woocommerce product query
     *
     * @param  object $q query oobject.
     * @param  object $wc_query query oobject.
     * @return void
     */
    public function mod_woocommerce_product_query( $q, $wc_query ) {
        global $wp_query;
        if ( $wp_query->is_sale_page ) {
            $product_ids_on_sale = apply_filters( 'wc_onsale_page_product_ids_on_sale', wc_get_product_ids_on_sale() );
            $meta_query = WC()->query->get_meta_query();
            $q->set( 'post__in', array_merge( array(0), $product_ids_on_sale ) );
        }
    }

    /**
     * Woocommerce_page_title
     *
     * @param  string $title page title.
     * @return string
     */
    public function woocommerce_page_title( $title ) {
        global $wp_query;
        $onsale_page_id = $this->get_main_wpml_id( wc_get_page_id( 'onsale' ) );
        if ( $wp_query->is_sale_page ) {
            $page_title = get_the_title( $onsale_page_id );
            return $page_title;
        }
        return $title;
    }

    /**
     * Woocommerce get breadcrumb
     *
     * @param  array $crumbs crumb string.
     * @return array
     */
    public function woocommerce_get_breadcrumb( $crumbs ) {
        global $wp_query;
        if ( $wp_query->is_sale_page ) {
            $onsale_page_id = $this->get_main_wpml_id( wc_get_page_id( 'onsale' ) );
            $crumbs[1] = array(get_the_title( $onsale_page_id ), get_permalink( $onsale_page_id ));
        }
        return $crumbs;
    }

    /**
     * Get main product id for multilanguage purpose
     *
     * @param  string $id page id.
     * @return string
     */
    public static function get_main_wpml_id( $id ) {
        global $sitepress;
        if ( function_exists( 'icl_object_id' ) ) {
            // Polylang with use of WPML compatibility mode.
            $id = icl_object_id( $id, 'page', false );
        }
        return $id;
    }

    /**
     * Set is filtered is true to skip displaying categories only on page
     *
     * @access public
     * @return bolean
     */
    public static function add_is_filtered() {
        return true;
    }

    /**
     * Change title for onsale page
     *
     * @param  string $title page title.
     * @return string
     */
    public function change_page_title( $title ) {
        global $wp_query;
        if ( $wp_query->is_sale_page ) {
            $title = get_the_title( $this->get_main_wpml_id( wc_get_page_id( 'onsale' ) ) );
            return $title;
        }
        return $title;
    }

    /**
     *
     * Fix active class in nav for   page.
     *
     * @param array $menu_items manu items.
     * @return array
     */
    public function nav_menu_item_classes( $menu_items ) {
        global $wp_query;
        if ( $wp_query->is_sale_page ) {
            $onsale_page = (int) $this->get_main_wpml_id( wc_get_page_id( 'onsale' ) );
            foreach ( (array) $menu_items as $key => $menu_item ) {
                $classes = (array) $menu_item->classes;
                // Unset active class for blog page.
                $menu_items[$key]->current = false;
                if ( in_array( 'current_page_parent', $classes, true ) ) {
                    unset($classes[array_search( 'current_page_parent', $classes, true )]);
                }
                if ( in_array( 'current-menu-item', $classes, true ) ) {
                    unset($classes[array_search( 'current-menu-item', $classes, true )]);
                }
                // Set active state if this is the shop page link.
                if ( $onsale_page === $menu_item->object_id && 'page' === $menu_item->object ) {
                    $menu_items[$key]->current = true;
                    $classes[] = 'current-menu-item';
                    $classes[] = 'current_page_item';
                }
                $menu_items[$key]->classes = array_unique( $classes );
            }
        }
        return $menu_items;
    }

    /**
     * Translate onsale page url
     *
     * @param  array  $languages languages.
     * @param  bolean $debug_mode debug mode.
     * @return array
     */
    public function translate_ls_onsale_url( $languages, $debug_mode = false ) {
        global $sitepress;
        global $wp_query;
        $onsale_page = (int) wc_get_page_id( 'onsale' );
        foreach ( $languages as $language ) {
            // shop page
            // obsolete?
            if ( $wp_query->is_sale_page || $debug_mode ) {
                $sitepress->switch_lang( $language['language_code'] );
                $url = get_permalink( apply_filters(
                    'translate_object_id',
                    $onsale_page,
                    'page',
                    true,
                    $language['language_code']
                ) );
                $sitepress->switch_lang();
                $languages[$language['language_code']]['url'] = $url;
            }
        }
        return $languages;
    }

    /**
     * Register Widgets.
     *
     * @since
     */
    public function wc_register_widgets() {
        register_widget( 'WC_Onsale_Page_Widget_Layered_Nav' );
    }

    /**
     * WP SEO meta description.
     *
     * Hooked into wpseo_ hook already, so no need for function_exist.
     *
     * @access public
     * @return string
     */
    public function wpseo_metadesc() {
        if ( is_woocommerce_sale_page() ) {
            return WPSEO_Meta::get_value( 'metadesc', wc_get_page_id( 'onsale' ) );
        }
    }

    /**
     * Rankmathseo metadesc
     *
     * @return string
     */
    public function rankmathseo_metadesc() {
        if ( is_woocommerce_sale_page() ) {
            return get_post_meta( wc_get_page_id( 'onsale' ), 'rank_math_description', true );
        }
    }

    /**
     * WP SEO meta key.
     *
     * Hooked into wpseo_ hook already, so no need for function_exist.
     *
     * @access public
     * @return string
     */
    public function wpseo_metakey() {
        if ( is_woocommerce_sale_page() ) {
            return WPSEO_Meta::get_value( 'metakey', wc_get_page_id( 'onsale' ) );
        }
    }

    /**
     * Rankmathseo metakey
     *
     * @return string
     */
    public function rankmathseo_metakey() {
        if ( is_woocommerce_sale_page() ) {
            return get_post_meta( wc_get_page_id( 'onsale' ), 'rank_math_focus_keyword', true );
        }
    }

    /**
     * WP SEO title.
     *
     * Hooked into wpseo_ hook already, so no need for function_exist.
     *
     * @access public
     * @return string
     */
    public function wpseo_title() {
        if ( is_woocommerce_sale_page() ) {
            return WPSEO_Meta::get_value( 'title', wc_get_page_id( 'onsale' ) );
        }
    }

    /**
     * Rankmathseo title
     *
     * @return string
     */
    public function rankmathseo_title() {
        if ( is_woocommerce_sale_page() ) {
            return get_post_meta( wc_get_page_id( 'onsale' ), 'rank_math_title', true );
        }
    }

    /**
     * WP SEO canonical ur.
     *
     * Hooked into wpseo_ hook already, so no need for function_exist.
     *
     * @param  string $canonical caonical.
     * @return string
     */
    public function wpseo_canonical_url( $canonical ) {
        if ( !is_shop() ) {
            return $canonical;
        }
        if ( is_woocommerce_sale_page() ) {
            return get_permalink( wc_get_page_id( 'onsale' ) );
        }
    }

    /**
     * Rankmathseo canonical url
     *
     * @param  string $canonical conical.
     * @return string
     */
    public function rankmathseo_canonical_url( $canonical ) {
        if ( !is_shop() ) {
            return $canonical;
        }
        if ( is_woocommerce_sale_page() ) {
            return get_permalink( wc_get_page_id( 'onsale' ) );
        }
    }

    /**
     * Add query vars
     *
     * @param  array $vars query vars.
     * @return array
     */
    public function add_query_vars( $vars ) {
        $vars[] = 'onsale';
        return $vars;
    }

    /**
     * Onsale woocommerce product query
     *
     * @param  object $q query object.
     * @param  object $query query object.
     * @return void
     */
    public function onsale_woocommerce_product_query( $q, $query ) {
        if ( isset( $q->query_vars['onsale'] ) ) {
            $q->set( 'post__in', array_merge( array(0), wc_get_product_ids_on_sale() ) );
        }
    }

}
