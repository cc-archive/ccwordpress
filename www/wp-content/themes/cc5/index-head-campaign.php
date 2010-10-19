<?php
/* Superhero cards */

$hero[0][0] = "robin-sloan"; $hero[0][1] = "robin";
$hero[1][0] = "elizabeth-stark"; $hero[1][1] = "elizabeth";
$hero[2][0] = "mitchell-baker"; $hero[2][1] = "mitchell";
$hero[3][0] = "neeru-khosla"; $hero[3][1] = "neeru";
$hero[4][0] = "gautam-john"; $hero[4][1] = "gautam";
$hero[5][0] = "andrew-rens"; $hero[5][1] = "andrew";

$i = rand(0, count($hero));

?>

<div id="splashBox" style="overflow: visible;">
	<div id="splash">
<script>utmx_section("Banner")</script>
		<a onclick="gwoTracker._link('https://creativecommons.net/donate'); return false;" href="https://creativecommons.net/donate?utm_source=ccorg&utm_medium=homepage_banner&utm_campaign=superhero"><img src="/images/support/2010/cc-org-banner-Strength-2.png" border="0" align="center" alt="Invest in the future of creativity and knowledge. Donate Today." /></a>
</noscript>
		<?php include ('progress.php'); ?>
	</div>
	<div id="superhero">
		<div id="superheroCard">
		<a href="https://creativecommons.net/superheroes/<?= $hero[$i][0] ?>/?utm_campaign=superhero&utm_source=ccorg&utm_medium=<?= $hero[$i][1] ?>"><img src="/images/support/2010/superheroes/superhero-home-<?= $hero[$i][1] ?>.png" alt="<?= $hero[$i][0] ?>" border="0"/></a>
		</div>
	</div>
</div>

