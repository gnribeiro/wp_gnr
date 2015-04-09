<?php

Class CustomPostTypesAdmin{

    protected  $cpt  = array();


    public function __construct(){
        $this->get_customPosts();
        add_action('init', array($this, 'register_ctps'));
    }


    protected function get_customPosts(){

        if($cpt = Helper::load_config('customPostTypes')){
           if(is_array($cpt)&& count($cpt)){
                foreach ($cpt as $key => $value) {

                    if(is_array($value)){
                        $this->cpt[$key] = $value;
                    }
                }
            }
        }
    }


    public function register_ctps(){
        foreach ($this->cpt as $key => $value) {
            register_post_type( $key, $value );
           // flush_rewrite_rules();
        }
    }
}
?>