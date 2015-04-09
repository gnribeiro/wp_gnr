<?php
$views  = 'views';
$libs   = 'libs';
$config = 'config';

if ( ! is_dir($views) AND is_dir(DOCROOT.$views)){
      $views = DOCROOT.$views;
}

if ( ! is_dir($libs) AND is_dir(DOCROOT.$libs)){
    $libs = DOCROOT.$libs;
}

if ( ! is_dir($config) AND is_dir(DOCROOT.$config)){
    $config = DOCROOT.$config;
}

define('VIEWS'     , realpath($views).DIRECTORY_SEPARATOR);
define('LIBS'      , realpath($libs).DIRECTORY_SEPARATOR);
define('CONFIG'    , realpath($config).DIRECTORY_SEPARATOR);
define('THEMEURL'  , get_template_directory_uri().DIRECTORY_SEPARATOR);
define('THEMEPATH' , get_template_directory().DIRECTORY_SEPARATOR);

unset($libs, $views, $config);

 if(is_file( $customPostTypes =  CONFIG . 'customPostTypes.php'))
        include $customPostTypes ;

include LIBS . 'View' . EXT;
include LIBS . 'Helper' . EXT;

$load_libs = array(
    'Gwp_Hooks',
    'Base',
    'Site',
    'Gwp_Shortcodes',
    'Gwp_Form_Handler'
);


foreach ($load_libs as $value) {
    if(!is_admin()){
        if(is_file( $class = LIBS . $value . EXT ))
            include $class ;
    }
}

if(is_admin()){
    include LIBS . 'Admin' . EXT;
    $admin = new Admin();
}

if(!is_admin()){
    $site_instance = new Site();
    $GLOBALS['site'] = $site_instance;
}

function pr( $var ) {
    print( '<pre class="dump">' );
    var_dump( $var );
    print( '</pre>' );
}

?>