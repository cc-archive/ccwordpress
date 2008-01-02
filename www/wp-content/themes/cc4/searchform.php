<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
<div><input type="text" value="<?php the_search_query(); ?>" name="s" id="s" size="30" />
<input type="submit" id="searchsubmit" value="Search" />
&nbsp;&nbsp;&nbsp;
<label><input type="radio" name="searchtype" value="blog" checked /> Search blog</label>
&nbsp;&nbsp;&nbsp;
<label><input type="radio" name="searchtype" value="site" /> Search site</label>
</div>
</form>
