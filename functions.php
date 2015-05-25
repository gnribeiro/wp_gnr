<?php
define('EXT', '.php');
define('DOCROOT', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR);

if(is_file( $core = dirname(__FILE__).'/libs/Core.php' ) )
    include $core;

?>