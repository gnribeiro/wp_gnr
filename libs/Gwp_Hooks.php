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
            
            add_action('wploop', array($this, 'wploop') , 10 , 2);
            add_action('wploop_query', array($this, 'wploop_query') , 10 , 3);
            
            $this->custom_hooks_actions_noadmin(); 
            $this->custom_hooks_filters_noadmin(); 
           
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
        
       
        $this->init_query_vars();
        $this->custom_hooks_actions(); 
        $this->custom_hooks_filters();
    }
    
    public function custom_hooks_filters() {

    }
    
    
    public function custom_hooks_actions() {
        
    }
    
    
     public function custom_hooks_filters_noadmin() {

    }
    
    
    public function custom_hooks_actions_noadmin() {
        
    }

    public function wploop($view = false , $else_view = false){
        global $site;
         
        $else_view = ($else_view === false || empty( $else_view)) ? "loop/fallback_else" : $else_view;
        $view      = ($view      === false || empty( $view)     ) ? "loop/fallback"      : $view;
        
        $content_else = $site->get_view($else_view);
        
        $site->set_view('loop/wploop' , array('view'=>$view  , 'else_view' => $content_else)); 
    } 
    
    
    public function wploop_query($args, $view = false , $else_view = false){
        global $site;
         
        $query     = new WP_Query($args);
       
        $else_view = ($else_view === false || empty( $else_view)) ? "loop/fallback_else" : $else_view;
        $view      = ($view      === false || empty( $view)     ) ? "loop/fallback"      : $view;
        
      
        $content_else = $site->get_view($else_view);
        
        $site->set_view('loop/wploop_query' , array('view'=>$view , 'else_view' => $content_else, 'query' => $query)); 
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
        $this->init_query_vars();
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
?>