<? /*
<div class="incoming" id="incoming">
	<div class="hide">
		<a href="#hide" onclick="document.getElementById ('incoming').parentNode.removeChild (document.getElementById ('incoming'));"><?php _e ('hide', 'search-unleashed'); ?></a>
	</div>
	<h3><?php echo $engine->get_engine_name () ?> <?php _e ('Search Results', 'search-unleashed'); ?></h3>
	
	<div>
		<p><?php echo __ngettext ('You arrived here after searching for the following phrase', 'You arrived here after searching for the following phrases', count ($words), 'search-unleashed'); ?>:</p>
		<ul>
			<?php foreach ($words AS $pos => $word) : ?>
				<li><a href="#high_<?php echo $pos + 1?>"><?php echo $word; ?></a></li>
			<?php endforeach; ?>
		</ul>

		<p><?php printf (__ ('Click a phrase to jump to the first occurrence, or <a href="%s">return to the search results</a>.', 'search-unleashed'), $_SERVER['HTTP_REFERER']) ?></p>
	</div>
</div>
*/ ?>
