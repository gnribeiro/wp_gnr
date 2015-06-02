<?php 
global $post , $site;

if (  $query->post_count) : 
    foreach (  $query->posts as $post) : 
  
    $site->set_view($view , array('post'=>$post)); 
       
    endforeach ; wp_reset_postdata();  
else :
  echo $else_view; 
endif; 
?>


