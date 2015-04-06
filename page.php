<?php get_header()?>

    <?php if (have_posts()) ?>
        <?php while (have_posts()) : the_post();  ?>
            <h1 class="title"><?php the_title(); ?></h1>
            <div class="bocontent"><?php the_content(); ?></div>                         
        <?php endwhile;  ?> 
    <?php endif; ?>  

<?php get_footer()?>

