<?php
require_once LIBS . 'validFluent.php';

Class Gwp_Form_Handler {

    public static function init() {
        add_action( 'wp_loaded', array( __CLASS__, 'contacts_action' ), 20 );

    }

    public static function  contacts_action(){
        if ( 'POST' !== strtoupper( $_SERVER[ 'REQUEST_METHOD' ] ) ) {
            return;
        }

        if ( empty( $_POST[ 'action' ] ) || 'contacts' !== $_POST[ 'action' ] || empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'contact-form' ) ) {
            return;
        }

//        $validation      = new ValidFluent($_POST);
//        $requerid        = 'Preenchimento Obrigatório';
//        $invalid_email   = 'Email inválido';
        
        
        
        $validation2 = Validation::factory($_POST);
        $validation2->rule('nome',    'not_empty');
        $validation2->rule('phone',   'not_empty');
        $validation2->rule('email',   'not_empty');
        $validation2->rule('email',   'Valid::email');
        $validation2->rule('mensage', 'not_empty');
        
         pr($validation2->check());

//        $validation->name('nome')
//            ->required( $requerid );
//        $validation->name('phone')
//            ->required( $requerid );
//        $validation->name('email')
//            ->required( $requerid )
//            ->email($invalid_email);
//        $validation->name('mensage')
//            ->required( $requerid );

        if(false){
//            Helper::set_flashdata("nome-error"    , $validation->getError('nome'));
//            Helper::set_flashdata("phone-error"   , $validation->getError('phone'));
//            Helper::set_flashdata("email-error"   , $validation->getError('email'));
//            Helper::set_flashdata("mensage-error" , $validation->getError('mensage'));
//            Helper::set_flashdata("error"         , true);
        }
        else{
             pr('ok');
        }
    }
}


?>