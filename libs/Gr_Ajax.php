<?php

    require_once LIBS . 'Exg_Mailman.php';
    require_once LIBS . 'validFluent.php';

    Class Gr_Ajax{

        function __construct()
        {

            // add_action( 'wp_ajax_contacts',       array( &$this, 'contacts' ) );
            // add_action( 'wp_ajax_nopriv_contacts', array( &$this, 'contacts' ) );

            // add_action( 'wp_ajax_search_ajax_products', array( &$this, 'search_ajax_products') );
            // add_action( 'wp_ajax_nopriv_search_ajax_products', array( &$this, 'search_ajax_products') );

            // add_action( 'wp_ajax_subscribe', array( &$this, 'subscribe') );
            // add_action( 'wp_ajax_nopriv_subscribe', array( &$this, 'subscribe') );

        }



        public function contacts()
        {     $output = array();

            if( isset( $_POST['data'] ) && $_POST){
                parse_str( $_POST['data'] ,  $params)  ;

                $topic   = $params['topics'];
                $email   = $params['email'];
                $message = $params['msg'];

                $validation = new ValidFluent($params);

                $invalid_emal =  (ICL_LANGUAGE_CODE == en ) ? 'Invalid Email'    : 'Email inválido' ;
                $requerid     =  (ICL_LANGUAGE_CODE == en ) ? 'Mandatory Field'  : 'Preenchimento Obrigatório' ;
                $selctToppic  =  (ICL_LANGUAGE_CODE == en ) ? 'Select a Topic'   : 'Preenchimento Obrigatório' ;


                $validation->name('email')
                     ->required( $requerid )
                     ->email($invalid_emal);

                $validation->name('msg')
                     ->required( $requerid );




                $topic_error = ($params['topics'] === 'default topic') ?  $selctToppic  : '';

                if(!$validation->isGroupValid() || $params['topics'] === 'default topic'){

                    $errors = array(
                        'topics' => $topic_error,
                        'email'  => $validation->getError('email'),
                        'msg'    => $validation->getError('msg')
                    );

                    $output = array(
                        'error'   => 1,
                        'message' =>  json_encode( $errors  )
                    );
                }
                else{
                    $idioma = (ICL_LANGUAGE_CODE == en ) ? 'Inglês'  : 'Português' ;

                     $template_args = array(
                        'topic'     => $params['topics'],
                        'email'     => $params['email'],
                        'message'   => $params['msg'],
                        'idioma'    => $idioma,
                    );

                    $this->send_mail($template_args) ;

                    $output = array(
                      'error'   => 0,
                      'message' => "ok"
                    );
                }
            }


            echo    json_encode( $output);
            die();
        }

        private function get_unique_id($long=8) {
            return substr(md5(uniqid()), $long * -1);
        }

        function subscribe(){

            $output = array();

            if( isset( $_POST['data_ne'] ) && $_POST){
                 parse_str( $_POST['data_ne'] ,  $params)  ;

                 $email = $params['ne'];
                 $name  = $params['name'];

                $validation = new ValidFluent($params);

                $invalid_email =  (ICL_LANGUAGE_CODE == en ) ? 'Invalid Email'    : 'Email inválido' ;
                $requerid      =  (ICL_LANGUAGE_CODE == en ) ? 'Mandatory Field'  : 'Preenchimento Obrigatório' ;


                $validation->name('ne')
                     ->required( $requerid  )
                     ->email($invalid_email );

                $validation->name('name')
                     ->required(  $requerid );



                if(!$validation->isGroupValid()) {

                    $errors = array(

                        'ne'  => $validation->getError('ne'),
                        'name'   => $validation->getError('name')
                    );

                    $output = array(
                        'error'   => 1,
                        'message' =>  json_encode( $errors  )
                    );
                }

                else{

                    $output = array(
                      'error'   => 0,
                      'message' => $this->subscription_knews($params)
                    );
                }
            }

            echo    json_encode( $output);
            die();
      }


       public function subscription_knews($posts = false){
            global $wpdb;

            if($posts === false)
                return;

            //die(var_dump($wpdb));
            $message             =  (ICL_LANGUAGE_CODE == en ) ? 'The email referred has already subscribed this newsletter'    : 'Esse email já existe na base dados' ;
            $email               = $posts['ne'];
            $knewsusers_table    = $wpdb->prefix . 'knewsusers';
            $knewsuserslists_tb  = $wpdb->prefix . 'knewsuserslists';
            $knewsusersextra     = $wpdb->prefix . 'knewsusersextra';


            $email_exist        = $wpdb->get_row('SELECT * FROM  '.$knewsusers_table.' WHERE email = "'.$email.'";');

            if( empty($email_exist) ){
              $args    = array(
                          'lang'    => $posts['lang'],
                          'email'   => $email,
                          'state'   => '2',
                          'ip'      => '',
                          'confkey' => $this->get_unique_id(),
                          'joined'  => date("Y-m-d H:i:s")
                     );

              $results = $wpdb->insert( $knewsusers_table , $args );

              if($results){
                $user_id = ($wpdb->insert_id !=0 ) ?$wpdb->insert_id : mysql_insert_id();

                $query2   = "INSERT INTO " . $knewsusersextra . " (user_id, field_id , value) VALUES (" . $user_id . ", 1 ,'" . $posts['name'] . "')";


                $results = $wpdb->query( $query2 );

                $query   = "INSERT INTO " . $knewsuserslists_tb . " (id_user, id_list) VALUES (" . $user_id . ", 1);";
                $results = $wpdb->query( $query );

                $message = (ICL_LANGUAGE_CODE == en ) ? 'Successfull subscription' : 'Subscrição efetuada com sucesso.' ;
              }
              else{
                $message = (ICL_LANGUAGE_CODE == en ) ? 'An error has occurred . Try again.' : '"Ocorreu um erro. tente novamente.' ;
              }
            }

            return $message;
        }

        public function send_mail($template_args){


            $mail    = new Exg_Mailman();
            //gnoribeiro@gmail.com

            //$emailTo =   (preg_match('@wpp.zonedevel.com|staging.excentricgrey.com@', $_SERVER['HTTP_HOST'])) ? 'gnoribeiro@gmail.com' :'info@getitonstore.com';
            $emailTo =   (preg_match('@wpp.zonedevel.com|staging.excentricgrey.com@', $_SERVER['HTTP_HOST'])) ? 'filipa.figueiredo@excentricgrey.com' :'info@getitonstore.com';
            $mail->set_subject('Formulário de contacto Loja Online');
            $mail->set_template('form-contacts.php');
            $mail->set_from("Get it On <no-reply@getitonstore.com>");
            $mail->set_to($emailTo);
            $mail->set_vars($template_args);
            $mail->send();
        }





        public function search_ajax_products() {
            global $woocommerce;

            $search_keyword = esc_attr($_REQUEST['query']);

            $ordering_args = $woocommerce->query->get_catalog_ordering_args( 'title', 'asc' );
            $products = array();

            $args = array(
                's'                     => $search_keyword ,
                'post_type'             => 'product',
                'post_status'           => 'publish',
                'ignore_sticky_posts'   => 1,
                'orderby'               => $ordering_args['orderby'],
                'order'                 => $ordering_args['order'],
                'posts_per_page'        => 8,
                'meta_query'            => array(
                    array(
                        'key'           => '_visibility',
                        'value'         => array('catalog', 'visible'),
                        'compare'       => 'IN'
                    )
                )
            );

            if( isset( $_REQUEST['product_cat']) ){
                $args['tax_query'] = array(
                    'relation' => 'AND',
                    array(
                        'taxonomy' => 'product_cat',
                        'field' => 'slug',
                        'terms' => $_REQUEST['product_cat']
                    ));
            }

            $products_query = new WP_Query( $args );

            if ( $products_query->have_posts() ) {
                while ( $products_query->have_posts() ) {
                    $products_query->the_post();

                    $products[] = array(
                        'id' => get_the_ID(),
                        'value' => get_the_title(),
                        'url' => get_permalink()
                    );
                }
            } else {
                $products[] = array(
                    'id' => -1,
                    'value' => __('Nenhum produto encontrado', 'letsgetiton'),
                    'url' => ''
                );
            }
            wp_reset_postdata();


            $products = array(
                'suggestions' => $products
            );


            echo json_encode( $products );
            die();
        }
}

    $contacts = new Exg_siteajax();
?>