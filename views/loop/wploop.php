 <?php 
    global $post, $site; 
    
    if ( have_posts() ) : 
        while ( have_posts() ) : the_post(); 
             $site->set_view($view , array('post'=>$post));
        endwhile; 
    else :
      echo $else_view; 
    endif; 
?>