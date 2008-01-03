<label>
	<?php if ($module->has_config ()) : ?>
	<div class="option">
		<a href="#config" onclick="return config('<?php echo $module->id () ?>')"><img src="<?php echo $this->url () ?>/images/edit.png" width="16" height="16" alt="Edit"/></a>
	</div>
	<?php endif; ?>
	<input onclick="toggle_module ('<?php echo $module->id () ?>');" type="checkbox" name="<?php echo $module->id () ?>" id="<?php echo $module->id () ?>" <?php if ($active) echo ' checked="checked"' ?>/>
	<?php echo $module->name (); ?>
</label>