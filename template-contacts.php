<?php 

/*
* Template Name: Contacts
*/

get_header(); 
?>

<article class="contacts">
    <?php if(have_posts()): while(have_posts()): the_post(); ?>
        <header><h1><?php the_title() ?></h1></header>
        
        <div class="bocontent"><?php the_content() ?></div>   
    <?php endwhile; endif; ?>

    <form action="" method="post">
        <label for="nome">Name</label>
        <input type="text" name="nome" id="nome" value="<?php Helper::input_value( "nome"  , getFlash("error"))?>" />
         <div class="error">
            <?php if(getFlash("nome-error")): echo getFlash("nome-error"); endif;?>
        </div> 
       
        <label for="phone">Phone</label>
         <input type="text" name="phone" id="phone" value="<?php Helper::input_value( "phone"  , getFlash("error"))?>"/> 
         <div class="error">
            <?php if(getFlash("phone-error")): echo getFlash("phone-error"); endif;?>
        </div>

        <label for="email">Email</label>
        <input type="text" name="email" id="email" value="<?php Helper::input_value( "email"  , getFlash("error"))?>"/> 
         <div class="error">
            <?php if(getFlash("email-error")): echo getFlash("email-error"); endif;?>
        </div>
        
        <label for="mensage">mensage</label>
        <textarea name="mensage" id="mensage" cols="30" rows="10"><?php Helper::input_value( "mensage"  , getFlash("error"))?></textarea>  
        <div class="error">
            <?php if(getFlash("mensage-error")): echo getFlash("mensage-error"); endif;?>
        </div>

        <input type="submit" name="send-contact" value="Send" />
        <?php wp_nonce_field( 'contact-form' ); ?>
        <input type="hidden" name="action" value="contacts" />
        <?php unsetFlash(); ?>
    </form>
</article>



<?php  get_footer(); ?>