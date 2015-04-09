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


    public function gwp_theme_menu(){
        add_theme_page( 'Theme Option', 'Theme Options', 'manage_options', 'gwp_theme_options.php', array($this , 'gwp_theme_page'));
    }


    public function gwp_theme_page() {
        echo $this->view->render("admin/theme_options/content");
    }


    public function gwp_register_settings(){
        register_setting( 'gwp_theme_options', 'gwp_theme_options', array($this , 'gwp_validate_settings') );

        $options = Helper::load_config('theme_options') ;

        foreach ($options['sections'] as $key => $value) {
            add_settings_section( $value['id'], $value['title'], array($this , $value['callback']), $value['page'] );
        }

        foreach ($options['fields'] as $key => $value) {
            add_settings_field( $value['id'], $value['title'], array($this , $value['callback']), $value['page'], $value['section'], $value['args'] );
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

    function gwp_validate_settings($input){

      //wp_die(var_dump($input));
      foreach($input as $k => $v)
      {
        $newinput[$k] = trim($v);

        // Check the input is a letter or a number
        if(!preg_match('/^[A-Z0-9 _\.-]*$/i', $v)) {
          $newinput[$k] = '';
        }
      }

      return $newinput;
    }

    function gwp_display_section($section){

    }

}

?>