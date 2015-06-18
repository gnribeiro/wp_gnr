<label>
<input type="checkbox" name="<?php echo $name ?>"  class="<?php echo $class ?>"  value="<?php echo $value ?>" id="<?php echo $id ?>" <?php checked($value, get_option($id), true); ?> />
<?php  if(isset($desc)) echo $desc?>
</label>
