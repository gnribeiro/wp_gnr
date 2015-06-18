<div class="section panel">
    <h1> Theme Options</h1>
    <br>
        <form method="post" action="options.php" enctype="multipart/form-data">
        <?php
           settings_fields("section");
           do_settings_sections("theme-options");      
           submit_button(); 
        ?>
        <input type="hidden" name="optionsgwpteme" value="1"/>
    </form>
</div>