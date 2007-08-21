<div id="sidebar" class="content-box">
      <h4><a href="http://creativecommons.org/worldwide">International</a></h4>
      <select id="international" name="sortby" onchange="orderby(this)">
        <option value="">Select a jurisdiction</option>
        <script type="text/javascript" src="http://api.creativecommons.org/rest/dev/support/jurisdictions.js"></script>
      </select>
      <span class="international"><a href="/worldwide">More information</a></span>

<h4><br/>The Commons</h4>
<ul>
  <li><img src="ccindex_files/sc.png"> <a href="http://sciencecommons.org/">Science Commons</a></li>
  <li><img src="ccindex_files/ic.png"> <a href="http://icommons.org/">iCommons</a></li>
  <li><img src="ccindex_files/cci.png"> <a href="http://creativecommons.org/worldwide">ccInternational</a></li>
  <li><img src="ccindex_files/learn.png"> <a href="http://learn.creativecommons.org/">ccLearn</a></li>
  <li><img src="ccindex_files/labs.png"> <a href="http://labs.creativecommons.org/">ccLabs</a></li>
  <li><img src="ccindex_files/mix.png"> <a href="http://ccmixter.org/">ccMixter</a></li>
</ul>
<br/>
<h4>Explore</h4>
<ul>
<?php echo wpfm_create("explore",true,'list',true); ?>
</ul>

</div>
