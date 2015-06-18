<?php 
   
    class Site extends Base
    {
        
        function __construct()
        {
            parent::__construct();
            $this->data_header = array( "teste2" => "data2 2header");
        
        }
        
        
        public function index()
        {
            $data_header = array( "teste" => "data2 header");
            $this->set_data_header($data_header);
            
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