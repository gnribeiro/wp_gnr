<?php
require_once( 'validFluent.php' );

class Gwp_Knews {
  
  function __construct() {
   
    add_action( 'wp_ajax_subscribe',        array($this, 'subscribe' ) );
    add_action( 'wp_ajax_nopriv_subscribe', array($this, 'subscribe' ) ); 
   
  }


 


  function subscribe(){

        $output = array();

        if( isset( $_POST['data_ne'] ) && $_POST){
             parse_str( $_POST['data_ne'] ,  $params)  ;
            //Your+email
            //terms=on
            //ne=O+seu+email&terms=on
            $email  = $params['ne'];
            $terms  = $params['terms'];
            
            $validation = new ValidFluent($params);

            $invalid_email =  (ICL_LANGUAGE_CODE == 'en' ) ? 'Please enter a valid email address.'  : 'Por favor, insira um email válido.' ;
            $requerid      =  (ICL_LANGUAGE_CODE == 'en' ) ? 'Please enter a valid email address.'  : 'Por favor, insira um email válido.' ;
            $invalid_terms =  (ICL_LANGUAGE_CODE == 'en' ) ? 'You must accept the terms and conditions.'  : 'Deve aceitar os termos e condições.' ;

            $validation->name('ne')
                 ->required( $requerid  )
                 ->email($invalid_email );

           


            if(preg_match("#(Your email|O seu email )#" , $email)) {
                 $errors = array(
                    'error'  =>$invalid_email
                );
                $output = array(
                    'error'   => 1,
                    'message' =>  json_encode( $errors  )
                ); 
            }
            elseif(!$validation->isGroupValid()) {

                $errors = array(

                    'error'  => $validation->getError('ne'),
                   
                );

                $output = array(
                    'error'   => 1,
                    'message' =>  json_encode( $errors  )
                );
            }
            elseif(empty($terms)) {
                $errors = array(
                    'error'  => $invalid_terms
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


    private function get_unique_id($long=8) {
        return substr(md5(uniqid()), $long * -1);
    }

    
    
    public function subscription_knews($posts = false){
            global $wpdb;

            if($posts === false)
                return;

            
            $message             =  (ICL_LANGUAGE_CODE == 'en' ) ? 'Your email is already registered.'    : 'O seu email já se encontra registado.' ;
            $email               = $posts['ne'];
            $knewsusers_table    = $wpdb->prefix . 'knewsusers';
            $knewsuserslists_tb  = $wpdb->prefix . 'knewsuserslists';
            $knewsusersextra     = $wpdb->prefix . 'knewsusersextra';


            $email_exist        = $wpdb->get_row('SELECT * FROM  '.$knewsusers_table.' WHERE email = "'.$email.'";');

            if( empty($email_exist) ){
                $args    = array(
                    'lang'    => ICL_LANGUAGE_CODE,
                    'email'   => $email,
                    'state'   => '2',
                    'ip'      => '',
                    'confkey' => $this->get_unique_id(),
                    'joined'  => date("Y-m-d H:i:s")
                );

                $results = $wpdb->insert( $knewsusers_table , $args );

                if($results){
                    $user_id = ($wpdb->insert_id !=0 ) ?$wpdb->insert_id : mysql_insert_id();

                    $query   = "INSERT INTO " . $knewsuserslists_tb . " (id_user, id_list) VALUES (" . $user_id . ", 1);";
                    $results = $wpdb->query( $query );
                    $message = (ICL_LANGUAGE_CODE == 'en' ) ? 'Subscription was successful.' : 'Subscrição efetuada com sucesso.' ;
                }   
                else{
                    $message = (ICL_LANGUAGE_CODE == 'en' ) ? 'An error has occurred. Try again.' : 'Ocorreu um erro. tente novamente.' ;
                }
            }

            return $message;
        }

}

$gwpknews = new Gwp_Knews();
?>