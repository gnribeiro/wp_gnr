<?php

Class Base{
    protected $view;
    protected $defauld_view = 'index';
    public    $uri;
    public    $data_header;
    public    $data_main;
    public    $data_footer;

    public function __construct() 
    {
        $this->uri         = rtrim(preg_replace(array('@\?.*$@' , '#^/#', '@page/[\d]+@'), array('' , '', ''), $_SERVER['REQUEST_URI']),"/");
        $this->data_header = array();
        $this->data_main   = array();
        $this->data_footer = array();
        $this->view        = View::factory();
    }


    public function set_head()
    {
        return Helper::siteInfo();
    }
    
    public function set_data_header($data_header= array())
    {   
        foreach($data_header as $key => $value){ 
          $this->data_header[$key]  =  $value;
        }
        
    }
    
    
    public function set_data_footer($data_footer= array())
    {   
        foreach($data_footer as $key => $value){ 
          $this->data_footer[$key]  =  $value;
        }
        
    }
    
        
    public function set_data_main($data_main= array())
    {   
        foreach($data_main as $key => $value){ 
          $this->data_main[$key]  =  $value;
        }
        
    }


    public function set_header()
    {

        $this->view->set('data_header', $this->data_header);
        $this->view->set('head', $this->view->render('template/head'));
        return $this->view->render('template/header');
    }


    public function set_footer()
    {

        $this->view->set('data_footer', $this->data_footer);
        return $this->view->render('template/footer');
    }

    
    public function content($content)
    {
       
        echo $this->set_header();
        
        $this->view->set('content', $content);
        
        echo $this->view->render('template/main');
        echo $this->set_footer();
    }
    
    
    public function fallback()
    {
        $this->set_content();
    }
    
    
    public function set_content($file = false , $data = array())
    {
        if($file === false ){
            $view = 'fallback';
        }    
        elseif (file_exists (VIEWS . $file .EXT ))
        {
            $view = $file;
        }
        else{
            $view ='fallback';
        }
            
        if(count($data)){
            
            foreach($data as $key => $value){ 
                $this->view->set($key , $value);
            }    
        }
            
        $content = $this->view->render($view);
        $this->content($content);    
    }
        
        
    public function set_view($file , $data = array())
    {        
        if(!file_exists (VIEWS . $file .EXT )){
            
            pr("THIS VIEW ({ $file }), DON`T EXIST");
            return;
        }
                
            
        if(count($data)){
            
            foreach($data as $key => $value){ 
                $this->view->set($key , $value);
            }    
        }
            
        echo  $this->view->render($file);
             
    }
        
        
    public function get_view($file , $data = array())
    {
            
        if(!file_exists (VIEWS . $file .EXT )){
            pr("THIS VIEW ({$file}), DON`T EXIST");
            return;
        }

        if(count($data)){
            foreach($data as $key => $value){ 
                $this->view->set($key , $value);
            }    
        }

        return  $this->view->render($file);
    }
    

    public function lang_menu()
    {

        if(!defined(ICL_LANGUAGE_CODE))
            return;

        global $wp;
        $langs = array();


        $languages = icl_get_languages('skip_missing=0');

        if(count($languages) > 1){
          foreach($languages as $code => $l)
          {
            $query_string = (!empty($_SERVER["QUERY_STRING"])) ? '?'.$_SERVER["QUERY_STRING"] : '';

            if( isset($_GET['product_cat'])  && !empty($query_string) ){

                     $query_string =  str_replace("-en", '', $query_string );


                     if($l['language_code'] == 'en')
                        $query_string =  $query_string.'-en';
            }

            $langs[] = array(
                    'url'      =>  $l['url'] . $query_string,
                    'selected' => (ICL_LANGUAGE_CODE == $l['language_code']) ? 1 : 0,
                    'code'     => $l['language_code'],
                    'label'    => trim(preg_replace('@-\w+$@','', $l['language_code']))
            ) ;
          }

          $this->view->set('langs' , $langs);
          return $this->view->render('lang_menu');
        }
    }

    
    public function pagination($num_pages)
    {
        $big = 999999999;

        $pagination = paginate_links( array(
            'base'         => esc_url( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', htmlspecialchars_decode( get_pagenum_link( 999999999 ) ) ) ) ),
            'format'    => '?page=%#%',
            'prev_text' => __( '&laquo;', 'text-domain' ),
            'next_text' => __( '&raquo;', 'text-domain' ),
            'total'     => $num_pages,
            'current'   =>  max(1 , get_query_var('paged')),
        ) );

        return $pagination;
    }


    public function current_url() 
    {
        return $_SERVER['REQUEST_URI'];
    }
}
?>