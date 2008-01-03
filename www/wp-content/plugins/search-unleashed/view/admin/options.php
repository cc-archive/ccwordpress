<div class="wrap">
	<h2><?php _e ('Options', 'search-unleashed'); ?></h2>
	
	<form action="<?php echo $this->url ($_SERVER['REQUEST_URI']) ?>" method="post" accept-charset="utf-8">
		
		<fieldset class="options">
			<legend><?php _e ('General Options', 'search-unleashed')?></legend>
			<table>
				<tr>
					<th><?php _e ('Pages to exclude', 'search-unleashed'); ?>:</th>
					<td><input size="40" type="text" name="exclude" value="<?php echo $options['exclude'] ?>"/>
						<span class="sub"><?php _e ('Comma-separated list of page/post IDs', 'search-unleashed'); ?></span></td>
				</tr>
				<tr>
					<th><label for="page_title"><?php _e ('Search results page title', 'search-unleashed'); ?>:</label></th>
					<td><input type="checkbox" name="page_title" id="page_title"<?php if ($options['page_title']) echo ' checked="checked"' ?>/>
						<span class="sub"><?php _e ('Change page title on search results to reflect the search condition', 'search-unleashed'); ?></span>
						</td>
				</tr>
				<tr>
					<th><?php _e ('Include Posts', 'search-unleashed'); ?>:</th>
					<td>
						<label><input type="checkbox" name="protected" id="protected"<?php if ($options['protected']) echo ' checked="checked"' ?>/> password-protected</label>
						<label><input type="checkbox" name="private" id="private"<?php if ($options['private']) echo ' checked="checked"' ?>/> private</label>
						<label><input type="checkbox" name="draft" id="draft"<?php if ($options['draft']) echo ' checked="checked"' ?>/> draft</label>
					</td>
				</tr>
				<tr>
					<th><label for="search_mode"><?php _e ('Search Mode', 'search-unleashed'); ?>:</label></th>
					<td>
						<select name="search_mode">
							<?php $this->select (array ('fulltext' => __ ('Full text with logical operations', 'search-unleashed'), 'like' => __ ('Simple text matching', 'search-unleased')), $options['search_mode']); ?>
						</select>
					</td>
				</tr>
				<tr>
					<th><label for="kitten"><?php _e ('Kitten protection', 'search-unleashed'); ?>:</label></th>
					<td>
						<input type="checkbox" name="kitten" id="kitten"<?php if ($options['kitten']) echo ' checked="checked"' ?>/>
						<span class="sub"><?php _e ('Click this if you have supported the author and helped stop the kittens from crying', 'search-unleashed'); ?></span>
					</td>
				</tr>
			</table>
		</fieldset>
		
		<fieldset class="options">
			<legend><?php _e ('Theme Options', 'search-unleashed')?></legend>
			<table>
				<tr>
					<th><?php _e ('Force content display', 'search-unleashed'); ?>:</th>
					<td>
						<input type="checkbox" name="force_display"<?php echo $this->checked ($options['force_display']) ?>/>
						<span class="sub"><?php _e ('Some themes don\'t display any search result content.  Enable this option to force the theme to display results', 'search-unleashed'); ?></span>
					</td>
				</tr>
				<tr>
					<th><label for="incoming"><?php _e ('Highlight incoming searches', 'search-unleashed'); ?>:</label></th>
					<td>
						<select name="incoming">
							<?php echo $this->select (array ('none' => 'No highlighting', 'content' => 'Content, no titles', 'all' => 'Content &amp; titles'), $options['incoming']); ?>
						</select>
						<span class="sub"><?php _e ('Show incoming search requests by highlighting phrases and displaying a help box', 'search-unleashed'); ?></span>
						</td>
				</tr>
				<tr>
					<th valign="top"><label for="theme_title_position"><?php _e ('Theme title position', 'search-unleashed'); ?>:</label></th>
					<td>
						<select name="theme_title_position">
							<option value="0"<?php if ($options['theme_title_position'] == 0) echo ' selected="selected"' ?>>0</option>
							<option value="1"<?php if ($options['theme_title_position'] == 1) echo ' selected="selected"' ?>>1 <?php _e ('(includes default theme)', 'search-unleashed'); ?></option>
							<option value="2"<?php if ($options['theme_title_position'] == 2) echo ' selected="selected"' ?>>2</option>
						</select>
						<br/>
						<span class="sub"><?php _e ('Most themes require a position of 1, but if you have incorrect highlighting in titles set this to another value', 'search-unleashed'); ?></span>
						</td>
				</tr>

			</table>
		</fieldset>
		
		<fieldset class="options">
			<legend><?php _e ('Search Style', 'search-unleashed'); ?></legend>
			<table>
				<tr>
					<th><label for="include_css"><?php _e ('Include highlight CSS', 'search-unleashed'); ?>:</label></th>
					<td><input type="checkbox" name="include_css" id="include_css"<?php if ($options['include_css']) echo ' checked="checked"' ?>/></td>
				</tr>
				<?php for ($x = 0; $x < 5; $x++) : ?>
				<tr>
					<th><?php _e ('Highlight colour', 'search-unleashed'); ?> #<?php echo $x + 1 ?>:</th>
					<td>
						<input class="colorinput" size="6" type="text" name="highlight[<?php echo $x ?>]" value="<?php echo $options['highlight'][$x] ?>"/>
						<span style="background-color: #<?php echo $options['highlight'][$x] ?>" class="colour" id="colour_<?php echo $x ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
					
						<span class="sub"> <?php _e ('Specify colour as a hex value', 'search-unleashed'); ?></span>
					</td>
				</tr>
				<?php endfor; ?>
			</table>
		</fieldset>

		<input type="submit" name="save" value="<?php _e ('Save', 'search-unleashed'); ?>"/>
	</form>
</div>

