<?php get_header()?>

<?php 

do_action('publish_post', '22');
echo apply_filters('comment_text' , "teste2" , 'arg1') ?>

<?php get_footer(); ?>