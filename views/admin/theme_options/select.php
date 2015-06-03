<select name="<?php echo $name ?>" class="<?php echo $class ?>">
    <?php foreach($options as $value => $label): ?>
        <option value="<?php echo $value ?>" <?php selected( get_option($id), $value ); ?> id="<?php echo $id ?>" ><?php echo $label ?></option>
    <?php endforeach; ?>
</select>
<?php  if(isset($desc)):?>
    <br>
    <div class="desc"><?php echo $desc ?></div>
<?php  endif;?>
