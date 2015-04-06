<?php 

Class  View{
    protected $_data = array();
    protected $_file;
    protected static $_global_data;
    public $path;

    public function __construct($file = NULL, array $data = NULL){
    
        $this->path = VIEWS;

        if ($file !== NULL)
        {
            $this->set_filename($file);
        }
     
        if ($data !== NULL)
        {
            // Add the values to the current data
            $this->_data = $data + $this->_data;
        }
    }

    public static function factory($file = NULL, array $data = NULL){
        return new View($file, $data);
    }


    public function bind($key, & $value){
        $this->_data[$key] =& $value;
 
        return $this;
    }


    public static function bind_global($key, & $value){
        View::$_global_data[$key] =& $value;
    }


    
    public function render($file = NULL){
        if ($file !== NULL){
            $this->set_filename($file);
        }
     
        if (empty($this->_file)){
            throw new Exception('You must set the file to use within your view before rendering');
        }

        return View::capture($this->_file, $this->_data);
    }

    public function set($key, $value = NULL){
        if (is_array($key)){
            foreach ($key as $name => $value){
                $this->_data[$name] = $value;
            }
        }
        else{
            $this->_data[$key] = $value;
        }
     
        return $this;
    }


    public function set_filename($file){

       // echo $this->path.$file.'.php';
        if ((is_file($path = $this->path.$file.EXT)) === FALSE)
        {
            throw new Exception('The requested view :file '.$path.' could not be found');
        }

        $this->_file = $path;
     
        return $this;
    }


    public static function set_global($key, $value = NULL){
        if (is_array($key))
        {
            foreach ($key as $key2 => $value)
            {
                View::$_global_data[$key2] = $value;
            }
        }
        else
        {
            View::$_global_data[$key] = $value;
        }
    }


    protected static function capture($kohana_view_filename, array $kohana_view_data){
        // Import the view variables to local namespace
        extract($kohana_view_data, EXTR_SKIP);
     
        if (View::$_global_data)
        {
            // Import the global view variables to local namespace
            extract(View::$_global_data, EXTR_SKIP);
        }
     
        // Capture the view output
        ob_start();
     
        try
        {
            // Load the view within the current scope
            include $kohana_view_filename;
        }
        catch (Exception $e)
        {
            // Delete the output buffer
            ob_end_clean();
     
            // Re-throw the exception
            die( "Can't capture this view" . $kohana_view_filename );
        }
     
        // Get the captured output and close the buffer
        return ob_get_clean();
    }


    public function __unset($key){
    
        unset($this->_data[$key], View::$_global_data[$key]);
    
    }

    public function & __get($key){
        if (array_key_exists($key, $this->_data)){
            return $this->_data[$key];
        }
        elseif (array_key_exists($key, View::$_global_data)){
            return View::$_global_data[$key];
        }
        else{
            throw new Exception('View variable is not set');
        }
    }


    public function __isset($key){
        return (isset($this->_data[$key]) OR isset(View::$_global_data[$key]));
    }


    public function __set($key, $value){
        $this->set($key, $value);
    }


    public function __toString(){
       return $this->render(); 
    }
}
?>