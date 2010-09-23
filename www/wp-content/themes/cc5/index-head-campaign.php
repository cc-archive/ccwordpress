<div id="splashBox" style="overflow: visible;">
	<div id="splash">
		<a href="http://zupport.creativecommons.org/donate?utm_source=ccorg&utm_medium=homepage_banner&utm_campaign=catalyst"><img src="/images/support/2010/cc-org-banner-Strength.png" border="0" align="center" alt="Invest in the future of creativity and knowledge. Donate Today." /></a>
		<?php include ('progress.php'); ?>

	</div>
	<?php /* Show random Superhero here */ ?>
	<div id="superheroCard" style="position:absolute; left: 10px; top: 9px; width:150px; height:189px; background:#fff; padding: 5px; -webkit-border-radius: 4px; -moz-border-radius:4px; -webkit-box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.5); -moz-box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.5);">
		<a href="http://zupport.creativecommons.org/superheroes/robin-sloan"><img src="http://zupport.creativecommons.org/sites/default/files/hero-badge-robin-sloan.png" alt="Robin Sloan" border="0"/></a>
	</div>
	<div id="superheroHelp" style="position:absolute; left:9px; bottom:215px; width: 160px;  border:1px solid #ff0; background-color: #fff; text-align:center; display:none; -webkit-border-radius: 5px; -webkit-box-shadow: 0 0 5px #ff6; -moz-border-radius: 5px; -moz-box-shadow: 0 0 5px #ff6;">
		<p style="padding: 3px 0; margin: 0;">
			<strong>Featured</strong><br/>
			Click to learn something lorem.
		</p>
	</div> 

<script>
// Popup script, based on http://jqueryfordesigners.com/coda-popup-bubbles/
$("#superheroCard").each(function() {
	var timer = null;
	var visible = false;
	var animating = false;

	var card = $("#superheroCard img");
	var help = $("#superheroHelp").css({opacity:0});

	$([card.get(0), help.get(0)]).mouseover(function() {
		if (timer) clearTimeout(timer);

		if (animating || visible) {
			return;
		} else {
			animating = true;

			help.css({
				bottom:205,
				left:9,
				display: 'block' 
			}).animate({
				bottom: '+=10px',
				opacity: 1
			}, 250, 'swing', function() {
				animating = false;
				visible = true;
			});
		}
			
	}).mouseout(function() {
		if (timer) clearTimeout(timer);
		timer = setTimeout(function() {
			help.animate({
				bottom:'-=10px',
				opacity: 0
			}, 250, 'swing', function() {
				visible = false;
				help.css({display:'none'});
			});
		}, 500);
	});
});
</script>
</div>

