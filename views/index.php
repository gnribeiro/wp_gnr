<?php global $post ?>

<article>
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <h1><?php the_title() ?></h1>
        <div><?php the_content() ?></div>
    <?php endwhile; endif ?>
</article>


<?php

    echo do_shortcode('[youtube id="qPKKtvkVAjY" ]Check out this video![/youtube]');

    echo apply_filters('comment_text' , "teste2" , 'arg1') ;

    
    
    

    $view =  View::factory();
    $view->name = "goncalo";
    echo $view->render("teste");
?>








<?php 

$conf = Helper::load_config('site');
echo  $conf['default_lang'] ; ?>