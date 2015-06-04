<?php

Class Theme_Options_Admin{
    protected $view;

    public function __construct(){

        if( Helper::load_config('theme_options') ){

            add_action('admin_menu', array($this , 'gwp_theme_menu'));
            add_action('admin_init', array($this , 'gwp_register_settings') );

            $this->view = View::factory();
        }
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

    public function gwp_register_settings()
    {
        add_settings_section("section", "All Settings", null, "theme-options");
        
        $options = Helper::load_config('theme_options') ;
        $validate_fields = array('text', 'checkbox', 'select', 'radio');
        
        if(count($options['fields'])){
            foreach ($options['fields'] as $key => $value) {

                if(!in_array($value['type'] , $validate_fields))
                    continue;
                $value['args']['id'] = $value['id'];       
                switch ( $value['type']  ) {
                     case 'text':
                        add_settings_field( $value['id'], $value['title'], array($this , 'display_input_text'), "theme-options", "section", $value['args'] );
                        register_setting("section",  $value['id']);
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