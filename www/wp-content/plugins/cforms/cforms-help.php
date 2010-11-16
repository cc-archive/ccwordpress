<?php
/*
please see cforms.php for more information
*/

load_plugin_textdomain('cforms');

$plugindir   = dirname(plugin_basename(__FILE__));
$cforms_root = get_settings('siteurl') . '/wp-content/plugins/'.$plugindir;


### Check Whether User Can Manage Database
if(!current_user_can('manage_cforms')) {
	die(__('Access Denied','cforms'));
}

?>

<div class="wrap" id="top">
<img src="<?php echo $cforms_root; ?>/images/cfii.gif" alt="" align="right"/><img src="<?php echo $cforms_root; ?>/images/p4-title.jpg" alt=""/>

		<p><?php _e('Here you\'ll find plenty of examples and documentation that should help you configure <strong>cforms II</strong>.', 'cforms'); ?></p>

		<p class="cftoctitle"><?php _e('Table of Contents', 'cforms'); ?></p>
		<ul class="cftoc">
			<li><a href="#guide" onclick="setshow(17)"><?php _e('Basic steps, a small guide', 'cforms'); ?></a> &raquo;</li>
			<li><a href="#inserting" onclick="setshow(18)"><?php _e('Inserting a form', 'cforms'); ?></a> &raquo;</li>
			<li><a href="#fields" onclick="setshow(19)"><?php _e('Supported form input fields', 'cforms'); ?></a> &raquo;
			<ul style="margin-top:7px	">
				<li><a href="#qa" onclick="setshow(19)"><?php _e('SPAM protection: Q &amp; A', 'cforms'); ?></a> &raquo;</li>
				<li><a href="#captcha" onclick="setshow(19)"><?php _e('SPAM protection: Captcha', 'cforms'); ?></a> &raquo;</li>
				<li><a href="#hfieldsets" onclick="setshow(19)"><?php _e('Fieldsets', 'cforms'); ?></a> &raquo;</li>
				<li><a href="#regexp" onclick="setshow(19)"><?php _e('Using regular expressions with form fields', 'cforms'); ?></a> &raquo;</li>
			</ul></li>
			<li><a href="#customerr" onclick="setshow(20)"><?php _e('Custom error messages', 'cforms'); ?></a> &raquo;</li>
			<li><a href="#hook" onclick="setshow(21)"><?php _e('Advanced: (Post-)Processing of submitted data', 'cforms'); ?></a> &raquo;</li>
			<li><a href="#dynamicforms" onclick="setshow(22)"><?php _e('Advanced: Real-time creation of dynamic forms', 'cforms'); ?></a> &raquo;</li>
			<li><a href="#variables" onclick="setshow(23)"><?php _e('Using variables in email subjects &amp; messages', 'cforms'); ?></a> &raquo;</li>
			<li><a href="#CSS" onclick="setshow(24)"><?php _e('Styling your forms', 'cforms'); ?></a> &raquo;</li>
			<li><a href="#troubles" onclick="setshow(25)"><?php _e('Need more help?', 'cforms'); ?></a> &raquo;</li>
		</ul>


	    <h3 id="guide" style="margin-top:20px;"><a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a><a id="b17" class="blindminus" onfocus="this.blur()" onclick="toggleui(17);return false;" href="#" title="<?php _e('Expand/Collapse', 'cforms') ?>"></a><span class="h3title-no">1.</span><?php _e('Basic steps, a small guide', 'cforms'); ?></h3>

		<div id="o17">
			<p><?php _e('Admittedly, <strong>cforms</strong> is not the easiest form mailer plugin but it may be the most flexible. The below outline should help you get started with the default form.', 'cforms'); ?></p>
			<ol style="margin:10px 0 0 100px;">
				<li><?php echo sprintf(__('First take a look at the <a href="%s">default form</a>', 'cforms'),'?page=' . $plugindir . '/cforms-options.php#anchorfields'); ?>
					<ul style="margin:10px 0 0 30px;">
						<li><?php _e('Verify that it contains all the fields you need, are they in the right order?', 'cforms'); ?></li>
						<li><?php _e('Check the field labels (field names).', 'cforms'); ?></li>
						<li><?php _e('Check "Required", "Email" (<em>if an email address is expected for input</em>) and/or "Auto Clear" (<em>if the field default value needs to be cleared upon focus</em>).', 'cforms'); ?></li>
						<li><?php echo sprintf(__('Want to include SPAM protection? Choose between <a href="%s" %s>Q&amp;A</a>, <a href="%s" %s>captcha</a> add an input field accordingly and configure <a href="%s" %s>here</a>.', 'cforms'),'#qa','onclick="setshow(19)"','#captcha','onclick="setshow(19)"','?page=' . $plugindir . '/cforms-global-settings.php#visitorv','onclick="setshow(13)"'); ?></li>
					</ul>
				</li>
				<li><?php echo sprintf(__('Check if the <a href="%s" %s>email admin</a> for your form is configured correctly.', 'cforms'),'?page=' . $plugindir . '/cforms-options.php#anchoremail','onclick="setshow(2)"'); ?></li>
				<li><?php echo sprintf(__('Decide if you want the visitor to receive an <a href="%s" %s>auto confirmation message</a> upon form submission.', 'cforms'),'?page=' . $plugindir . '/cforms-options.php#autoconf','onclick="setshow(5)"'); ?></li>
				<li><?php echo sprintf(__('Would you like <a href="%s" %s>to track</a> form submission via the database?', 'cforms'),'?page=' . $plugindir . '/cforms-global-settings.php#tracking','onclick="setshow(14)"'); ?></li>
				<li><?php echo sprintf(__('<a href="%s" %s>Add the default form</a> to a post or page.', 'cforms'),'#inserting','onclick="setshow(18)"'); ?></li>
				<li><?php _e('Give it a whirl.', 'cforms'); ?></li>
			</ol>
		</div>


	    <h3 id="inserting"><a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a><a id="b18" class="blindminus" onfocus="this.blur()" onclick="toggleui(18);return false;" href="#" title="<?php _e('Expand/Collapse', 'cforms') ?>"></a><span class="h3title-no">2.</span><?php _e('Inserting a form', 'cforms'); ?></h3>

		<div id="o18">
			<p><strong><?php _e('In posts and pages:', 'cforms'); ?></strong></p>

			<p><?php echo sprintf(__('If you like to do it the old-fashioned way using tags, make sure to use %1s for the first form and/or %2s for your other forms to include them in your <em>Pages/Posts</em>.', 'cforms'),'<code>&lt;!--cforms--&gt;</code>','<code>&lt;!--cforms<span style="color:red; font-weight:bold;">X</span>--&gt;</code>'); ?></p>
			<p><?php echo sprintf(__('However, using the TinyMCE Button is much more elegant and safer (double check if <a href="%3s" %s>Button Support</a> is enabled!).', 'cforms'),'?page=' . $plugindir . '/cforms-global-settings.php#wpeditor','onclick="setshow(12)"'); ?></p>

			<p align="center"><img src="<?php echo $cforms_root; ?>/images/example-tiny.png"  alt=""/></p>

			<p><strong><?php _e('Via PHP function call:', 'cforms'); ?></strong></p>
			<p><?php echo sprintf(__('Alternatively, you can specifically insert a form (into the sidebar for instance etc.) per the PHP function call %1s for the default/first form and/or %2s for any other form.', 'cforms'),'<code>insert_cform();</code>','<code>insert_cform(\'<span style="color:red; font-weight:bold;">X</span>\');</code>'); ?></p>
			<p><strong><?php _e('Note:', 'cforms'); ?></strong> <?php echo sprintf(__('"%1s" represents the number of the form, starting with %2s ..and so forth.', 'cforms'),'<span style="color:red; font-weight:bold;">X</span>','<span style="color:red; font-weight:bold;">2</span>, 3,4'); ?></p>
		</div>
	
	
	    <h3 id="fields"><a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a><a id="b19" class="blindminus" onfocus="this.blur()" onclick="toggleui(19);return false;" href="#" title="<?php _e('Expand/Collapse', 'cforms') ?>"></a><span class="h3title-no">3.</span><?php _e('Configuring form input fields', 'cforms'); ?></h3>

		<div id="o19">
			<p><?php _e('All supported input fields are listed below, highlighting the expected <em><u>formats</u></em> for their associated <em>Field Names</em>. Form labels (<em>Field Names</em>) permit the use of <strong>HTML</strong>, see examples below.', 'cforms'); ?></p>
			<p class="ex"><strong><?php _e('Note:', 'cforms'); ?></strong><?php _e('While the <em>Field Names</em> are usually just the label of a field (e.g. "Your Name"), they can contain additional information to support special functionality (e.g. default values, regular expressions for extended field validation etc.)', 'cforms'); ?></p>
			
			<p align="center" style="float:right; margin:20px 50px; width:400px; "><img src="<?php echo $cforms_root; ?>/images/example-wizard.png"  alt=""/><br /><?php _e('A new <em>wizard like</em> mode allows you to configure more complex settings in case all the pipes "|" and pounds "#" are overwhelming.', 'cforms'); ?></p>
			<ul style="margin:10px 0 0 100px; list-style:square;">
				<li><a href="#textonly" onclick="setshow(19)"><?php 	_e('Text only elements', 'cforms'); ?></a></li>
				<li><a href="#datepicker" onclick="setshow(19)"><?php _e('Javascript Date Picker input field', 'cforms'); ?></a></li>
				<li><a href="#single" onclick="setshow(19)"><?php 	_e('Single-, Password &amp; Multi-line fields', 'cforms'); ?></a></li>
				<li><a href="#select" onclick="setshow(19)"><?php 	_e('Select / drop down box &amp; radio buttons', 'cforms'); ?></a></li>
				<li><a href="#multiselect" onclick="setshow(19)"><?php _e('Multi-select box', 'cforms'); ?></a></li>
				<li><a href="#check" onclick="setshow(19)"><?php 		_e('Check boxes', 'cforms'); ?></a></li>
				<li><a href="#checkboxgroup" onclick="setshow(19)"><?php _e('Check box groups', 'cforms'); ?></a></li>
				<li><a href="#ccme" onclick="setshow(19)"><?php 		_e('CC:me check box', 'cforms'); ?></a></li>
				<li><a href="#multirecipients" onclick="setshow(19)"><?php _e('Multiple recipients drop down box', 'cforms'); ?></a></li>
				<li><a href="#hidden" onclick="setshow(19)"><?php 	_e('Hidden fields', 'cforms'); ?></a></li>
				<li><a href="#qa" onclick="setshow(19)"><?php 		_e('SPAM protection: Q&amp;A input field', 'cforms'); ?></a></li>
				<li><a href="#captcha" onclick="setshow(19)"><?php 	_e('SPAM protection: Captcha input field', 'cforms'); ?></a></li>
				<li><a href="#upload" onclick="setshow(19)"><?php 	_e('File attachments / upload', 'cforms'); ?></a></li>
				<li><a href="#taf" onclick="setshow(19)"><?php 		_e('Special <em>Tell A Friend</em> input fields', 'cforms'); ?></a></li>
				<li><a href="#commentrep" onclick="setshow(19)"><?php _e('Special <em>WP Comment Feature</em> input fields', 'cforms'); ?></a></li>
			</ul>


		<br style="clear:both;"/>


		<h4 id="textonly">
			<span class="h4ff"><?php _e('form<br />field', 'cforms'); ?></span>
			<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a>
			<?php _e('Text only elements (no input)', 'cforms'); ?>
		</h4>
		<img class="helpimg" src="<?php echo $cforms_root; ?>/images/example-text.png"  alt=""/>
		<table class="hf" cellspacing="2" border="4">
			<tr>
				<td class="bleft"><span class="abbr" title="<?php _e('Entry format for Field Name', 'cforms'); ?>"><?php _e('Format:', 'cforms'); ?></span></td>
				<td class="bright"><?php echo sprintf(__('text paragraph %1$s css class %1$s optional style', 'cforms'),'<span style="color:red; font-weight:bold;">|</span>'); ?></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright"><code><?php _e('Please make sure...', 'cforms'); ?>|mytextclass|font-size:9x; font-weight:bold;</code></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright"><code><?php echo sprintf(__('Check %s here %s for more info. %s', 'cforms'),'&lt;a href="http://mysite.com"&gt;','&lt;/a&gt;','||font-size:9x;'); ?></code></td>
			</tr>
			<tr>
				<td class="ball" colspan="2"><?php _e('HTML: the <code>text paragraph</code> supports HTML. If you need actual &lt;, &gt; in your text please use the proper HTML entity.', 'cforms'); ?></td>
			</tr>
			<tr>
				<td class="ball" colspan="2"><?php _e('The above expression applies the custom class "<code>mytextclass</code>" <strong>AND</strong> the specific styles "<code>font-size:9x; font-weight:bold;</code>" to the paragraph.', 'cforms'); ?></td>
			</tr>
			<tr>
				<td class="ball" colspan="2"><?php echo sprintf(__('If you specific a <code>css class</code>, you also need to define it in your current form theme file, <a href="%s">here</a>.', 'cforms'),'?page=' . $plugindir . '/cforms-css.php'); ?></td>
			</tr>
		</table>

		<br style="clear:both;"/>

		<h4 id="datepicker">
			<span class="h4ff"><?php _e('form<br />field', 'cforms'); ?></span>
			<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a>
			<?php _e('Javascript Date Picker', 'cforms'); ?>
		</h4>
		<img class="helpimg" src="<?php echo $cforms_root; ?>/images/example-dp.png"  alt=""/>
		<table class="hf" cellspacing="2" border="4">
			<tr>
				<td class="bleft"><span class="abbr" title="<?php _e('Entry format for Field Name', 'cforms'); ?>"><?php _e('Format:', 'cforms'); ?></span></td>
				<td class="bright"><?php echo sprintf(__('field name %1$s default value %1$s <a href="%2$s">regular expression</a>', 'cforms'),'<span style="color:red; font-weight:bold;">|</span>','#regexp'); ?></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright">
					<code  style="font-size:9px;"><?php _e('Arrival Date', 'cforms'); ?>|mm/dd/yyyy|^[0-9][0-9]/[0-9][0-9]/[0-9][0-9][0-9][0-9]$</code></td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php echo sprintf(__('The example above will set a <em>default value</em> of "mm/dd/yyyy" so users know the expected format. The <strong>regexp</strong> at the end ensures that only this format is accepted. <strong>NOTE:</strong> You also need to <a href="%s" %s>configure the date picker options</a> to match the date format ("MM/dd/yyyy" !)', 'cforms'),'?page=' . $plugindir . '/cforms-global-settings.php#datepicker','onclick="setshow(9)"'); ?>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
				<table class="dateinfo" width="100%">
					<tr><td colspan="3"><strong><?php _e('Supported Date Formats (see "Plugin Settings" tab)', 'cforms');?><br /></strong></td></tr>
					<tr><th><?php _e('Field', 'cforms');?></th><th><?php _e('Full Form', 'cforms');?></th><th><?php _e('Short Form', 'cforms');?></th></tr>
					<tr><td><strong><?php _e('Year', 'cforms');?></strong></td><td><?php _e('yyyy (4 digits)', 'cforms');?></td><td><?php _e('yy (2 digits), y (2 or 4 digits)', 'cforms');?></td></tr>
					<tr><td><strong><?php _e('Month', 'cforms');?></strong></td><td><?php _e('MMM (name or abbr.)', 'cforms');?></td><td><?php _e('MM (2 digits), M (1 or 2 digits)', 'cforms');?></td></tr>
					<tr><td><strong><?php _e('Day of Month', 'cforms');?></strong></td><td><?php _e('dd (2 digits)', 'cforms');?></td><td><?php _e('d (1 or 2 digits)', 'cforms');?></td></tr>
					<tr><td><strong><?php _e('Day of Week', 'cforms');?></strong></td><td><?php _e('EE (name)', 'cforms');?></td><td><?php _e('E (abbr)', 'cforms');?></td></tr>
				</table>					
				</td>
			</tr>
		</table>

		<br style="clear:both;"/>

		<h4 id="single">
			<span class="h4ff"><?php _e('form<br />field', 'cforms'); ?></span>
			<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a>
			<?php _e('Single, Password &amp; Multi line input fields', 'cforms'); ?>
		</h4>
		<img class="helpimg" src="<?php echo $cforms_root; ?>/images/example-single.png"  alt=""/>
		<table class="hf" cellspacing="2" border="4">
			<tr>
				<td class="bleft"><span class="abbr" title="<?php _e('Entry format for Field Name', 'cforms'); ?>"><?php _e('Format:', 'cforms'); ?></span></td>
				<td class="bright"><?php echo sprintf(__('field name %1$s default value %1$s <a href="%2$s">regular expression</a>', 'cforms'),'<span style="color:red; font-weight:bold;">|</span>','#regexp'); ?></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright">
					<code><?php _e('Reference', 'cforms'); ?>#|xxx-xx-xxx|^[0-9A-Z-]+$</code></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright">
					<code><?php _e('Your &lt;u&gt;Full&lt;/u&gt; Name', 'cforms'); ?>||^[A-Za-z- \.]+$</code></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright">
					<code>&lt;acronym title="<?php echo sprintf(__('We need your email address for confirmation."%sYour EMail', 'cforms'),'&gt;'); ?>&lt;/acronym&gt;</code></td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('You can of course omit the <em>default value</em>, the syntax would as in Example 2.', 'cforms'); ?>
				</td>
			</tr>
		</table>
		
		
		<br style="clear:both;"/>


		<h4 id="select">
			<span class="h4ff"><?php _e('form<br />field', 'cforms'); ?></span>
			<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a>
			<?php _e('Select boxes &amp; radio buttons', 'cforms'); ?>
		</h4>
		<img class="helpimg" src="<?php echo $cforms_root; ?>/images/example-dropdown.png"  alt=""/>
		<table class="hf" cellspacing="2" border="4">
			<tr>
				<td class="bleft"><span class="abbr" title="<?php _e('Entry format for Field Name', 'cforms'); ?>"><?php _e('Format:', 'cforms'); ?></span></td>
				<td class="bright"><?php echo sprintf(__('field name %1$s option1 %2$s value1 %1$s option2 %2$s value2 %1$s option3...', 'cforms'),'<span style="color:red; font-weight:bold;">#</span>','<span style="color:red; font-weight:bold;">|</span>'); ?></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright">
					<code><?php _e('Your age#12-18|kiddo#19 to 30|young#31 to 45#45+ |older', 'cforms'); ?></code></td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('Omitting the <code>field name</code> will result in not showing a label to the left of the field.', 'cforms'); ?>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('The <strong>option</strong> placeholder determines the text displayed to the visitor, <strong>value</strong> what is being sent in the email.', 'cforms'); ?>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('Is no <strong>value</strong> explicitly given, then the shown option text is the value sent in the email.', 'cforms'); ?>
				</td>
			</tr>			
			<tr>
				<td class="ball" colspan="2">
					<?php echo sprintf(__('<strong>Select box marked "Required":</strong> Using a minus symbol %1$s as the value (after %2$s), will mark an option as "not valid"! Example:<br /><code>Your age#Please pick your age group|-#12 to 18|kiddo#19 to 30|young#31 to 45#45+ |older</code>. <br />"Please pick..." is shown but not considered a valid value.', 'cforms'),'<code>-</code>','<span style="color:red; font-weight:bold;">|</span>'); ?>
				</td>
			</tr>			
		</table>


		<br style="clear:both;"/>

		<h4 id="multiselect">
			<span class="h4ff"><?php _e('form<br />field', 'cforms'); ?></span>
			<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a>
			<?php _e('Multi select boxes', 'cforms'); ?>
		</h4>
		<img class="helpimg" src="<?php echo $cforms_root; ?>/images/example-ms.png"  alt=""/>
		<table class="hf" cellspacing="2" border="4">
			<tr>
				<td class="bleft"><span class="abbr" title="<?php _e('Entry format for Field Name', 'cforms'); ?>"><?php _e('Format:', 'cforms'); ?></span></td>
				<td class="bright"><?php echo sprintf(__('field name %1$s option1 %2$s value1 %1$s option2 %2$s value2 %1$s option3...', 'cforms'),'<span style="color:red; font-weight:bold;">#</span>','<span style="color:red; font-weight:bold;">|</span>'); ?></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright">
					<code><?php _e('Pick#red#blue#green#yellow#orange', 'cforms'); ?></code></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright">
					<code><?php _e('&lt;strong&gt;Select&lt;/strong&gt;#Today#Tomorrow#This Week#Next Month#Never', 'cforms'); ?></code></td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('Multi select fields can be set to <strong>Required</strong>. If so and unless at least one option is selected the form won\'t validate.', 'cforms'); ?>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('If <code>value1,2,..</code> are not specified, the values delivered in the email default to <code>option1,2,...</code>.', 'cforms'); ?>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('Examples for specific values could be the matching color codes: e.g. <code>red|#ff0000</code>', 'cforms'); ?>
				</td>
			</tr>			
		</table>


		<br style="clear:both;"/>


		<h4 id="check">
			<span class="h4ff"><?php _e('form<br />field', 'cforms'); ?></span>
			<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a>
			<?php _e('Check boxes', 'cforms'); ?>
		</h4>
		<img class="helpimg" src="<?php echo $cforms_root; ?>/images/example-checkbox.png"  alt=""/>
		<table class="hf" cellspacing="2" border="4">
			<tr>
				<td class="bleft"><span class="abbr" title="<?php _e('Entry format for Field Name', 'cforms'); ?>"><?php _e('Format:', 'cforms'); ?></span></td>
				<td class="bright"><?php echo sprintf(__('field name <u>left</u> %s field name <u>right</u>', 'cforms'),'<span style="color:red; font-weight:bold;">#</span>'); ?></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright">
					<code><?php _e('#please check if you\'d like more information', 'cforms'); ?></code></td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('You can freely choose on which side of the check box the label appears (e.g. <code>#label-right-only</code>).', 'cforms'); ?>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('If <strong>both</strong> left and right labels are provided, only the <strong>right one</strong> will be considered.', 'cforms'); ?>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('Check boxes can be flagged "<strong>Required</strong>" to support special use cases, e.g.: when you require the visitor to confirm that he/she has read term &amp; conditions, before submitting the form.', 'cforms'); ?>
				</td>
			</tr>			
		</table>
		

		<br style="clear:both;"/>


		<h4 id="checkboxgroup">
			<span class="h4ff"><?php _e('form<br />field', 'cforms'); ?></span>
			<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a>
			<?php _e('Check box groups', 'cforms'); ?>
		</h4>
		<img class="helpimg" src="<?php echo $cforms_root; ?>/images/example-grp.png"  alt=""/>
		<table class="hf" cellspacing="2" border="4">
			<tr>
				<td class="bleft"><span class="abbr" title="<?php _e('Entry format for Field Name', 'cforms'); ?>"><?php _e('Format:', 'cforms'); ?></span></td>
				<td class="bright"><?php echo sprintf(__('field name %1$s chk box1 label%2$schk box1 value %1$s chk box2 label %3$s chk box3...', 'cforms'),'<span style="color:red; font-weight:bold;">#</span>','<span style="color:red; font-weight:bold;">|</span>','<span style="color:red; font-weight:bold;">##</span>'); ?></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright">
					<code><?php _e('Select Color#green|00ff00 #red|ff0000 #purple|8726ac #yellow|fff90f', 'cforms'); ?></code></td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('Two # (<code>##</code>) in a row will force a new line! This helps to better structure your check box group.', 'cforms'); ?>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('Similar to <strong>multi-select boxes</strong> (see above), <strong>Check box groups</strong> allow you to deploy several check boxes (with their labels and corresponding values) that form one logical field. The result submitted via the form email is a single line including all checked options.', 'cforms'); ?>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php echo sprintf(__('If no explicit <strong>value</strong> (text after the pipe symbol \'%1$s\') is specified, the provided check box label is both label &amp; submitted value.', 'cforms'),'<span style="color:red; font-weight:bold;">|</span>'); ?>
				</td>
			</tr>			
			<tr>
				<td class="ball" colspan="2">
					<?php _e('None of the check boxes within a group can be made "Required".', 'cforms'); ?>
				</td>
			</tr>			
		</table>
		

		<br style="clear:both;"/>


		<h4 id="ccme">
			<span class="h4ff"><?php _e('form<br />field', 'cforms'); ?></span>
			<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a>
			<?php _e('CC: option for visitors', 'cforms'); ?>
		</h4>
		<img class="helpimg" src="<?php echo $cforms_root; ?>/images/example-cc.png"  alt=""/>
		<table class="hf" cellspacing="2" border="4">
			<tr>
				<td class="bleft"><span class="abbr" title="<?php _e('Entry format for Field Name', 'cforms'); ?>"><?php _e('Format:', 'cforms'); ?></span></td>
				<td class="bright"><?php echo sprintf(__('field name <u>left</u> %s field name <u>right</u>', 'cforms'),'<span style="color:red; font-weight:bold;">#</span>'); ?></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright">
					<code><?php _e('#please cc: me', 'cforms'); ?></code></td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php echo sprintf(__('If the visitor chooses to be CC\'ed, <strong>no</strong> additional auto confirmation email (<a href="%s" %s>if configured</a>) is sent out!', 'cforms'),'?page=' . $plugindir . '/cforms-options.php#autoconf','onclick="setshow(5)"'); ?>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('Please also see <em>check boxes</em> above.', 'cforms'); ?>
				</td>
			</tr>
		</table>
		

		<br style="clear:both;"/>


		<h4 id="multirecipients">
			<span class="h4ff"><?php _e('form<br />field', 'cforms'); ?></span>
			<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a>
			<?php _e('Multiple form mail recipients', 'cforms'); ?>
			<span style="font-size:10px; color:#ffeaef; margin-left:15px"><strong><?php _e('Note:', 'cforms'); ?></strong> <?php echo sprintf(__('This requires corresponding email addresses <a href="%s" %s>here</a>!!', 'cforms'),'?page='.$plugindir.'/cforms-options.php#anchoremail','onclick="setshow(2)"'); ?></span>
		</h4>
		
		
		<img class="helpimg" src="<?php echo $cforms_root; ?>/images/example-multi.png"  alt=""/>
		<table class="hf" cellspacing="2" border="4">
			<tr>
				<td class="bleft"><span class="abbr" title="<?php _e('Entry format for Field Name', 'cforms'); ?>"><?php _e('Format:', 'cforms'); ?></span></td>
				<td class="bright"><?php echo sprintf(__('field name %1$s Name1 %1$s Name2 %1$s Name3...', 'cforms'),'<span style="color:red; font-weight:bold;">#</span>'); ?></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright">
					<code><?php _e('Send to#Joe#Pete#Hillary', 'cforms'); ?></code></td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php echo sprintf(__('The order of the names (1,2,3...) provided in the input field <strong>directly</strong> corresponds with the order of email addresses configured <a href="%s" %s>here</a>.', 'cforms'),'?page=' . $plugindir . '/cforms-options.php#anchoremail','onclick="setshow(2)"'); ?>
				</td>
			</tr>
		</table>


		<br style="clear:both;"/>

		<h4 id="hidden">
			<span class="h4ff"><?php _e('form<br />field', 'cforms'); ?></span>
			<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a>
			<?php _e('Hidden input fields', 'cforms'); ?>
		</h4>
		<table class="hf" cellspacing="2" border="4">
			<tr>
				<td class="bleft"><span class="abbr" title="<?php _e('Entry format for Field Name', 'cforms'); ?>"><?php _e('Format:', 'cforms'); ?></span></td>
				<td class="bright"><?php echo sprintf(__('field name %1$s default value', 'cforms'),'<span style="color:red; font-weight:bold;">|</span>'); ?></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright">
					<code><?php _e('extra-data', 'cforms'); ?>|fixed,hidden text</code></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright">
					<code><?php _e('post-data-meta', 'cforms'); ?>|{custom_field_1}</code></td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('Hidden fields can contain fixed/preset values or <strong>{variables}</strong> which reference custom fields of posts or pages.', 'cforms'); ?>
				</td>
			</tr>
		</table>


		<br style="clear:both;"/>


		<h4 id="qa">
			<span class="h4ff"><?php _e('form<br />field', 'cforms'); ?></span>
			<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a>
			<?php _e('Visitor verification (Q&amp;A)', 'cforms'); ?>
		</h4>
		<img class="helpimg" src="<?php echo $cforms_root; ?>/images/example-vv.png"  alt=""/>
		<table class="hf" cellspacing="2" border="4">
			<tr>
				<td class="bleft"><span class="abbr" title="<?php _e('Entry format for Field Name', 'cforms'); ?>"><?php _e('Format:', 'cforms'); ?></span></td>
				<td class="bright"><?php _e('--', 'cforms'); ?></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright">
					<code><?php _e('--', 'cforms'); ?></code></td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php echo sprintf(__('No <code>field name</code> required, the field has no configurable label per se, as it is determined at run-time from the list of <strong>Question &amp; Answers</strong> provided <a href="%s" %s>here</a>.', 'cforms'),'?page=' . $plugindir . '/cforms-global-settings.php#visitorv','onclick="setshow(13)"'); ?>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('It makes sense to encapsulate this field inside a FIELDSET, to do that simply add a <code>New Fieldset</code> field before this one.', 'cforms'); ?>
				</td>
			</tr>		
		</table>


		<br style="clear:both;"/>


		<h4 id="captcha">
			<span class="h4ff"><?php _e('form<br />field', 'cforms'); ?></span>
			<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a>
			<?php _e('Captcha', 'cforms'); ?>
		</h4>
		<img class="helpimg" src="<?php echo $cforms_root; ?>/images/example-cap.png"  alt=""/>
		<table class="hf" cellspacing="2" border="4">
			<tr>
				<td class="bleft"><span class="abbr" title="<?php _e('Entry format for Field Name', 'cforms'); ?>"><?php _e('Format:', 'cforms'); ?></span></td>
				<td class="bright"><?php _e('field name', 'cforms'); ?></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright">
					<code><?php _e('Enter code', 'cforms'); ?></code></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright">
					<code><?php _e('Spam Protection|err:Please enter the CAPTCHA code correctly! If text is unreadable, try reloading.', 'cforms'); ?></code></td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('Alternatively or in addition to the above <strong>Visitor verification</strong> feature, you can have the visitor provide a captcha response.', 'cforms'); ?>
				</td>
			</tr>
		</table>


		<br style="clear:both;"/>


		<h4 id="upload">
			<span class="h4ff"><?php _e('form<br />field', 'cforms'); ?></span>
			<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a>
			<?php _e('Attachments / File Upload Box', 'cforms'); ?>
		</h4>
		<img class="helpimg" src="<?php echo $cforms_root; ?>/images/example-upload.png"  alt=""/>
		<table class="hf" cellspacing="2" border="4">
			<tr>
				<td class="bleft"><span class="abbr" title="<?php _e('Entry format for Field Name', 'cforms'); ?>"><?php _e('Format:', 'cforms'); ?></span></td>
				<td class="bright"><?php _e('form label', 'cforms'); ?></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright">
					<code><?php _e('Please select a file', 'cforms'); ?></code></td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php echo sprintf(__('Please double-check the <a href="%s" %s>general settings</a> for proper configuration of the <code>File Upload</code> functionality (allowed extensions, file size etc.).', 'cforms'),'?page='.$plugindir.'/cforms-global-settings.php#upload','onclick="setshow(11)"'); ?>
				</td>
			</tr>
		</table>


		<br style="clear:both;"/>
		
		
		<h4 id="taf">
			<span class="h4ff"><?php _e('form<br />field', 'cforms'); ?></span>
			<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a>
			<?php _e('Tell a Friend input fields', 'cforms'); ?>
		</h4>
		<img class="helpimg" src="<?php echo $cforms_root; ?>/images/example-t-a-f.png"  alt=""/>
		<table class="hf" cellspacing="2" border="4">
			<tr>
				<td class="bleft"><span class="abbr" title="<?php _e('Entry format for Field Name', 'cforms'); ?>"><?php _e('Format:<br />of all 4 fields', 'cforms'); ?></span></td>
				<td class="bright"><?php echo sprintf(__('field name %1$s default value %1$s <a href="#regexp">regular expression</a>', 'cforms'),'<span style="color:red; font-weight:bold;">|</span>'); ?></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Fields:', 'cforms'); ?></td><td class="bright">
					<code><strong><?php _e('T-A-F * Your Name', 'cforms'); ?></strong></code><br />
					<code><strong><?php _e('T-A-F * Your Email <em>(make sure it\'s checked \'Email\')</em>', 'cforms'); ?></strong></code><br />
					<code><strong><?php _e('T-A-F * Friend\'s Name', 'cforms'); ?></strong></code><br />
					<code><strong><?php _e('T-A-F * Friend\'s Email <em>(make sure it\'s checked \'Email\')</em>', 'cforms'); ?></strong></code>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('<strong>To get it working:</strong>', 'cforms'); ?>
					<ol>
						<li><?php echo sprintf(__('The <a href="%s" %s>Tell A Friend feature</a> needs to be <strong>enabled for the respective form</strong> (<em>check if it\'s the right one!</em>), otherwise you won\'t see the above input fields in the [<em>Field Type</em>] select box.', 'cforms'),'?page='.$plugindir.'/cforms-options.php#tellafriend','onclick="setshow(6)"'); ?></li>
						<li><?php echo sprintf(__('The <a href="%s" %s>auto confirmation</a> message will be used as a <strong>message template</strong> and needs to be defined. See example below.', 'cforms'),'?page='.$plugindir.'/cforms-options.php#cforms_cmsg','onclick="setshow(5)"'); ?></li>
						<li><?php echo sprintf(__('There a <a href="%s" %s>three additional</a>, <em>predefined variables</em> that can be used in the <a href="%s" %s>message template</a>.', 'cforms'),'#tafvariables','onclick="setshow(23)"','?page='.$plugindir.'/cforms-options.php#cforms_cmsg','onclick="setshow(5)"'); ?></li>
						<li><?php echo _e('<strong>Add the form</strong> to your post/page php templates (see deployment options further below).', 'cforms'); ?></li>
						<li><img style="float:right;" src="<?php echo $cforms_root; ?>/images/example-t-a-f2.png"  alt=""/><?php echo _e('Tell-A-Friend <strong>enable your posts/pages</strong> by checking the T-A-F field in the WP post (page) editor.', 'cforms'); ?></li>
					</ol>
					
				</td>
			</tr>
		</table>
		<br />
		<table class="hf" cellspacing="2" border="4">			
			<tr>
				<td class="ball" colspan="2">
					<?php echo sprintf(__('Here is an example of how to setup the TXT part of the <a href="%s" %s>auto confirmation message</a> as a Tell-A-friend template:', 'cforms'),'?page='.$plugindir.'/cforms-options.php#cforms_cmsg','onclick="setshow(5)"'); ?>
	
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<code>
					<?php _e('Hello {Friend\'s Name}','cforms'); ?>,<br />
					<?php  _e('{Your Name} left you this message:','cforms'); ?><br />		
					<?php  _e('{Optional Comment}','cforms'); ?><br />
					<?php  _e('The message was sent in reference to','cforms'); ?> {Title}:<br />
					{Excerpt}<br />
					{Permalink}<br />
					--<br />
					<?php  _e('This email is sent, as a courtesy of website.com, located at http://website.com. The person who sent this email to you, {Your Name}, gave an email address of {Your Email}. {Your Name} logged into website.com from IP {IP}, and sent the email at {Time}.','cforms'); ?><br />
					</code>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<strong><?php _e('Note:', 'cforms'); ?></strong> <?php _e('In addition to the above TXT message you can, of course, add an HTML counterpart.', 'cforms'); ?>
				</td>
			</tr>
		</table>
		<br />
		<table class="hf" cellspacing="2" border="4">			
			<tr>
				<td class="ball" colspan="2">
					<strong><?php _e('Recommended Implementation Options:', 'cforms'); ?></strong>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('<em>Alternative 1:</em> The actual form will not show on the WP front page, but in the individual post/page view.', 'cforms'); ?>
					<ol>
						<li><?php echo sprintf(__('Simply add a <code>&lt;?php insert_cform(<em>#</em>); ?&gt;</code> (# = <a href="%s" %s>your form id</a>) to your existing <code>single.php</code> and/or <code>page.php</code> template, e.g:', 'cforms'),'#inserting','onclick="setshow(18)"');?>

