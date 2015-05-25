<?php 
   
    class Site extends Base
    {
        
        function __construct()
        {
            parent::__construct();
        
        }
        
        
        public function index()
        {
            $content = $this->view->render('index');
            $this->content($content);
        }
        
        
        public function page()
        {
            $this->view->template  = "PAGE";
            $content = $this->view->render('page');
            $this->content($content);
        }
        
        
        public function set_view($file , $data = array()){
            $view = (file_exists (VIEWS . $file )) ? $file : 'index';
            
            if(count($data)){
            
                foreach($data as $key => $value){ 
                    $this->view->set($key , $value);
                }    
            }
            
            $content = $this->view->render('page');
            $this->content($content);    
        }


    }
?>