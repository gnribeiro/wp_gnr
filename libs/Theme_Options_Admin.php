<?php

Class Theme_Options_Admin{
    protected $view;
    protected $current_image = array();   
    protected $count_image   = 0;
    protected $image_process = false;
    
    public function __construct(){

        if( Helper::load_config('theme_options') ){

            add_action('admin_menu', array($this , 'gwp_theme_menu'));
            add_action('admin_init', array($this , 'gwp_register_settings') );

            add_action( 'wp_ajax_themeoptionsfile',       array( $this, 'themeoptionsfile' ) );
            //add_action( 'wp_ajax_nopriv_contacts', array( $this, 'contacts' ) );
            $this->view = View::factory();
        }
    }
    
    public function themeoptionsfile(){
    
    
        update_option( $_POST['id'], '' );
        die();
    }

    public function gwp_theme_menu()
    {
        add_menu_page( "Theme Options", "Theme Options", "manage_options", "theme-options", array($this , 'gwp_theme_page'), null, 99 );
    }


    public function gwp_theme_page() 
    {
        echo $this->view->render("admin/theme_options/content");
    }
    
    
    public function display_input_text($args)
    {
                
        foreach($args as $key => $value){ 
            
            if($key == 'id'){
                $value = stripslashes($value);
                $value = esc_attr( $value);
            }
               
            $this->view->set($key , $value) ;
        }
        
        
        echo $this->view->render("admin/theme_options/input-text");
    
    }
    
    
    public function display_input_checkbox($args)
    {
                
        foreach($args as $key => $value){ 
            
            if($key == 'id'){
                $value = stripslashes($value);
                $value = esc_attr( $value);
            }
               
            $this->view->set($key , $value) ;
        }
        
     
        echo $this->view->render("admin/theme_options/checkbox");
    
    }
    
    
    public function display_input_select($args)
    {
                
        foreach($args as $key => $value)
        { 
            
            if($key == 'id'){
                $value = stripslashes($value);
                $value = esc_attr( $value);
            }
               
            $this->view->set($key , $value) ;
        }
        
        echo $this->view->render("admin/theme_options/select");
    }
    
    
    public function display_input_radio($args)
    {
                
        foreach($args as $key => $value)
        { 
            
            if($key == 'id'){
                $value = stripslashes($value);
                $value = esc_attr( $value);
            }
               
            $this->view->set($key , $value) ;
        }
        
     
        echo $this->view->render("admin/theme_options/radio");
    
    }
    
    
    public function display_input_file_image($args)
    {
        foreach($args as $key => $value)
        { 
            
            if($key == 'id'){
                $value = stripslashes($value);
                $value = esc_attr( $value);
            }
               
            $this->view->set($key , $value) ;
        }
        
     
        echo $this->view->render("admin/theme_options/file_image");
        
        
    }
    
    
    
    public function handle_image_upload($plugin_options)
    {
        //wp_die( pr($plugin_options) );
        if(!empty( $_POST[ 'optionsgwpteme' ] )){
            
//            $nrfiles = count($_FILES) - 1;
//            $name    = $this->current_image[$this->count_image]['name'];
//            $id      = $this->current_image[$this->count_image]['id'];
//            
//            
//            error_log($nrfiles);
//            error_log($this->count_image);
//            error_log( $name  );
//            $this->count_image = $this->count_image  + 1; 
//            
//           
//            
//            
//
//            if(!empty($_FILES[$name]["tmp_name"]) || !isset($_FILES[$name]['error']) ||  is_array($_FILES[$name]['error']))
//            { 
//                if(   $this->count_image > $nrfiles ) $this->count_image = 0; 
//                $finfo    = new finfo(FILEINFO_MIME_TYPE);
//                $is_image = array_search($finfo->file($_FILES[$name]['tmp_name']),
//                        array(
//                            'jpg' => 'image/jpeg',
//                            'png' => 'image/png',
//                            'gif' => 'image/gif',
//                        ),
//                        true
//                    );
//                
//                if (false !== $is_image)
//                {
//                    $urls = wp_handle_upload($_FILES[$name], array('test_form' => FALSE));
//
//                    if ( $urls && !isset( $urls['error'] ) ) {
//                         $temp = $urls["url"];
//                         if(   $this->count_image  ==$nrfiles ) $this->count_image = 0; 
//                         return $temp;   
//                    }       
//                }
//            } 
//
//            if( get_option($id) && get_option($id) !=='' ){
//                if(   $this->count_image  > $nrfiles ) $this->count_image = 0; 
//                return get_option($id); 
//            }
            $nrfiles = count($_FILES) - 1;
            if($this->count_image == 0){
                
                
                $keys = array_keys($_FILES); 
                $i = 0;
            
                foreach ( $_FILES as $image ) {  
                    // if a files was upload 
                    $name    = $this->current_image[$i]['name'];
                     error_log( $name ) ;
                    if ($image['size']) 
                    {     
                        // if it is an image    
                        if ( preg_match('/(jpg|jpeg|png|gif)$/', $image['type']) ) {       
                            $override = array('test_form' => false);      
                            // save the file, and store an array, containing its location in $file      
                            //$file = wp_handle_upload( $image, $override );      
                            //$plugin_options[$keys[$i]] = $file['url'];     
                        }
                        else {       
                             //$options = get_option('plugin_options');       
                             //$plugin_options[$keys[$i]] = $options[$logo];       
                            // Die and let the user know that they made a mistake.       
                           // wp_die('No image was uploaded.');     
                        }   
                    }
                    // Else, the user didn't upload a file.   
                    // Retain the image that's already on file.  
                    else {   
                        //$options = get_option('plugin_options');     
                        //$plugin_options[$keys[$i]] = $options[$keys[$i]];   
                    }   
                    $i++; 
                } 
                return $plugin_options;
            }
            
            $this->count_image = $this->count_image  + 1; 
            
            if($this->count_image === $nrfiles){ 
                $this->image_process = 0;
            
            }
        
        }
    }

    public function gwp_register_settings()
    {
        add_settings_section("section", "All Settings", null, "theme-options");
        
        $options = Helper::load_config('theme_options') ;
        $validate_fields = array('text', 'checkbox', 'select', 'radio', 'file_image');
        
        if(count($options['fields'])){
            foreach ($options['fields'] as $key => $value) {

                if(!in_array($value['type'] , $validate_fields))
                    continue;
                $value['args']['id'] = $value['id'];       
                switch ( $value['type']  ) {
                     case 'text':
                        add_settings_field( $value['id'], $value['title'], array($this , 'display_input_text'), "theme-options", "section", $value['args'] );
                        register_setting("section",  $value['id'] );
                    break;
                     case 'checkbox':
                        add_settings_field( $value['id'], $value['title'], array($this , 'display_input_checkbox'), "theme-options", "section", $value['args'] );
                        register_setting("section",  $value['id']);
                    break;
                     case 'select':
                        add_settings_field( $value['id'], $value['title'], array($this , 'display_input_select'), "theme-options", "section", $value['args'] );
                        register_setting("section",  $value['id']);
                    break;
                    case 'radio':
                        add_settings_field( $value['id'], $value['title'], array($this , 'display_input_radio'), "theme-options", "section", $value['args'] );
                        register_setting("section",  $value['id']);
                    break;
                    case 'file_image':
                        $this->current_image[] = array(
                            'name' =>$value['args']['name'],
                            'id'   =>$value['args']['id'],
                        );
                    
                        add_settings_field( $value['id'], $value['title'], array($this , 'display_input_file_image'), "theme-options", "section", $value['args'] );
                        register_setting("section",  $value['id'], array($this , 'handle_image_upload'));
                    break;
                }
            }
        }

    }


    function gwp_display_setting($args)
    {

        extract( $args );

        $option_name = 'gwp_theme_options';
        $options     = get_option( $option_name );

        switch ( $type ) {
              case 'text':
                  $options[$id] = stripslashes($options[$id]);
                  $options[$id] = esc_attr( $options[$id]);
                  echo "<input class='regular-text$class' type='text' id='$id' name='" . $option_name . "[$id]' value='$options[$id]' />";
                  echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";
              break;
              case 'textarea':
                  $options[$id] = stripslashes($options[$id]);
                  $options[$id] = esc_attr( $options[$id]);
                  echo "<textarea class='regular-text$class'  id='$id' name='" . $option_name . "[$id]'>$options[$id]</textarea>";
                  echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";
              break;
        }
    }
}
?>