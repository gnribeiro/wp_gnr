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

    public static  function get_id_by_slug($slug , $type){
      $page = get_page_by_path( $slug );
       return icl_object_id($page->ID, $type, true);
      
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


    public static function input_value($name , $error = null , $method = 'POST') {

        $request_method =  ($method === 'POST') ? $_POST : $_GET;
        
        if(is_null($error)){
          echo '';
        }
        elseif(isset($request_method[$name])){

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
        elseif(is_post_type_archive('promotions')){
           $title = (ICL_LANGUAGE_CODE == 'en') ?  "Promotions "   :  "Promoções";
            return $title;
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
}
?>