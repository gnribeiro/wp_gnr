<?php
define('EXT', '.php');
define('DOCROOT', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR);

ini_set('display_errors', '1');
 
if(is_file( $core = dirname(__FILE__).'/libs/Core.php' ) )
    include $core;

?>