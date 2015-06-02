<?php global $post , $site; ?>

<article>
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <h1><?php the_title() ?></h1>
        <div><?php the_content() ?></div>
    <?php endwhile; endif ?>
</article>


<?php
    //echo do_shortcode('[youtube id="qPKKtvkVAjY" ]Check out this video![/youtube]');
    //do_action("wploop_query" , 'post_type=page&name=teste');
    //do_action("wploop");
    //$site->set_view('teste' , array('name'=>'goncalo')); 
    
    //$conf = Helper::load_config('site');
    //echo  $conf['default_lang'] ;
?>