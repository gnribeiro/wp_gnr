<?php
class Helper {
    public static function load_file($file) {
        $file = $file . EXT;

        if(is_file($file))
            return include $file;
    }

    public static function load_config($file) {
        $file = CONFIG . $file;
        return self::load_file($file);
    }

    public static function get_uri(){
        return rtrim(preg_replace(array('@\?.*$@' , '#^/#' , '@page/[\d]+@'), array('' , '', ''), $_SERVER['REQUEST_URI']) , '/');
    }

    
    public static  function get_id_by_slug($slug , $type)
    {
        if(!function_exists('icl_object_id'))
            return;
        
        $page = get_page_by_path( $slug );
        return icl_object_id($page->ID, $type, true);
    }

    
     public static function message($file, $path = NULL, $default = NULL)
    {
         static $messages;
        
        if ( ! isset($messages[$file]))
        {
            $messages[$file] = array();

            if ($files = self::load_messages($file))
            {

                $messages[$file] =  Wparr::merge($messages[$file], $files);
            }
        }
    

        if ($path === NULL)
        {
            return $messages[$file];
        }
        else
        {
          return Wparr::path($messages[$file], $path, $default);
        }
    }

    
    
    public static function load_messages($file) {
        $file = MESSAGES . $file;
        return self::load_file($file);
    }
    
    
    public static function arraypath($array, $path, $default = NULL, $delimiter = NULL)
    {
        if ( ! is_array($array))
        {
            return $default;
        }

        if (is_array($path))
        {
            $keys = $path;
        }
        else
        {
            if (array_key_exists($path, $array))
            {
                // No need to do extra processing
                return $array[$path];
            }

            if ($delimiter === NULL)
            {
                // Use the default delimiter
                $delimiter = '.';
            }

            // Remove starting delimiters and spaces
            $path = ltrim($path, "{$delimiter} ");

            // Remove ending delimiters, spaces, and wildcards
            $path = rtrim($path, "{$delimiter} *");

            // Split the keys by delimiter
            $keys = explode($delimiter, $path);
        }

        do
        {
            $key = array_shift($keys);

            if (ctype_digit($key))
            {
                // Make the key an integer
                $key = (int) $key;
            }

            if (isset($array[$key]))
            {
                if ($keys)
                {
                    if (is_array($array[$key]))
                    {
                        // Dig down into the next part of the path
                        $array = $array[$key];
                    }
                    else
                    {
                        // Unable to dig deeper
                        break;
                    }
                }
                else
                {
                    // Found the path requested
                    return $array[$key];
                }
            }
            elseif ($key === '*')
            {
                // Handle wildcards

                $values = array();
                foreach ($array as $arr)
                {
                    if ($value = self::arraypath($arr, implode('.', $keys)))
                    {
                        $values[] = $value;
                    }
                }

                if ($values)
                {
                    // Found the values requested
                    return $values;
                }
                else
                {
                    // Unable to dig deeper
                    break;
                }
            }
            else
            {
                // Unable to dig deeper
                break;
            }
        }
        while ($keys);

        // Unable to find the value requested
        return $default;
    }
    
    
    
    public static function arraymap($callbacks, $array, $keys = NULL)
    {
        foreach ($array as $key => $val)
        {
            if (is_array($val))
            {
                $array[$key] = self::arraymap($callbacks, $array[$key]);
            }
            elseif ( ! is_array($keys) or in_array($key, $keys))
            {
                if (is_array($callbacks))
                {
                    foreach ($callbacks as $cb)
                    {
                        $array[$key] = call_user_func($cb, $array[$key]);
                    }
                }
                else
                {
                    $array[$key] = call_user_func($callbacks, $array[$key]);
                }
            }
        }

        return $array;
    }
    
    
    
    
    
     public static  function get_link_translation($slug , $type ){
       $id = self::get_id_by_slug($slug , $type );
       return get_page_link(  $id  );
    }

