<?php

Class Gwp_Hooks{

    public $query_vars = array();

    public function __construct() 
    {
        if ( ! is_admin() ) {
            add_filter( 'query_vars',    array( $this, 'add_query_vars'), 0 );
            add_action( 'parse_request', array( $this, 'parse_request'), 0 );
            add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );
            add_action('init',           array( $this,'start_session'), 1);
        }

        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'wp_shortlink_wp_head');

        add_filter('wp_headers',        array( $this, 'remove_x_pingback') );
        add_filter('style_loader_src',  array( $this, 'vc_remove_wp_ver_css_js'), 9999 );
        add_filter('script_loader_src', array( $this, 'vc_remove_wp_ver_css_js'), 9999 );

        // remove wpml generator
        //remove_action( 'wp_head', array($sitepress, 'meta_generator_tag' ) );
        
        $this->custom_hooks();
        $this->init_query_vars();
    }
    
    public function custom_hooks() {
        //add_action( 'init', array( $this, 'add_test' ) );
        //add_filter( 'comment_text', array( $this, 'filter_profanity') , 10, 2 );
        //add_action('teste_publish_post', array($this, 'send'));

    }


    public function vc_remove_wp_ver_css_js( $src ) {
        if ( strpos( $src, 'ver=' . get_bloginfo( 'version' ) ) )
            $src = remove_query_arg( 'ver', $src );

        $date = new DateTime();
        $date = $date->format('Ymd');

        return add_query_arg( array('ver' => $date), $src );
    }


    public function remove_x_pingback($headers) {
        unset($headers['X-Pingback']);
        return $headers;
    }

     public function start_session($headers) {
        if(!session_id()) {
            session_start();
        }
    }


    public function filter_profanity($content , $args){
        global $site;

        pr($site->uri);

    }

    public function init_query_vars() {
        // Query vars to add to WP
        $this->query_vars = array(

        );
    }

    public function add_query_vars( $vars ) {
        foreach ( $this->query_vars as $key => $var ) {
            $vars[] = $key;
        }

        return $vars;
    }


    public function parse_request() {
        global $wp;

        // Map query vars to their keys
        foreach ( $this->query_vars as $key => $var ) {
            if ( isset( $_GET[ $var ] ) ) {
                $wp->query_vars[ $key ] = $_GET[ $var ];
            }

            elseif ( isset( $wp->query_vars[ $var ] ) ) {
                $wp->query_vars[ $key ] = $wp->query_vars[ $var ];
            }
        }
    }

    public function pre_get_posts( $query ) {
        // We only want to affect the main query
        if ( ! $query->is_main_query() ) {
            return;
        }
    }

}


$grhooks = new Gwp_Hooks();
?>

