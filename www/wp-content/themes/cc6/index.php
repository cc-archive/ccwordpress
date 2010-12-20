<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
	<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
	<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
  <meta name="keywords" content="creative commons, commons, free culture, open source, attribution, non-commercial, share-alike, no derivatives, lessig" />
  <meta name="description" content="Creative Commons licenses provide a flexible range of protections and freedoms for authors, artists, and educators." />


	<!-- <link rel="shortcut icon" href="favicon.ico"><link rel="icon" type="image/gif" href="animated_favicon1.gif"> -->
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

	<?php if (is_home() || is_404()) {?>
  <title>Creative Commons</title>
  <?php } else if (is_search()) { ?>
  <title>Search Creative Commons</title>
  <?php } else { ?>
  <title><?php wp_title(''); ?> - Creative Commons</title>
  <?php }?>

	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />

	<link href="<?php bloginfo('stylesheet_directory'); ?>/support.css" rel="stylesheet" type="text/css" />
 	<link href="http://creativecommons.org/includes/total.css" rel="stylesheet" type="text/css" />

	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('home')?>/weblog/rss" />

	<script type="text/javascript" charset="utf-8" src="<?php bloginfo('stylesheet_directory'); ?>/jquery.min.js"></script>
	<script type="text/javascript" charset="utf-8" src="<?php bloginfo('stylesheet_directory'); ?>/site.js"></script>

	<?php wp_head(); ?>
</head>
	<body>
		<a id="top"></a>
		<div id="header">
			<div class="container_16">
				<div class="grid_16 alpha">
					<h1 id="logo"><a href="<?php echo get_settings('home'); ?>"><span>Creative Commons</span></a></h1>

					<ul class="nav">
						<li><a href="/about/">About</a></li>
						<li><a href="/weblog/">Blog</a></li>
						<li><a href="https://creativecommons.net/donate">Donate</a></li>
						<li><a href="http://wiki.creativecommons.org/FAQ">FAQ</a></li>
						<li><a href="http://wiki.creativecommons.org/">Wiki</a></li>
						<li><a href="/international/">International</a></li>
					</ul>
				</div>
			</div>
			<div class="container_16" id="intro">
				<div class="grid_9" id="mission">
					<h1>Share, Remix, Reuse &mdash; Legally</h1>
					<h4>Creative Commons is a nonprofit organization that develops, supports, and stewards legal and technical infrastructure that maximizes digital creativity, sharing, and innovation.</h4>
				</div>
				<div id="search_and_buttons" class="grid_6 prefix_1 ">
					<div class="search_container">
						<div><strong>Search for licensed content:</strong></div>
						<form method="get" action="http://search.creativecommons.org" id="search_form">
							<input type="text" class="search_text" name="q" />
							<input type="submit" class="search_submit" value="Search"/>
						</form>
					</div>
					<div><a class="grid_3 alpha button" href="/about/">Learn More</a></div>
					<div style=""><a class="grid_3 omega button" href="/license/">Choose License</a></div>
				</div>
			</div>
		</div>

		<div id="page">
			<div id="features">
				<div class="container_16">
					<?php include "index-head-campaign.php"; ?>
				</div>
			</div>


			<div id="content">
				<div class="container_16">
					<div class="grid_4">
						<h5>What is Creative Commons?</h5>
						<p>The idea of universal access to research, education, and culture is made possible by the internet, but our legal and social systems don’t always allow that idea to be realized. Copyright was created long before the emergence of the internet, and can make it hard to legally perform actions we take for granted on the network: copy, paste, edit source, and post to the Web.</p>
					</div>
					<div class="grid_4">
						<div class="auto">
							<div class="grid_1 alpha omega"><div class="icon">&nbsp;</div></div>
							<div class="grid_3">
								<h5>Something useful</h5>
								<p>The default setting of copyright law requires all of these actions to have explicity permission granted in advance, whether you're an</p>
							</div>
						</div>
						<div class="auto">
							<div class="grid_1 alpha omega"><div class="icon">&nbsp;</div></div>
							<div class="grid_3">
								<h5>Something useful</h5>
								<p>The default setting of copyright law requires all of these actions to have explicity permission granted in advance, whether you're an</p>
							</div>
						</div>
					</div>
					<div class="grid_4">
						<div class="auto">
							<div class="grid_1 alpha omega"><div class="icon">&nbsp;</div></div>
							<div class="grid_3">
								<h5>Something useful</h5>
								<p>The default setting of copyright law requires all of these actions to have explicity permission granted in advance, whether you're an</p>
							</div>
						</div>
						<div class="auto">
							<div class="grid_1 alpha omega"><div class="icon">&nbsp;</div></div>
							<div class="grid_3">
								<h5>Something useful</h5>
								<p>The default setting of copyright law requires all of these actions to have explicity permission granted in advance, whether you're an</p>
							</div>
						</div>
					</div>
					<div class="grid_4">
						<h5><a href="https://creativecommons.net/donate/">Donate</a></h5>
						<p>Creative Commons is a non-profit organization that needs your support to provide tools.</p>
					</div>
				</div>
			</div>

<?php get_footer(); ?>