    public static function link_select($url , $class){

      $http = 'http' . (isset($_SERVER['HTTPS']) ? 's://' : '://');
      $uri  =  $http.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

      if( $url == $uri )
          echo $class;
    }

     public static function check_select($name , $value){
      if(!isset($_GET[$name]))
          return;

      if( $_GET[$name] == $value )
          echo "selected='selected'";
    }


    public static function input_value($name , $error = null ) {

        if(is_null($error)){
          echo '';
        }
        elseif(isset($_REQUEST[$name])){
             echo  $_REQUEST[$name];
        }
        else{
          echo '';
        }


    }

    public static function check_selected($key , $value){
      if( $key == $value )
          echo "selected='selected'";
    }

    public static function is_categorie($slug){
      global  $wpdb;

      $slug = esc_sql($slug);

      return $wpdb->get_var("SELECT count({$wpdb->terms}.term_id) from {$wpdb->terms}
          LEFT JOIN {$wpdb->term_taxonomy} on ({$wpdb->term_taxonomy}.term_id  = {$wpdb->terms}.term_id)
          WHERE  {$wpdb->terms}.slug = '{$slug}' AND {$wpdb->term_taxonomy}.taxonomy LIKE 'category' ");
    }


    public static function is_post($slug){
      global $wpdb;

      $slug = esc_sql($slug);

      $post_type =  $wpdb->get_var( "SELECT post_type FROM {$wpdb->posts} WHERE  post_name like '{$slug}' " );

      return ($post_type === 'post') ? true : false;
    }


    public static function postID_by_slug($slug){
      global $wpdb;

      $slug = esc_sql($slug);

      return $wpdb->get_var( "SELECT ID FROM {$wpdb->posts} WHERE  post_name like '{$slug}' and post_type like 'post' " );
    }


    public static function pageID_by_slug($slug){
      global $wpdb;

      $slug = esc_sql($slug);

      return $wpdb->get_var( "SELECT ID FROM {$wpdb->posts} WHERE  post_name like '{$slug}' and post_type like 'page' " );
    }

    public static function postSlug_by_ID($id){
      global $wpdb;

      $slug = esc_sql($id);

      return $wpdb->get_var( "SELECT post_name FROM {$wpdb->posts} WHERE  ID = {$id} and post_type like 'post' " );
    }

    public static function pageSlug_by_ID($id){
      global $wpdb;

      $slug = esc_sql($id);

      return $wpdb->get_var( "SELECT post_name FROM {$wpdb->posts} WHERE  ID = {$id} and post_type like 'page' " );
    }

    public static function siteInfo() {
        return array(
          'blog_title'  => self::getSiteTitle(),
          'name'        => get_bloginfo('name'),
          'description' => self::get_description(),
          'admin_email' => get_bloginfo('admin_email'),

          'url'   => get_bloginfo('url'),
          'wpurl' => get_bloginfo('wpurl'),

          'stylesheet_directory' => get_bloginfo('stylesheet_directory'),
          'stylesheet_url'       => get_bloginfo('stylesheet_url'),
          'template_directory'   => get_bloginfo('template_directory'),
          'template_url'         => get_bloginfo('template_url'),

          'atom_url'     => get_bloginfo('atom_url'),
          'rss2_url'     => get_bloginfo('rss2_url'),
          'rss_url'      => get_bloginfo('rss_url'),
          'pingback_url' => get_bloginfo('pingback_url'),
          'rdf_url'      => get_bloginfo('rdf_url'),

          'comments_atom_url' => get_bloginfo('comments_atom_url'),
          'comments_rss2_url' => get_bloginfo('comments_rss2_url'),

          'charset'        => get_bloginfo('charset'),
          'html_type'      => get_bloginfo('html_type'),
          'language'       => get_bloginfo('language'),
          'text_direction' => get_bloginfo('text_direction'),
          'version'        => get_bloginfo('version'),

          'is_user_logged_in' => is_user_logged_in()
        );
    }


    public static function get_description(){
      global $post;

      if(is_page() || is_single()){
        return strip_tags (  wp_trim_words($post->post_content, 150, "" ) );
      }
      else{
        return  get_bloginfo('description');
      }
    }

    public static function getSiteTitle() {
        if (is_home()) {
          return get_bloginfo('name');
        }
        elseif(is_search()){

          if(isset($_GET['s'])){

            $title = (ICL_LANGUAGE_CODE == 'en') ? sprintf("Search for %s" , $_GET['s']) : sprintf( "Pesquisou por  %s"  , $_GET['s']);
            return $title;
          }
          else{

            $title = (ICL_LANGUAGE_CODE == 'en') ?  "Search "   :  "Pesquisa";
            return    $title;

          }
        }
        else {

        $title = wp_title('-' , true, 'right');


        //$title =  str_replace(array(' ' , 'Arquivos')  , "",  $title);

          return  $title ;
        }
    }

    public static function get_wp_template(){
        if( is_post_type_archive()){
            return 'postTypeArchive';
        }
        elseif(is_category()){
            return 'category';
        }
        elseif(is_attachment()){
            return 'attachment';
        }
        elseif(is_page()){
            return 'page';
        }
        elseif(is_single() && !is_attachment()){
            return 'single';
        }
        elseif(is_home() || is_front_page() ){
            return 'frontpage';
        }
        elseif(is_search()){
            return 'search';
        }

        elseif( is_tag()){
            return 'tag';
        }
        elseif( is_archive()){
            return 'archive';
        }
        elseif( is_404()){
            return '404';
        }
    }


    public function flashdata($type, $message=null) {
        if (func_num_args() == 1) {
            $message = $this->get_flashdata($type);
            self::unset_flashdata($type);
            return $message;
        }
        self::set_flashdata($type, $message);
    }


    public static function display_flashdata() {
        $flashes = self::get_all_flashdata();
        $html = '';

        if (!empty($flashes)) {
            foreach ($flashes as $type => $message) {
                $classes   = array();
                $classes[] = $type;
                $html .= '
                    <div id="message" class="'.implode(' ', $classes).'">
                        <p>
                            '.$message.'
                        </p>
                    </div>';
                self::unset_flashdata($type);
            }
        }

        echo $html;
    }

    public static function set_flashdata($type, $message) {
        self::init_flashdata();
        $_SESSION['gwp_mvc_flash'][$type] = $message;
    }

   public static function unset_flashdata($type) {
        self::init_flashdata();
        unset($_SESSION['gwp_mvc_flash'][$type]);
    }

    public static function unset_all_flashdata() {
        self::init_flashdata();
        unset($_SESSION['gwp_mvc_flash']);
    }

    public static  function get_flashdata($type) {
        self::init_flashdata();
        $message = empty($_SESSION['gwp_mvc_flash'][$type]) ? null : $_SESSION['gwp_mvc_flash'][$type];
        //echo var_dump( $type);
        self::unset_flashdata($type);
        return $message;
    }


    public static function get_all_flashdata() {
        self::init_flashdata();
        return $_SESSION['gwp_mvc_flash'];
    }


    public static function init_flashdata() {
        if (!isset($_SESSION['gwp_mvc_flash'])) {
            $_SESSION['gwp_mvc_flash'] = array();
        }
    }

    public static function refresh() {
        $location = self::current_uri();
        $this->redirect($location);
    }

    public static function redirect($location, $status=302) {

        if (headers_sent()) {
            $html = '
                <script type="text/javascript">
                    window.location = "'.$location.'";
                </script>';
            echo $html;
        } else {
            wp_redirect($location, $status);
        }

        die();

    }

    public static  function current_uri() {
        return $_SERVER['REQUEST_URI'];
    }

    public static function get_protocol(){
       $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
       return $protocol;
    } 

    public static function current_url(){
       $url = self::get_protocol() . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
       return $url;
    } 
}
?>