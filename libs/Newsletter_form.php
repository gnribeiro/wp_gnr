<?php
require_once( 'validFluent.php' );

class Newsletter_form {

  function __construct() {
    add_action( 'wp_head', array( &$this, 'add_ajax_library' ) );
    add_action( 'wp_ajax_subscribe',       array( &$this, 'subscribe' ) );
    add_action( 'wp_ajax_nopriv_subscribe', array( &$this, 'subscribe' ) );

  }


  public function add_ajax_library() {

    $html  = '<script type="text/javascript">';
    $html .= 'var ajaxurl = "' . admin_url( 'admin-ajax.php' ) . '"';
    $html .= '</script>';

    echo $html;
  }


  function subscribe(){

    if( isset( $_POST['ne'] ) && $_POST){

      $output     = array();
      $validation = new ValidFluent($_POST);

      $validation->name('ne')
                 ->required(__('Prenchimento Obrigatórios' , 'anubis'))
                 ->email(__('Email inválido' , 'anubis'));

      if(!$validation->isGroupValid()){
        $output = array(
            'error'   => 1,
            'message' => $validation->getError('ne')
        );
      }
      else{
        $output = array(
          'error'   => 0,
          'message' =>  $this->subscription_knews()
        );
      }

      echo   json_encode($output);
    }
    die();
  }


  private function subscription($post){
    unset($_POST['na']);
    unset($post['na']);

    $message = array(
          'E' => __("Ocorreu um erro. tente novamente." , 'anubis'),
          'C' => __("Subscrição efetuada com sucesso."  , 'anubis'),
    );

    $user = NewsletterSubscription::instance()->subscribe();

    return ($user->status=='E' ||  $user->status=='C') ? $message[$user->status] : __("Ocorreu um erro." , 'anubis') ;
  }


  private function get_unique_id($long=8) {
    return substr(md5(uniqid()), $long * -1);
  }


  private function subscription_knews(){
    global $wpdb;


    //die(var_dump($wpdb));
    $message            = __("Esse email já existe na base dados" , 'anubis');
    $email              = $_REQUEST['ne'];
    $knewsusers_table   = $wpdb->prefix . 'knewsusers';
    $knewsuserslists_tb = $wpdb->prefix . 'knewsuserslists';
    $email_exist        = $wpdb->get_row('SELECT * FROM  '.$knewsusers_table.' WHERE email = "'.$email.'";');

    if( empty($email_exist) ){
      $args    = array(
                  'lang'    => 'en',
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

        $message = __("Subscrição efetuada com sucesso."  , 'anubis');
      }
      else{
        $message = __("Ocorreu um erro. tente novamente." , 'anubis');

      }
    }

    return $message;
  }



  private function subscription_meenews(){
    global $wpdb;

    //die(var_dump($wpdb));
    $message           = __("Esse email já existe na base dados" , 'anubis');
    $email         = $_REQUEST['ne'];
    $meenews_table = $wpdb->prefix . 'meenewsusers';
    $email_exist   = $wpdb->get_row('SELECT * FROM  '.$meenews_table.' WHERE email = "'.$email.'";');

    if( empty($email_exist) ){

      $confkey = md5(uniqid(rand(),1));
      $date    = date("Y-m-d H:i:s");
      $args    = array(
                  'id_categoria' => '0',
                  'email'        => $email,
                  'name'         => '',
                  'enterprise'   => '',
                  'state'        => 2,
                  'confkey'      => md5(uniqid(rand(),1)),
                  'joined'       => date("Y-m-d H:i:s")
             );


      $message = ( $wpdb->insert( $meenews_table , $args ) )
             ? __("Subscrição efetuada com sucesso."  , 'anubis')
             : __("Ocorreu um erro. tente novamente." , 'anubis');
    }

    return $message;
  }


  private function cleanInput($input) {

    $search = array(
      '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
      '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
      '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
      '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
    );

    $output = preg_replace($search, '', $input);
    return $output;
  }


  private function sanitize($input) {
    if (is_array($input)) {
        foreach($input as $var=>$val) {
            $output[$var] = $this->sanitize($val);
        }
    }
    else {
        if (get_magic_quotes_gpc()) {
            $input = stripslashes($input);
        }
        $input  = $this->cleanInput($input);
        $input  = $this->xss_clean($input);
        $output = mysql_real_escape_string($input);
    }
    return $output;
  }



  private function xss_clean($str){

    if (is_array($str) OR is_object($str))
    {
        foreach ($str as $k => $s)
        {
            $str[$k] = $this->xss_clean($s);
        }

        return $str;
    }

    // Remove all NULL bytes
    $str = str_replace("\0", '', $str);

    // Fix &entity\n;
    $str = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $str);
    $str = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $str);
    $str = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $str);
    $str = html_entity_decode($str);

    // Remove any attribute starting with "on" or xmlns
    $str = preg_replace('#(?:on[a-z]+|xmlns)\s*=\s*[\'"\x00-\x20]?[^\'>"]*[\'"\x00-\x20]?\s?#iu', '', $str);

    // Remove javascript: and vbscript: protocols
    $str = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $str);
    $str = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $str);
    $str = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $str);

    // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
    $str = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#is', '$1>', $str);
    $str = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#is', '$1>', $str);
    $str = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#ius', '$1>', $str);

    // Remove namespaced elements (we do not need them)
    $str = preg_replace('#</*\w+:\w[^>]*+>#i', '', $str);

    do
    {
        // Remove really unwanted tags
        $old = $str;
        $str = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $str);
    }
    while ($old !== $str);

    return $str;
  }
}

$tsn = new Newsletter_form();
?>