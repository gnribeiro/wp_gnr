<div class="section panel">
    <h1> Theme Options</h1>
    <br>
    <form method="post" enctype="multipart/form-data" action="options.php">
        <?php
           settings_fields("section");
           do_settings_sections("theme-options");      
           submit_button(); 
        ?>
    </form>
</div>