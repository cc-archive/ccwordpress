<div class="wrap">
	<h2><?php _e ('Search Log', 'search-unleashed'); ?></h2>

	<?php $this->render_admin ('pager', array ('pager' => $pager)); ?>

	<?php if (count ($logs) > 0) : ?>
	<table class="log">
		<thead>
			<tr>
				<th><?php echo $pager->sortable ('phrase', __ ('Phrase', 'search-unleashed')) ?></th>
				<th><?php echo $pager->sortable ('searched_at', __ ('Time', 'search-unleashed')) ?></th>
				<th><?php echo $pager->sortable ('ip', __ ('IP', 'search-unleashed')) ?></th>
				<th><?php echo $pager->sortable ('referrer', __ ('Referrer', 'search-unleashed')) ?></th>
			</tr>
		</thead>

		<tbody>
		<?php foreach ($logs as $pos => $log): ?>
			<tr>
				<td><?php echo $log->phrase () ?></td>
				<td><?php echo date (get_option ('date_format', $log->searched_at)) ?></td>
				<td><?php echo $log->ip () ?></td>
				<td><?php echo $log->referrer () ?></td>
			</tr>
		<?php endforeach ?>
		</tbody>
	</table>
	
	<div class="pager">
	<?php foreach ($pager->area_pages () AS $page) : ?>
		<?php echo $page ?>
	<?php endforeach; ?>&nbsp;
	</div>
	
	<?php else : ?>
		<p><?php _e ('There is nothing to display', 'search-unleashed'); ?></p>
	<?php endif; ?>
</div>

<div class="wrap">
	<h2><?php _e ('Delete Logs', 'search-unleashed'); ?></h2>
	
	<p><?php _e ('This option will delete all search logs.  Please be sure this is what you want to do.', 'search-unleashed'); ?></p>
	
	<form action="<?php echo $this->url ($_SERVER["REQUEST_URI"]) ?>" method="post" accept-charset="utf-8">
		<input type="submit" name="delete" value="<?php _e ('Delete logs', 'search-unleashed') ?>"/>
	</form>
</div>