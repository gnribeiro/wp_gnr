<?php
define('EXT', '.php');
define('DOCROOT', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR);

ini_set('display_errors', '1');

if(is_file( $core = dirname(__FILE__).'/libs/Core.php' ) )
    include $core;





 
function add_theme_menu_item()
{
    add_menu_page("Theme Panel", "Theme Panel", "manage_options", "theme-panel", "theme_settings_page", null, 99);
}
 
//add_action("admin_menu", "add_theme_menu_item");


function theme_settings_page()
{
    ?>
        <div class="wrap">
        <h1>Theme Panel</h1>
        <form method="post" action="options.php" enctype="multipart/form-data">
            <?php
                settings_fields("section");
                do_settings_sections("theme-options");      
                submit_button(); 
            ?>          
        </form>
        </div>
    <?php
}



function display_twitter_element()
{
    ?>
        <input type="text" name="twitter_url" id="twitter_url" value="<?php echo get_option('twitter_url'); ?>" />
    <?php
}
 
function display_facebook_element()
{
    ?>
        <input type="text" name="facebook_url" id="facebook_url" value="<?php echo get_option('facebook_url'); ?>" />
    <?php
}
 

function display_layout_element()
{
    ?>
        <input type="checkbox" name="theme_layout" value="1" <?php checked(1, get_option('theme_layout'), true); ?> /> 
    <?php
}



function display_remove_logo()
{
    ?>
        <input type="checkbox" name="remove_logo" value="0">
    <?php
}
function logo_display()
{
    ?>
        <input type="file" name="logo" /> 
        <div><img src=" <?php echo get_option('logo'); ?>" alt=""></div>
        <?php echo get_option('logo'); ?>
        
   <?php
}
 
function handle_logo_upload()
{   
    wp_die(pr($_POST));
    
    if($_POST['remove_logo'])
        return "";
    
    if(!empty($_FILES["logo"]["tmp_name"]))
    {
        $urls = wp_handle_upload($_FILES["logo"], array('test_form' => FALSE));
             
        if ( $urls && !isset( $urls['error'] ) ) {
             $temp = $urls["url"];
            return $temp;   
        }
    }
  
    return get_option('logo');
}


function display_theme_panel_fields()
{
    add_settings_section("section", "All Settings", null, "theme-options");
     
    add_settings_field("twitter_url", "Twitter Profile Url", "display_twitter_element", "theme-options", "section");
    add_settings_field("facebook_url", "Facebook Profile Url", "display_facebook_element", "theme-options", "section");
  
    add_settings_field("theme_layout", "Do you want the layout to be responsive?", "display_layout_element", "theme-options", "section");
    
    register_setting("section", "twitter_url");
    register_setting("section", "facebook_url");
    register_setting("section", "theme_layout");

    add_settings_field("logo", "Logo", "logo_display", "theme-options", "section");  
 
    register_setting("section", "logo", "handle_logo_upload");
    
    
    add_settings_field("remove_logo", "Remove Logo", "display_remove_logo", "theme-options", "section");
    
}
 
//add_action("admin_init", "display_theme_panel_fields");
?>