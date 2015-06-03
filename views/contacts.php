<?php global $post ?>
<article class="contacts">
    <?php if(have_posts()): while(have_posts()): the_post(); ?>
        <header><h1><?php the_title() ?></h1></header>

        <div class="bocontent"><?php the_content() ?></div>
    <?php endwhile; endif; ?>

    <form action="" method="post">
        <?php $error =  Helper::get_flashdata("error"); $error_nome =  Helper::get_flashdata("nome-error");?>
        <label for="nome">Name</label>
        <input type="text" name="nome" id="nome" value="<?php Helper::input_value( "nome"  , $error)?>" />
         <div class="error">
            <?php if($error_nome): echo $error_nome; endif;?>
        </div>

        <label for="phone">Phone</label>
         <?php $error_phone =  Helper::get_flashdata("phone-error");?>
         <input type="text" name="phone" id="phone" value="<?php Helper::input_value( "phone"  , $error)?>"/>
         <div class="error">
            <?php if($error_phone): echo $error_phone; endif;?>
        </div>

        <label for="email">Email</label>
        <?php $error_email =  Helper::get_flashdata("email-error");?>
        <input type="text" name="email" id="email" value="<?php Helper::input_value( "email"  , $error)?>"/>
         <div class="error">
            <?php if($error_email): echo $error_email; endif;?>
        </div>

        <label for="mensage">mensage</label>
        <?php $error_mensage =  Helper::get_flashdata("mensage-error");?>
        <textarea name="mensage" id="mensage" cols="30" rows="10"><?php Helper::input_value( "mensage"  , $error)?></textarea>
        <div class="error">
            <?php if($error_mensage): echo $error_mensage; endif;?>
        </div>

        <input type="submit" name="send-contact" value="Send" />
        <?php wp_nonce_field( 'contact-form' ); ?>
        <input type="hidden" name="action" value="contacts" />
        <?php //Helper::unset_all_flashdata(); ?>
    </form>
</article>