<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
<div>
<? if (!$is_footer) {?><p><? } ?>
<input type="text" value="<?php the_search_query(); ?>" name="s" id="s" size="30" />
<input type="submit" id="searchsubmit" value="Search" />
<? if (!$is_footer) {?></p><? } ?>
<? if (!is_category() && !$is_footer) { ?>
<label><input type="radio" name="st" value="blog" checked /> Search blog</label>
&nbsp;&nbsp;&nbsp;
<label><input type="radio" name="st" value="site" /> Search creativecommons.org using Google</label>
<? } ?>
</div>
</form>