<code  style="font-size: 11px;"><br />
[...]<br />
&lt;?php the_content('&lt;p&gt;Read the rest of this entry &raquo;&lt;/p&gt;'); ?&gt;<br />
<strong style="color:red;">&lt;?php if ( is_tellafriend( $post-&gt;ID ) ) insert_cform(#); ?&gt;</strong><br />
[...]
</code>
						</li>				
						<li><?php echo _e('Suggestion: For a less crowded layout, optionally add some Javascript code to show/hide the form.', 'cforms'); ?></li>
					</ol>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('<em>Alternative 2:</em> A Tell-A-Friend link is shown under every eligible post/page, displayed both on the blog\'s front page &amp; individual post &amp; page view.', 'cforms'); ?>

					<ol>
						<li><?php  _e('This requires a new WP page created (make note of the page ID or permalink), with its own page template (a clone of page.php will do). Add the following code to the new <strong>page template</strong>:', 'cforms'); ?>

<code  style="font-size: 11px;"><br />
[...]<br />
&lt;?php the_content('&lt;p&gt;Read the rest of this page &raquo;&lt;/p&gt;');?&gt;<br />
<strong style="color:red;">&lt;h3&gt; &lt;?php echo 'E-Mail "'.get_the_title( $_GET['pid'] ).'" to a friend:'; ?&gt; &lt;/h3&gt;<br />
&lt;?php if ( is_tellafriend( $_GET['pid'] ) ) insert_cform(#); ?&gt;</strong><br />
[...]
</code>
						</li>				
						<li><?php echo _e('In <em>single.php &amp; index.php</em> and/or <em>page.php</em> add beneath the "the_content()" call the link to the new page created above, e.g.:', 'cforms'); ?>

<code  style="font-size: 11px;"><br />
[...]<br />
&lt;?php the_content('&lt;p&gt;Read the rest of this entry &raquo;&lt;/p&gt;'); ?&gt;<br />
<strong style="color:red;">&lt;?php <br />
if ( is_tellafriend( $post-&gt;ID ) ) <br />
 &nbsp; &nbsp; echo '&lt;a href="[your-new-page]?&amp;pid='.$post-&gt;ID.'" title="Tell-A-Friend form"&gt;Tell a friend!&lt;/a&gt;'; <br />
?&gt;</strong><br />
[...]<br />
</code>
						</li>				
						<li><?php echo _e('Replace <strong>[your-new-page]</strong> with <strong>the permalink</strong> of your newly created page.', 'cforms'); ?></li>
					</ol>

				</td>
			</tr>
		</table>

		<br style="clear:both;"/>

		<h4 id="commentrep">
			<span class="h4ff"><?php _e('form<br />field', 'cforms'); ?></span>
			<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a>
			<?php _e('WP Comment Feature input fields', 'cforms'); ?>
		</h4>
		<div style="float:right" align="center">
			<img class="helpimg" style="float:none" src="<?php echo $cforms_root; ?>/images/example-crep1.png"   alt=""/><br /><br />
			<img class="helpimg" style="float:none" src="<?php echo $cforms_root; ?>/images/example-crep2sm.png" alt=""/><br />
			<?php _e('Example Configuration', 'cforms'); ?>
		</div>
		<table class="hf" cellspacing="2" border="4">
			<tr>
				<td class="bleft"><span class="abbr" title="<?php _e('Entry format for Field Name', 'cforms'); ?>"><?php _e('Format:<br />of all 4 fields', 'cforms'); ?></span></td>
				<td class="bright"><?php echo sprintf(__('field name  %1$s  default value  %1$s  <a href="#regexp">regular expression</a>', 'cforms'),'<span style="color:red; font-weight:bold;">|</span>'); ?></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Fields:', 'cforms'); ?></td><td class="bright">
					<code><strong><?php _e('Comment Author', 'cforms'); ?></strong></code><br />
					<code><strong><?php _e('Author\'s Email', 'cforms'); ?></strong></code><br />
					<code><strong><?php _e('Author\'s URL', 'cforms'); ?></strong></code><br />
					<code><strong><?php _e('Author\'s Comment', 'cforms'); ?></strong></code>
				</td>
			</tr>
			<tr><td class="bleft" colspan="2">&nbsp;</td></tr>
			<tr>
				<td class="bleft"><span class="abbr" title="<?php _e('Entry format for Field Name', 'cforms'); ?>"><?php _e('Format:', 'cforms'); ?></span></td>
				<td class="bright"><?php echo sprintf(__('field name %1$s text <em>comment</em> %2$s 0 %1$s text <em>to author</em> %2$s 1', 'cforms'),'<span style="color:red; font-weight:bold;">#</span>','<span style="color:red; font-weight:bold;">|</span>'); ?></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Field', 'cforms'); ?>:</td><td class="bright">
					<code><strong><?php _e('Select: Email/Comment', 'cforms'); ?></strong></code>
				</td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright">
					<code><?php _e('Send as#regular comment|0#email to post author|1', 'cforms'); ?></code></td>
			</tr>
			<tr><td class="bleft" colspan="2">&nbsp;</td></tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('<strong>To get it working:</strong>', 'cforms'); ?>
					<ol>
						<li><?php echo sprintf(__('Turn on the <a href="%s" %s>WP Comment feature</a> for the given form. (<em>Make sure it\'s the right one!</em>), otherwise you won\'t see the above input fields in the [<em>Field Type</em>] select box.', 'cforms'),'?page='.$plugindir.'/cforms-options.php#commentrep','onclick="setshow(7)"'); ?></li>
						<li><?php _e('Modify this form to include all the necessary (new) input fields, make them required or not, add regexp, anti SPAM fields or even custom err messages. All up to you.', 'cforms'); ?></li>
						<li><?php _e('Edit your WP Theme template for comments. Remove the current <strong><u>form tag</u></strong> and usually anything in-between (<code  style="color:red">&lt;form action=&quot;...&lt;/form&gt;</code>). Instead replace with a PHP call to cforms: <code  style="color:red">&lt;?php insert_cform(X); ?&gt;</code> with <strong>X</strong> being <u>omitted</u> if the form is your default form or starting at <strong>\'2\'</strong> (with single quotes!) for any subsequent form #.', 'cforms'); ?></li>
						<li><?php echo sprintf(__('Double check the extended <a href="%s" %s>WP comment feature settings here</a> (especially the Ajax specific ones!). ', 'cforms'),'?page='.$plugindir.'/cforms-global-settings.php#wpcomment','onclick="setshow(28)"'); ?></li>
						<li><?php _e('<strong>IMPORTANT</strong>: To make Ajax work in case there are no comments yet, make sure that the comment container <strong>is always</strong> being rendered.', 'cforms'); ?></li>
					</ol>					
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('<strong>Suggestions:</strong>', 'cforms'); ?>
					<ol>
						<li><?php echo sprintf(__('I recommend you choose the <strong>wide_form.css</strong> theme under the <a href="%s">Styling</a> menu. And adjust to your liking.', 'cforms'),'?page='.$plugindir.'/cforms-css.php'); ?></li>
						<li><?php _e('If you intend to make certain fields "required", I further recommend you add the text "<em>required</em>" to the input field label and set this style: <code  style="color:red">span.reqtxt, span.emailreqtxt {...</code> to <code  style="color:red">display:none;</code> (using the CSS editor on the <em>Styling</em> page)', 'cforms'); ?></li>
					</ol>					
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<strong><?php _e('Note:', 'cforms'); ?></strong> <?php _e('The beauty is, using one form, you can now offer your readers to either leave a comment behind or simply send a note to the post editor while being able to fully utilize all security aspects of cforms.', 'cforms'); ?>
				</td>
			</tr>
		</table>
												

		<br style="clear:both;"/>


		<h4 id="hfieldsets">
			<span class="h4ff"><?php _e('form<br />field', 'cforms'); ?></span>
			<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a>
			<?php _e('Fieldsets', 'cforms'); ?>
		</h4>

   		<p style="margin:10px 30px;"><?php _e('Fieldsets are definitely part of good form design, they are form elements that are used to create individual sections of content within a given form.', 'cforms'); ?></p>

		<img class="helpimg" src="<?php echo $cforms_root; ?>/images/example-fieldsets.png"  alt=""/>
		<table class="hf" cellspacing="2" border="4">
			<tr>
				<td class="bleft"><span class="abbr" title="<?php _e('Entry format for Field Name', 'cforms'); ?>"><?php _e('Format:', 'cforms'); ?></span></td>
				<td class="bright"><?php _e('fieldset name', 'cforms'); ?></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:', 'cforms'); ?></td><td class="bright">
					<code><?php _e('My Fieldset', 'cforms'); ?></code></td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('Fieldsets can begin anywhere, simply add a <strong>New Fieldset</strong> field between or before your form elements.', 'cforms'); ?>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('Fieldsets do not need to explicitly be closed, a <strong>New Fieldset</strong> element will automatically close the existing (if there is one to close) and reopen a new one.', 'cforms'); ?>
				</td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<?php _e('<strong>End Fieldset</strong> <u>can</u> be used, but it works without just as well.', 'cforms'); ?>
				</td>
			</tr>			
			<tr>
				<td class="ball" colspan="2">
					<?php _e('If there is no closing <strong>End Fieldset</strong> element, the plugin assumes that it needs to close the set just before the submit button', 'cforms'); ?>
				</td>
			</tr>			
		</table>


		<br style="clear:both; "/>


		<h4 id="regexp">
			<span class="h4ff"><?php _e('form<br />field', 'cforms'); ?></span>
			<a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a>
			<?php _e('Using regular expressions with form fields', 'cforms'); ?>
		</h4>
		
		<p style="margin:10px 30px;"><?php _e('A regular expression (regex or regexp for short) is a special text string for describing a search pattern, according to certain syntax rules. Many programming languages support regular expressions for string manipulation, you can use them here to validate user input. Single/Multi line input fields:', 'cforms'); ?></p>

		<!-- no img for regexps-->
		<table class="hf" cellspacing="2" border="4" width="95%">
			<tr>
				<td class="bleft"><span class="abbr" title="<?php _e('Entry format for Field Name', 'cforms'); ?>"><?php _e('Format:', 'cforms'); ?></span></td>
				<td class="bright"><?php echo sprintf(__('field name %1$s default value %1$s regular expression', 'cforms'),'<span style="color:red; font-weight:bold;">|</span>'); ?></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:<br />US zip code', 'cforms'); ?></td><td class="bright">
					<code><?php _e('zip code', 'cforms'); ?>||^\d{5}$)|(^\d{5}-\d{4}$</code></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Example:<br />US phone #', 'cforms'); ?></td><td class="bright">
					<code><?php _e('phone', 'cforms'); ?>||^[\(]?(\d{0,3})[\)]?[\s]?[\-]?(\d{3})[\s]?[\-]?(\d{4})[\s]?[x]?(\d*)$</code></td>
			</tr>
			<tr>
				<td class="bleft"><?php _e('Special Example:<br />comparing two input fields', 'cforms'); ?></td><td class="bright">
					<code><?php _e('please repeat email', 'cforms'); ?>||cf2_field2</code></td>
			</tr>
			<tr>
				<td class="ball" colspan="2">
					<strong><?php _e('GENERAL:', 'cforms'); ?></strong>
				</td>
			</tr>			
			<tr>
				<td class="ball" colspan="2">
					<?php _e('Ensure that the input field in question is tagged \'<strong>Required</strong>\'!', 'cforms'); ?>
				</td>
			</tr>			
			<tr>
				<td class="ball" colspan="2">
					<code>^</code> <?php _e('and', 'cforms'); ?> <code>$</code> <?php _e('define the start and the end of the input', 'cforms'); ?>
				</td>
			</tr>			
			<tr>
				<td class="ball" colspan="2">
					"<code>ab*</code>": <?php _e('...matches a string that has an "a" followed by zero or more "b\'s" ("a", "ab", "abbb", etc.);', 'cforms'); ?>
				</td>
			</tr>			
			<tr>
				<td class="ball" colspan="2">
					"<code>ab+</code>": <?php _e('...same, but there\'s at least one b ("ab", "abbb", etc.);', 'cforms'); ?>
				</td>
			</tr>			
			<tr>
				<td class="ball" colspan="2">
					"<code>[a-d]</code>": <?php _e('...a string that has lowercase letters "a" through "d"', 'cforms'); ?>
				</td>
			</tr>			
			<tr>
				<td class="ball" colspan="2">
					<?php echo sprintf(__('More information can be found <a href="%s">here</a>, a great regexp repository <a href="%s">here</a>.', 'cforms'),'http://weblogtoolscollection.com/regex/regex.php','http://regexlib.com'); ?>
				</td>
			</tr>			
			<tr>
				<td class="ball" colspan="2">
					<?php _e('<strong>IMPORTANT:</strong>', 'cforms'); ?><br />
					<?php _e('<strong>If you would like to compare two input fields (e.g. email verification):</strong> simply use the regexp field (see special example above, to point to the <u>HTML element ID</u> of the field you want to compare the current one to. To find the <u>HTML element ID</u> you would have to look into the html source code of the form (e.g.', 'cforms'); ?> <code>cf2_field2</code>).
				</td>
			</tr>			
		</table>
	</div>

		
	    <h3 id="customerr"><a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a><a id="b20" class="blindminus" onfocus="this.blur()" onclick="toggleui(20);return false;" href="#" title="<?php _e('Expand/Collapse', 'cforms') ?>"></a><span class="h3title-no">4.</span><?php _e('Custom error messages', 'cforms'); ?></h3>

		<div id="o20">
			<p>
				<?php echo sprintf(__('If you like to add custom error messages (next to your generic <a href="%s" %s>success</a> and <a href="%s" %s>error</a> messages) for your input fields, simply append a %s to a given <em>field name</em>. HTML is supported.', 'cforms'),'?page=' . $plugindir . '/cforms-options.php#cforms_success','onclick="setshow(1)"','?page=' . $plugindir . '/cforms-options.php#cforms_failure','onclick="setshow(1)"','<code>|err:XXX</code>'); ?> 			
			</p>
			
			<table class="hf" cellspacing="2" border="4" width="95%">
				<tr>
					<td class="bleft"><span class="abbr" title="<?php _e('Extended entry format for the Field Name', 'cforms'); ?>"><?php _e('Format:', 'cforms'); ?></span></td>
					<td class="bright"><?php echo sprintf(__('field name %s your error message %s', 'cforms'),'<span style="color:red; font-weight:bold;">|err:<em>','</em></span>'); ?></td>
				</tr>
				<tr>
					<td class="bleft"><?php _e('Example 1:', 'cforms'); ?></td><td class="bright">
						<code><?php _e('Your Name|err: please enter your full name.', 'cforms'); ?></code></td>
				</tr>
				<tr>
					<td class="bleft"><?php _e('Example 2:', 'cforms'); ?></td><td class="bright">
						<code><?php _e('Your age#12-18|kiddo#19 to 30|young#31 to 45#45+ |older', 'cforms'); ?><?php _e('|err: your age is &lt;strong&gt;important&lt;/strong&gt; to us.', 'cforms'); ?></code></td>
				</tr>
				<tr>
					<td class="ball" colspan="2">
						<strong><?php _e('Note:', 'cforms'); ?></strong>
					</td>
				</tr>			
				<tr>
					<td class="ball" colspan="2">
						<?php _e('<strong>Custom error messages</strong> can be applied to any input field that can be flagged "<strong>Required</strong>".', 'cforms'); ?>
					</td>
				</tr>			
			</table>
		</div>


	    <h3 id="hook"><a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a><a id="b21" class="blindminus" onfocus="this.blur()" onclick="toggleui(21);return false;" href="#" title="<?php _e('Expand/Collapse', 'cforms') ?>"></a><span class="h3title-no">5.</span><?php _e('Advanced: (Post-)Processing of submitted data', 'cforms'); ?></h3>

		<div id="o21">
			<p><?php _e('This is really for hard core deployments, where <em>real-time manipulation</em> of a form &amp; fields are required.', 'cforms'); ?></p>
	
			<p><?php _e('If you require the submitted data to be manipulated, and or sent to a 3rd party or would like to make use of the data otherwise, here is how:', 'cforms'); ?></p>
			<p class="ex"><?php _e('With v7.4 and forward, cforms comes with its own <strong>my-functions.php</strong> file (plugin root directory), including examples.', 'cforms');?></p>

			<table class="hf" cellspacing="2" border="4" width="95%">
				<tr>
					<td class="bright" colspan="2"><span class="abbr" title="<?php _e('Custom functions to (post-)process user input', 'cforms'); ?>"><?php _e('Available Functions', 'cforms'); ?></span></td>
				</tr>
				<tr>
					<td class="bleft"><strong><code class="codehighlight">my_cforms_filter()</code></strong></td>
					<td class="bright"><code><?php _e('function gets triggered <strong>after</strong> user input validation and cforms processing', 'cforms'); ?></code></td>
				</tr>
				<tr>
					<td class="bleft"><strong><code class="codehighlight">my_cforms_ajax_filter()</code></strong></td>
					<td class="bright"><code><?php _e('function gets called <strong>after</strong> input validation, but <strong>before	</strong> further processing', 'cforms'); ?> <?php _e('(nonAjax)', 'cforms'); ?></code></td>
				</tr>
				<tr>
					<td class="bleft"><strong><code class="codehighlight">my_cforms_action()</code></strong></td>
					<td class="bright"><code><?php _e('function gets called <strong>after</strong> input validation, but <strong>before</strong> further processing', 'cforms'); ?> <?php _e('(Ajax)', 'cforms'); ?></code></td>
				</tr>
			</table>
		</div>


	    <h3 id="dynamicforms"><a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a><a id="b22" class="blindminus" onfocus="this.blur()" onclick="toggleui(22);return false;" href="#" title="<?php _e('Expand/Collapse', 'cforms') ?>"></a><span class="h3title-no">6.</span><?php _e('Advanced: Real-time creation of dynamic forms', 'cforms'); ?></h3>

		<div id="o22">
			<p><?php _e('Again, this is for the advanced user who requires ad-hoc creation of forms.', 'cforms'); ?></p>
	
			<p><strong><?php _e('A few things to note on dynamic forms:', 'cforms'); ?></strong></p>
			<ol>
				<li><?php _e('Dynamic forms only work in <strong>non-Ajax</strong> mode.', 'cforms');?></li>
				<li><?php _e('Each dynamic form references and thus requires <strong>a base form defined</strong> in the cforms form settings. All its settings will be used, except the form (&amp;field) definition.', 'cforms');?></li>
				<li><?php _e('Any of the form fields described in the plugins\' <strong>HELP!</strong> section can be dynamically generated.', 'cforms');?></li>
				<li><?php echo sprintf(__('Function call to generate dynamic forms: %s with', 'cforms'),'<code>insert_custom_cform($fields:array,$form-no:int);</code> ');?>
	
	                <br /><br />
	                <code>$form-no</code>: <?php _e('empty string for the first (default) form and <strong>2</strong>,3,4... for any subsequent form', 'cforms'); ?><br />
	                <code>$fields</code> :
	
	                <pre style="font-size: 11px;"><code>
	            $fields['label'][n]      = '<?php _e('field name', 'cforms'); ?>';           <?php _e('<em>field name</em> format described above', 'cforms'); ?>
	
	            $fields['type'][n]       = 'input field type';     default: 'textfield';
	            $fields['isreq'][n]      = true|false;             default: false;
	            $fields['isemail'][n]    = true|false;             default: false;
	            $fields['isclear'][n]    = true|false;             default: false;
	            $fields['isdisabled'][n] = true|false;             default: false;
	            $fields['isreadonly'][n] = true|false;             default: false;
	
	            n = 0,1,2...</code></pre></li>
	    	</ol>
	
	
	        <strong><?php _e('Form input field types (\'type\'):', 'cforms'); ?></strong>
	        <ul style="list-style:none;">
	        <li>
	            <table class="cf_dyn_fields">
	                <tr><td><strong><?php _e('Basic fields', 'cforms'); ?></strong></td><td></td><td class="cf-wh">&nbsp;</td><td><strong><?php _e('Special T-A-F fields', 'cforms'); ?></strong></td><td></td></tr>
	                <tr><td><?php _e('Text paragraph', 'cforms'); ?>:</td><td> <code>textonly</code></td><td class="cf-wh">&nbsp;</td><td><?php _e('T-A-F * Your Name', 'cforms'); ?>:</td><td> <code>yourname</code></td></tr>
	                <tr><td><?php _e('Single input field', 'cforms'); ?>:</td><td> <code>textfield</code></td><td class="cf-wh">&nbsp;</td><td><?php _e('T-A-F * Your Email', 'cforms'); ?>:</td><td> <code>youremail</code></td></tr>
	                <tr><td><?php _e('Multi line field', 'cforms'); ?>:</td><td> <code>textarea</code></td><td class="cf-wh">&nbsp;</td><td><?php _e('T-A-F * Friend\'s Name', 'cforms'); ?>:</td><td> <code>friendsname</code></td></tr>
	                <tr><td><?php _e('Hidden field', 'cforms'); ?>:</td><td> <code>hidden</code></td><td class="cf-wh">&nbsp;</td><td><?php _e('T-A-F * Friend\'s Name', 'cforms'); ?>:</td><td> <code>friendsemail</code></td></tr>
	                <tr><td><?php _e('Password field', 'cforms'); ?>:</td><td> <code>pwfield</code></td></tr>
	                <tr><td><?php _e('Date picker field', 'cforms'); ?>:</td><td> <code>datepicker</code></td><td class="cf-wh">&nbsp;</td><td><strong><?php _e('WP Comment Feature', 'cforms'); ?></strong></td><td></td></tr>
	                <tr><td><?php _e('Check boxes', 'cforms'); ?>:</td><td> <code>checkbox</code></td><td class="cf-wh">&nbsp;</td><td><?php _e('Comment Author', 'cforms'); ?>:</td><td> <code>author</code></td></tr>
	                <tr><td><?php _e('Check boxes groups', 'cforms'); ?>:</td><td> <code>checkboxgroup</code></td><td class="cf-wh">&nbsp;</td><td><?php _e('Author\'s Email', 'cforms'); ?>:</td><td> <code>email</code></td></tr>
	                <tr><td><?php _e('Drop down fields', 'cforms'); ?>:</td><td> <code>selectbox</code></td><td class="cf-wh">&nbsp;</td><td><?php _e('Author\'s URL', 'cforms'); ?>:</td><td> <code>url</code></td></tr>
	                <tr><td><?php _e('Multi select boxes', 'cforms'); ?>:</td><td> <code>multiselectbox</code></td><td class="cf-wh">&nbsp;</td><td><?php _e('Author\'s Comment', 'cforms'); ?>:</td><td> <code>comment</code></td></tr>
	                <tr><td><?php _e('Radio buttons', 'cforms'); ?>:</td><td> <code>radiobuttons</code></td><td class="cf-wh">&nbsp;</td><td><?php _e('Select: Email/Comment', 'cforms'); ?>:</td><td> <code>send2author</code></td></tr>
	                <tr><td><?php _e('\'CC\' check box', 'cforms'); ?> <sup>*)</sup>:</td><td> <code>ccbox</code></td></tr>
	                <tr><td><?php _e('Multi-recipients field', 'cforms'); ?> <sup>*)</sup>:</td><td> <code>emailtobox</code></td></tr>
	                <tr><td><?php _e('Spam/Q&amp;A verification', 'cforms'); ?> <sup>*)</sup>:</td><td> <code>verification</code></td></tr>
	                <tr><td><?php _e('Spam/captcha verification', 'cforms'); ?> <sup>*)</sup>:</td><td> <code>captcha</code></td></tr>
	                <tr><td><?php _e('File upload fields', 'cforms'); ?> <sup>*)</sup>:</td><td> <code>upload</code></td></tr>
	                <tr><td><?php _e('Begin of a fieldset', 'cforms'); ?>:</td><td> <code>fieldsetstart</code></td></tr>
	                <tr><td><?php _e('End of a fieldset', 'cforms'); ?>:</td><td> <code>fieldsetend</code></td></tr>
	            </table>
	        </li>
	        <li><sup>*)</sup> <em><?php _e('Should only be used <strong>once</strong> per generated form!', 'cforms'); ?></em></li>
	        </ul>

        <br />

		<a id="ex1"></a>
        <strong><?php _e('Simple example:', 'cforms'); ?></strong>
        <ul style="list-style:none;">
        <li>
        <pre style="font-size: 11px;"><code>
