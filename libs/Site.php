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
        
        
        public function set_content($file , $data = array()){
            
            $view = (file_exists (VIEWS . $file .EXT )) ? $file : 'fallback';
            
            if(count($data)){
            
                foreach($data as $key => $value){ 
                    $this->view->set($key , $value);
                }    
            }
            
            $content = $this->view->render($view);
            $this->content($content);    
        }
        
        
        public function set_view($file , $data = array()){
            
            if(!file_exists (VIEWS . $file .EXT )){
            
                pr("THIS VIEW ({ $file }), DON`T EXIST");
                return;
            }
                
            
            if(count($data)){
            
                foreach($data as $key => $value){ 
                    $this->view->set($key , $value);
                }    
            }
            
            echo  $this->view->render($file);
             
        }


    }
?>