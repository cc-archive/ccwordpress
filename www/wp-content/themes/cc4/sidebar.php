<div id="sidebar" class="content-box-right">
  <div class="sideitem">
    <h4><a href="http://creativecommons.org/international/">International</a></h4>
    <select id="international" name="sortby" onchange="orderby(this)">
      <option value="">Select a jurisdiction</option>
      <script type="text/javascript" src="http://api.creativecommons.org/rest/dev/support/jurisdictions.js"></script>
    </select>
    <span class="international"><a href="/international/">More information</a></span>
  </div>
  
  <div class="sideitem">
    <h4>Search</h4>
    <?php $is_sidebar = true; include (TEMPLATEPATH . '/searchform.php'); ?>
    <div class="clear"></div>
  </div>
  
  <div class="sideitem">
    <ul>
      <li ><a href="/commoners" >Creative Commoners</a></li>
      <li><a href="/projects/documentation">Documentation</a></li>
      <li><a href="/projects/casestudies">Case Studies</a></li>
      <li><a href="http://wiki.creativecommons.org/FFAQ">FAQ</a></li>
      <li><a href="/about/opportunities">Opportunities</a></li>
      <li><a href="/presskit">Press Kit</a></li>
    </ul>
  </div>
  <div class="sideitem">
    <ul>
      <li><a href="/press-releases">Press Releases</a></li>
      <li><a href="/about/newsletter">Newsletter</a></li>
      <li><a href="http://wiki.creativecommons.org/Events">Events</a></li>
    </ul>
  </div>

  <div class="sideitem">
    <h4>The Commons</h4>
    <ul>
	  <li><a href="https://creativecommons.net/">CC Network</a></li>
      <li><!-- img src="/images/commons/sc.png" alt="Science Commons" /--> <a href="http://sciencecommons.org/">Science Commons</a></li>
      <li><!-- img src="/images/commons/cci.png" alt="ccInternational" /--> <a href="http://creativecommons.org/international/">ccInternational</a></li>
      <li><!-- img src="/images/commons/learn.png" alt="ccLearn" /--> <a href="http://learn.creativecommons.org/">ccLearn</a></li>
	</ul>
  </div>


</div>