$fields = array();

$formdata = array(	
		array('<?php _e('Your Name|Your Name', 'cforms'); ?>','textfield',0,1,0,1,0),
		array('<?php _e('Your Email', 'cforms'); ?>','textfield',0,0,1,0,0),
		array('<?php _e('Your Message', 'cforms'); ?>','textarea',0,0,0,0,0)
		);
				
$i=0;
foreach ( $formdata as $field ) {
	$fields['label'][$i]        = $field[0];
	$fields['type'][$i]         = $field[1];
	$fields['isdisabled'][$i]   = $field[2];
	$fields['isreq'][$i]        = $field[3];
	$fields['isemail'][$i]      = $field[4];						
	$fields['isclear'][$i]      = $field[5];						
	$fields['isreadonly'][$i++] = $field[6];		
}

insert_custom_cform($fields,'');    //<?php _e('Call default form with two defined fields', 'cforms'); ?></code></pre>
        </li>
        </ul>

        <br />

		<a id="ex2"></a>
        <?php _e('<strong>More advanced example</strong> (file access)', 'cforms'); ?><strong>:</strong>
        <ul style="list-style:none;">
        <li>
        <pre style="font-size:11px"><code>
$fields['label'][0]  ='<?php _e('Your Name|Your Name', 'cforms'); ?>';
$fields['type'][0]   ='textfield';
$fields['isreq'][0]  ='1';
$fields['isemail'][0]='0';
$fields['isclear'][0]='1';
$fields['label'][1]  ='<?php _e('Email', 'cforms'); ?>';
$fields['type'][1]   ='textfield';
$fields['isreq'][1]  ='0';
$fields['isemail'][1]='1';
$fields['label'][2]  ='<?php _e('Please pick a month for delivery:', 'cforms'); ?>||font-size:14px; padding-top:12px; text-align:left;';
$fields['type'][2]   ='textonly';

