<?php

class GWP_Autoloader {

    public function __construct($prepend = false) {

        if (version_compare(phpversion(), '5.3.0', '>=')) {
            spl_autoload_register(array(__CLASS__, 'autoload'), true, $prepend);
        }
        else {
            spl_autoload_register(array(__CLASS__, 'autoload'));
        }
    }


    public  function autoload($class){
        
        if (is_file($file  =  LIBS .$class.'.php')) {
  
            @require_once $file;
        }
      
    }

}