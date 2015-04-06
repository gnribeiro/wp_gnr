<?php 

Class Gr_Hooks{
    /** @public array Query vars to add to wp */
    public $query_vars = array();

    public function __construct() {
        //add_action( 'init', array( $this, 'add_test' ) );
      
        //add_filter( 'teste_comment_text', array( $this, 'filter_profanity') , 10, 2 );
        //add_action('teste_publish_post', array($this, 'send'));


        if ( ! is_admin() ) {
            add_filter( 'query_vars', array( $this, 'add_query_vars'), 0 );
            add_action( 'parse_request', array( $this, 'parse_request'), 0 );
            add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) ); 
        }

        $this->init_query_vars();
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


$grhooks = new Gr_Hooks();
?>

