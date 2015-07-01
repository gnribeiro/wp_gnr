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
            $this->view->set('template', "PAGE");
            $content = $this->view->render('page');
            $this->content($content);
        }
    }
?>