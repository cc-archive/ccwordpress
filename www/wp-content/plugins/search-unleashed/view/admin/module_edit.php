<form action="<?php echo $this->url () ?>/ajax.php?cmd=save_module" method="post" accept-charset="utf-8" id="form_<?php echo $module->id () ?>">
	<table>
		<?php $module->edit (); ?>
		<tr>
			<td></td>
			<td>
				<input type="submit" name="save" value="<?php _e ('Save', 'search-unleashed'); ?>"/>
				<input type="submit" name="cancel" value="<?php _e ('Cancel', 'search-unleashed'); ?>" onclick="return cancel_module ('<?php echo $module->id () ?>')"/>
				<input type="hidden" name="id" value="<?php echo $module->id () ?>"/>
			</td>
		</tr>
	</table>
</form>

<script type="text/javascript" charset="utf-8">
	jQuery('#form_<?php echo $module->id () ?>').ajaxForm ({ target: '#module_<?php echo $module->id () ?>', beforeSubmit: function (data,form,settings) { if (data[data.length - 2].name == 'cancel') return false; return true}})
</script>