<div class="section panel">
    <h1> Theme Options</h1>
    <br>
    <form method="post" enctype="multipart/form-data" action="options.php">
        <?php
            settings_fields('gwp_theme_options');

            do_settings_sections('gwp_theme_options.php');
        ?>
        <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
        </p>
    </form>
</div>