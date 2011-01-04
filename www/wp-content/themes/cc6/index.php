<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
	<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
	<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
  <meta name="keywords" content="creative commons, commons, free culture, open source, attribution, non-commercial, share-alike, no derivatives, lessig" />
  <meta name="description" content="Creative Commons licenses provide a flexible range of protections and freedoms for authors, artists, and educators." />

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
	<body class="home">
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
					<div><a class="grid_3 alpha button" href="/about/">Learn More...</a></div>
					<div style=""><a class="grid_3 omega button" href="/license/">Choose License</a></div>
				</div>
			</div>
		</div>

		<div id="page">

			<?php include "index-head.php"; ?>

			<div id="content">
				<div class="container_16">
					<div class="grid_8 suffix_4">
						<h3>What is Creative Commons?</h3>
						<p>
Our vision is to realize the full potential of the Internet through universal access to scientific research, education, and culture.
						<br/>
						<a href="/about/">Learn More...</a></p>
					</div>
					<div class="grid_4 ">
						<h3><a href="https://creativecommons.net/donate/">Donate</a></h3>
						<p>Creative Commons is a non-profit organization, we need your support.</p>
					</div>
					<div class="clear"></div>
					<div class="grid_4">
						<div class="auto">
							<div class="grid_1 alpha omega"><div class="icon"><img src="/images/projects/sciencecommons.png"/></div></div>
							<div class="grid_3">
								<h5>Licenses</h5>
								<p>Creative Commons licenses provide simple, standardized alternatives to the "all rights reserved" paradigm of traditional copyright.<br/>
								<a href="/licenses/">Learn More...</a></p>
							</div>
						</div>
					</div>
					<div class="grid_4">
						<div class="auto">
							<div class="grid_1 alpha omega"><div class="icon"><img src="/images/projects/sciencecommons.png"/></div></div>
							<div class="grid_3">
								<h5>Culture</h5>
								<p>Whether you're a photographer, writer, filmmaker, or DJ, our licenses help make your work part of the cultural innovation that's multiplying on the Internet.<br/>
								<a href="/culture/">Learn More...</a></p>
							</div>
						</div>
					</div>
					<div class="grid_4 omega">
						<div class="auto">
							<div class="grid_1 alpha omega"><div class="icon"><img src="/images/projects/sciencecommons.png"/></div></div>
							<div class="grid_3">
								<h5>Education</h5>
								<p>We make access to education easy and universal by making textbooks, lectures, and lesson plans freely available over the Internet.<br/>
								<a href="/education/">Learn More...</a></p>
							</div>
						</div>
					</div>
					<div class="grid_4">
						<div class="auto">
							<div class="grid_1 alpha omega"><div class="icon"><img src="/images/projects/sciencecommons.png"/></div></div>
							<div class="grid_3">
								<h5>Science</h5>
								<p>We believe that scientific research, journals, and data should be available to everyone, and have legal tools that help make this happen.<br/>
								<a href="/science/">Learn More...</a></p>
							</div>
						</div>
					</div>
				</div>
			</div>

<?php get_footer(); ?>

