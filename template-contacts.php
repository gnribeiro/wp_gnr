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
        <input type="text" name="nome" id="nome" value="<?php Helper::input_value( "nome"  , Helper::get_flashdata("error"))?>" />
         <div class="error">
            <?php if(Helper::get_flashdata("nome-error")): echo Helper::get_flashdata("nome-error"); endif;?>
        </div>

        <label for="phone">Phone</label>
         <input type="text" name="phone" id="phone" value="<?php Helper::input_value( "phone"  , Helper::get_flashdata("error"))?>"/>
         <div class="error">
            <?php if(Helper::get_flashdata("phone-error")): echo Helper::get_flashdata("phone-error"); endif;?>
        </div>

        <label for="email">Email</label>
        <input type="text" name="email" id="email" value="<?php Helper::input_value( "email"  , Helper::get_flashdata("error"))?>"/>
         <div class="error">
            <?php if(Helper::get_flashdata("email-error")): echo Helper::get_flashdata("email-error"); endif;?>
        </div>

        <label for="mensage">mensage</label>
        <textarea name="mensage" id="mensage" cols="30" rows="10"><?php Helper::input_value( "mensage"  , Helper::get_flashdata("error"))?></textarea>
        <div class="error">
            <?php if(Helper::get_flashdata("mensage-error")): echo Helper::get_flashdata("mensage-error"); endif;?>
        </div>

        <input type="submit" name="send-contact" value="Send" />
        <?php wp_nonce_field( 'contact-form' ); ?>
        <input type="hidden" name="action" value="contacts" />
        <?php Helper::unset_all_flashdata(); ?>
    </form>
</article>
<?php  get_footer(); ?>