$fields['label'][3]='<?php _e('Deliver on#Please pick a month', 'cforms'); ?>|-#';

$fp = fopen(dirname(__FILE__).'/months.txt', "r"); // <?php _e('Need to put this file into your themes dir!', 'cforms'); ?> 

while ($nextitem = fgets($fp, 512))
	$fields['label'][3] .= $nextitem.'#';

fclose ($fp);

$fields['label'][3]  = substr( $fields['label'][3], 0, strlen($fields['label'][3])-1 );  //<?php _e('Remove the last \'#\'', 'cforms'); ?> 
$fields['type'][3]   ='selectbox';
$fields['isreq'][3]  ='1';
$fields['isemail'][3]='0';

insert_custom_cform($fields,5);    //<?php _e('Call form #5 with new fields', 'cforms'); ?></code></pre>
        </li>
        </ul>

        <?php _e('With <code>month.txt</code> containing all 12 months of a year:', 'cforms'); ?>
        <ul style="list-style:none;">
        <li>
        <pre><code>
<?php _e('January', 'cforms'); ?>

<?php _e('February', 'cforms'); ?>

<?php _e('March', 'cforms'); ?>

...</code></pre>
        </li>
        </ul>        

		</div>


	    <h3 id="variables"><a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a><a id="b23" class="blindminus" onfocus="this.blur()" onclick="toggleui(23);return false;" href="#" title="<?php _e('Expand/Collapse', 'cforms') ?>"></a><span class="h3title-no">7.</span><?php _e('Using variables in email subject and messages', 'cforms'); ?></h3>

		<div id="o23">
			<p>
				<?php echo sprintf(__('Email <strong>subjects and messages</strong> for emails both to the <a href="%s" %s>form admin</a> as well as to the <a href="%s" %s>visitor</a> (auto confirmation, CC:) support insertion of pre-defined variables and/or any of the form input fields.', 'cforms'),'?page=' . $plugindir . '/cforms-options.php#anchoremail','onclick="setshow(2)"','?page=' . $plugindir . '/cforms-options.php#autoconf','onclick="setshow(5)"'); ?>
			</p>
			<p class="ex"><?php _e('Note that the variable names are case sensitive!', 'cforms'); ?></p>
	
			<table class="hf" cellspacing="2" border="4">
				<tr>
					<td class="bright" colspan="2"><span class="abbr" title="<?php _e('Case sensitive!', 'cforms'); ?>"><?php _e('Predefined variables:', 'cforms'); ?></span></td>
				</tr>
				<tr>
					<td class="bleft">{BLOGNAME}</td>
					<td class="bright"><code><?php _e('Inserts the Blog\'s name.', 'cforms'); ?></code></td>
				</tr>
				<tr>
					<td class="bleft">{Form Name}</td>
					<td class="bright"><code><?php _e('Inserts the form name (per your configuration).', 'cforms'); ?></code></td>
				</tr>
				<tr>
					<td class="bleft">{Page}</td>
					<td class="bright"><code><?php _e('Inserts the WP page the form was submitted from.', 'cforms'); ?></code></td>
				</tr>
				<tr>
					<td class="bleft">{Date}</td>
					<td class="bright"><code><?php _e('Inserts the date of form submission (per your general WP settings).', 'cforms'); ?></code></td>
				</tr>
				<tr>
					<td class="bleft">{Time}</td>
					<td class="bright"><code><?php _e('Inserts the time of form submission (per your general WP settings).', 'cforms'); ?></code></td>
				</tr>
				<tr>
					<td class="bleft">{IP}</td>
					<td class="bright"><code><?php _e('Inserts visitor IP address.', 'cforms'); ?></code></td>
				</tr>
				<tr>
					<td class="bleft">{ID}</td>
					<td class="bright"><code><?php _e('Inserts a unique and referenceable form ID (provided that DB Tracking is enabled!)', 'cforms'); ?></code></td>
				</tr>
				<tr>
					<td class="bleft">{CurUserID}</td>
					<td class="bright"><code><?php _e('Inserts the ID of the currently logged-in user.', 'cforms'); ?></code></td>
				</tr>
				<tr>
					<td class="bleft">{CurUserName}</td>
					<td class="bright"><code><?php _e('Inserts the Name of the currently logged-in user.', 'cforms'); ?></code></td>
				</tr>
				<tr>
					<td class="bleft">{CurUserEmail}</td>
					<td class="bright"><code><?php _e('Inserts the Email Address of the currently logged-in user.', 'cforms'); ?></code></td>
				</tr>

				<tr>
					<td class="bleft"><em><?php _e('Special:', 'cforms'); ?></em></td>
					<td class="bright"><code><?php echo sprintf(__('A single %s (period) on a line inserts a blank line.', 'cforms'),'"<code>.</code>"'); ?></code></td>
				</tr>
				<tr id="tafvariables">
					<td class="bright" colspan="2"><span class="abbr" title="<?php _e('Case sensitive!', 'cforms'); ?>"><?php _e('Predefined variables for Tell-A-Friend forms:', 'cforms'); ?></span></td>
				</tr>
				<tr>
					<td class="bleft">{Permalink}</td>
					<td class="bright"><code><?php _e('Inserts the URL of the WP post/page.', 'cforms'); ?></code></td>
				</tr>
				<tr>
					<td class="bleft">{Author}</td>
					<td class="bright"><code><?php _e('Inserts the Author\'s name (<em>Nickname</em>).', 'cforms'); ?></code></td>
				</tr>
				<tr>
					<td class="bleft">{Title}</td>
					<td class="bright"><code><?php _e('Inserts the WP post or page title.', 'cforms'); ?></code></td>
				</tr>
				<tr>
					<td class="bleft">{Excerpt}</td>
					<td class="bright"><code><?php _e('Inserts the WP post or page excerpt.', 'cforms'); ?></code></td>
				</tr>
				<tr>
					<td class="ball" colspan="2">
						<?php echo sprintf(__('Alternatively, you can also include any of your form input fields by referring to the exact (!) <strong>field name</strong>:<br /><u>Example:</u> The default form comes with a %1$sYour Name%2$s and %1$sWebsite%2$s field.', 'cforms'),'<span style="font-weight:bold; background:#f2f2f2;">','</span>'); ?>
						<br />
						<?php echo sprintf(__('The corresponding variables to be used would be: %1$s{Your Name}%2$s &amp; %1$s{Website}%2$s.', 'cforms'),'<span style="font-weight:bold; background:#f2f2f2;">','</span>'); ?>					
					</td>
				</tr>			
			</table>
			<br />
			<table class="hf" cellspacing="2" border="4" width="75%"> 
				<tr>
					<td class="bright" style="padding:10px; background:#fdcbaa;" colspan="2"><?php _e('<strong>IMPORTANT:</strong> If you are using multiple input fields with <strong>the same</strong> recorded field label (you can always check the "Tracking" menu tab for how the fields are stored), e.g:', 'cforms'); ?><br />
