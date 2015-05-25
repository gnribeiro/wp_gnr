<?php

require_once LIBS . 'Theme_Options_Admin.php';

Class Admin {
    private $settings;

    public function __construct($settings = false) {
        $lang = Helper::load_config('site');
        
        $theme_options = new Theme_Options_Admin();
        $this->register_menus();
        
        if($lang['multilang'])
        {
            add_action('after_setup_theme', array($this, 'poe_lang'));
        }
        
        
        add_theme_support( 'post-thumbnails' );
    }

    protected function register_menus()
    {
        if($menus = Helper::load_config('menus'))
        {
            if(is_array($menus)&&count($menus))
            {
                foreach ($menus as  $value) 
                {
                    if(is_array($value))
                    {
                        register_nav_menus($value);
                    }
                }
            }
        }
    }

    public function poe_lang()
    {
        if($lang = Helper::load_config('lang'))
        {
            if(is_array($lang)&&count($lang)&& array_key_exists('folder', $lang) && array_key_exists('name', $lang)){
                load_theme_textdomain($lang['name'], get_template_directory() . $lang['folder'] );
            }
        }
    }
}
?>