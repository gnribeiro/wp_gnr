<?php

class Gwp_Mailman {

    protected $defaults  = array(
          'subject'      => '',
          'inline'       => FALSE,
          'template'     => '',
          'content_type' => 'text/html',
          'to'           => '',
          'from'         => ''
    );

    protected $data  = array();
    protected $vars  = array();

    function __construct($params=array(), $vars=array()){

        $this->defaults['from'] = get_option('admin_email');
        $this->data           = array_merge( $this->defaults, $params );
        $this->vars           = $vars;
    }

    function set_template($template){
        $this->data['template'] = $template;
    }

    function get_template(){
        return $this->data['template'];
    }

    function set_subject($subject){
        $this->data['subject'] = $subject;
    }

    function get_subject(){
        return $this->data['subject'];
    }

    function set_content_type($content_type){
        $this->data['content_type'] = $content_type;
    }

    function get_content_type(){
        return $this->data['content_type'];
    }

    function set_from($from){
        $this->data['from'] = $from;
    }

    function get_from(){
        return $this->data['from'];
    }

    function set_to($to){
        $this->data['to'] = $to;
    }

    function get_to(){
        return $this->data['to'];
    }

    function set_vars($vars=array()){
        $this->vars = $vars;
    }

    function send(){

        if( $this->data['inline'] == FALSE && empty($this->data['template']) ){
          return new WP_Error('template_missing', __('Please supply a render template') );
        }

        if( $this->data['inline'] == TRUE ){
          $message = $this->data['template'];
        }else{
          $message = $this->render_template();
        }

        $headers = array(
          sprintf("From: %s \r\n"   , $this->data['from']),
          sprintf("Content-type: %s", $this->data['content_type'])
        );

        return wp_mail(
          $this->data['to'],
          $this->data['subject'],
          $message,
          $headers
        );
    }

    private function render_template(){
        if( is_array($this->vars) && !empty($this->vars) ){
            extract($this->vars);
        }
        ob_start();
        include get_template_directory()."views/emails/" . $this->data['template'];
        return ob_get_clean();
    }
};
?>