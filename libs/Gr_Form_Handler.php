<?php
require_once LIBS . 'validFluent.php';



Class GR_Form_Handler {

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

        $validation      = new ValidFluent($_POST);
        $requerid        = 'Preenchimento Obrigatório';
        $invalid_email   = 'Email inválido';

        $validation->name('nome')
            ->required( $requerid );
        $validation->name('phone')
            ->required( $requerid );
        $validation->name('email')
            ->required( $requerid )
            ->email($invalid_email);
        $validation->name('mensage')
            ->required( $requerid );

        if(!$validation->isGroupValid()){
            flash("nome-error"    , $validation->getError('nome'));
            flash("phone-error"   , $validation->getError('phone'));
            flash("email-error"   , $validation->getError('email'));
            flash("mensage-error" , $validation->getError('mensage'));
            flash("error"         , true);
        }
        else{
             pr('ok');
        }
    }
}

GR_Form_Handler::init();
?>