<pre style="font-size:11px"><code>
<strong>Size</strong>#250gr.#500gr#1kg circa
<strong>Size</strong>#450gr.#700gr#1.2kg circa
<strong>Size</strong>#650gr.#800gr#1.5kg circa
</code></pre>
					<br />

					<?php echo sprintf(__('Results in the first field labeled %1$s to be addressed with %2$s. The second instance of %1$s can be addressed by %3$s, and so on...', 'cforms'),'\'<strong>Size</strong>\'','<code class="codehighlight">{Size}</code>','<code class="codehighlight">{Size__2}</code>'); ?>
					</td>
				</tr>	
			</table>
			<br />
			<table class="hf" cellspacing="2" border="4" width="75%"> 
				<tr>
					<td class="bright" colspan="2"><?php echo sprintf(__('Here is an example for a simple <a href="%s" %s>Admin HTML message</a> <em>(you can copy and paste the below code or change to your liking)</em>:', 'cforms'),'?page=' . $plugindir . '/cforms-options.php#cforms_header_html','onclick="setshow(3)"'); ?></td>
				</tr>
	
				<tr>
					<td class="ball" colspan="2">
						<strong><?php _e('HTML code:', 'cforms'); ?></strong><br />
						<?php echo '<p>&lt;p style="background:#fafafa; text-align:center; font:10px arial"&gt;' . sprintf(__('a form has been submitted on %s, via: %s [IP %s]', 'cforms'),'{Date}','{Page}','{IP}') . '&lt;/p&gt;</p>'; ?>
					</td>
				</tr>			
				<tr>
					<td class="ball" colspan="2">
						<strong><?php _e('Output:', 'cforms'); ?></strong><br />
						<?php echo '<p style="background:#fafafa; text-align:center; font:10px arial">' . __('a form has been submitted on June 13, 2007 @ 9:38 pm, via: / [IP 184.153.91.231]', 'cforms') . '</p>'; ?>					
					</td>
				</tr>			
				<tr>
					<td class="ball" colspan="2">
						<strong><?php _e('Note:', 'cforms'); ?></strong> <?php _e('With this limited message you\'d want to enable the option "Include pre formatted form data table in HTML part"', 'cforms'); ?><br />
					</td>
				</tr>			
			</table>
			<br />
			<table class="hf" cellspacing="2" border="4" width="75%"> 
				<tr>
					<td class="bright" colspan="2"><?php echo sprintf(__('Here is another example for a more detailed <a href="%s" %s>Admin HTML message</a>:', 'cforms'),'?page=' . $plugindir . '/cforms-options.php#cforms_header_html','onclick="setshow(3)"'); ?></td>
				</tr>
	
				<tr>
					<td class="ball" colspan="2">
						<strong><?php _e('HTML code:', 'cforms'); ?></strong><br />
						<?php echo '<p>&lt;p&gt;'.__('{Your Name} just submitted {Form Name}. You can get in touch with him/her via &lt;a href="mailto:{Email}"&gt;{Email}&lt;/a&gt; and might want to check out his/her web page at &lt;a href="{Website}"&gt;{Website}&lt;/a&gt;', 'cforms') . '&lt;/p&gt;</p><p>&lt;p&gt;' .  __('The message is:', 'cforms') . '&lt;br/ &gt;<br />'.__('{Message}', 'cforms') . '&lt;/p&gt;</p>'; ?>
					</td>
				</tr>			
				<tr>
					<td class="ball" colspan="2">
						<strong><?php _e('Output:', 'cforms'); ?></strong><br />
						<?php echo '<p>' . __('John Doe just submitted MY NEW FORM. You can get in touch with him/her via <a href="mailto:#">john.doe@doe.com</a> and might want to check out his/her web page at <a href="#">http://website.com</a>', 'cforms') . '</p>'; ?>					
						<?php echo '<p>' . __('The message is:', 'cforms') . '<br />'; ?>					
						<?php echo  __('Hey there! Just wanted to get in touch. Give me a ring at 555-...', 'cforms') . '</p>'; ?>					
					</td>
				</tr>			
				<tr>
					<td class="ball" colspan="2">
						<strong><?php _e('Note:', 'cforms'); ?></strong> <?php _e('With this more detailed message you can disable the option "Include pre formatted form data table in HTML part" since you already have all fields covered in the actual message/header.', 'cforms'); ?><br />
					</td>
				</tr>			
			</table>
			<br />
			<table class="hf" cellspacing="2" border="4" width="75%"> 
				<tr>
					<td class="bright" colspan="2"><?php echo sprintf(__('And a final example for a <a href="%s" %s>HTML auto confirmation message</a>:', 'cforms'),'?page=' . $plugindir . '/cforms-options.php#cforms_cmsg_html','onclick="setshow(5)"'); ?></td>
				</tr>
	
				<tr>
					<td class="ball" colspan="2">
						<strong><?php _e('HTML code:', 'cforms'); ?></strong><br />
						<?php echo '<p>&lt;div style="text-align:center; color:#aaa; border-bottom:1px solid #aaa"&gt; &lt;strong&gt;' . __('auto confirmation message', 'cforms') . ', {Date}&lt;/strong&gt; &lt;/div&gt;&lt;br /&gt;</p>'; ?>
						<?php echo '&lt;p&gt;&lt;strong&gt;' . __('Dear {Your Name},', 'cforms') . '&lt;/strong&gt;&lt;/p&gt;<br />'; ?>
						<?php echo '&lt;p&gt;' . __('Thank you for your note!', 'cforms') . '&lt;/p&gt;<br />'; ?>
						<?php echo '&lt;p&gt;' . __('We will get back to you as soon as possible.', 'cforms') . '&lt;/p&gt;<br />'; ?>
					</td>
				</tr>			
				<tr>
					<td class="ball" colspan="2">
						<strong><?php _e('Output:', 'cforms'); ?></strong><br />				
						<?php echo '<div style="text-align:center; color:#aaa; border-bottom:1px solid #aaa"><strong>' . __('auto confirmation message', 'cforms') . ', June 13, 2007 @ 5:03 pm</strong></div><br />'; ?>					
						<?php echo '<p><strong>' . __('Dear John Doe,', 'cforms') . '</strong></p>'; ?>					
						<?php echo '<p>' . __('Thank you for your note!', 'cforms') . '</p>'; ?>					
						<?php echo '<p>' . __('We will get back to you as soon as possible.', 'cforms') . '</p>'; ?>					
					</td>
				</tr>			
			</table>
		</div>


	    <h3 id="CSS"><a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a><a id="b24" class="blindminus" onfocus="this.blur()" onclick="toggleui(24);return false;" href="#" title="<?php _e('Expand/Collapse', 'cforms') ?>"></a><span class="h3title-no">8.</span><?php _e('Styling Your Forms (CSS theme files)', 'cforms'); ?></h3>

		<div id="o24">
			<p><?php echo sprintf(__('Please see the <a href="%s">Styling page</a> for theme selection and editing options.', 'cforms'),'?page=' . $plugindir . '/cforms-css.php'); ?></p>
			<p><?php _e('cforms comes with a few theme examples (some of the may require adjustments to work with <strong>your</strong> forms!) but you can of course create your own theme file -based on the default <strong>cforms.css</strong> file- and put it in the <code>/styling</code> directory.', 'cforms'); ?></p>
			<p><?php echo sprintf(__('You might also want to study the <a href="%s">PDF guide on cforms CSS &amp; a web screencast</a> I put together to give you a head start.', 'cforms'),'http://www.deliciousdays.com/cforms-forum?forum=1&amp;topic=428&amp;page=1'); ?></p>
			<p class="ex"><?php _e('Your form <strong>doesn\'t</strong> look like the preview image, or your individual changes don\'t take effect, check your global WP theme CSS! It may overwrite some or many cforms CSS declarations. If you don\'t know how to trouble shoot, take a look at the Firefox extension "Firebug" - an excellent CSS troubleshooting tool!', 'cforms'); ?></p>
		</div>


	    <h3 id="troubles"><a class="helptop" href="#top"><?php _e('top', 'cforms'); ?></a><a id="b25" class="blindminus" onfocus="this.blur()" onclick="toggleui(25);return false;" href="#" title="<?php _e('Expand/Collapse', 'cforms') ?>"></a><span class="h3title-no">9.</span><?php _e('Need more help?', 'cforms'); ?></h3>

		<div id="o25">
			<p><?php _e('For up-to-date information check the <a href="http://www.deliciousdays.com/cforms-forum">cforms forum</a> and comment section on the plugin homepage.', 'cforms'); ?></p>
		</div>


	<?php cforms_footer(); ?>
</div>