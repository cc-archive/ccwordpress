<div id="sidebar" class="content-box-right">
      <h4><a href="http://creativecommons.org/international">International</a></h4>
      <select id="international" name="sortby" onchange="orderby(this)">
        <option value="">Select a jurisdiction</option>
        <script type="text/javascript" src="http://api.creativecommons.org/rest/dev/support/jurisdictions.js"></script>
      </select>
      <span class="international"><a href="/international">More information</a></span>
      <?php
      if (!is_home()) {
      ?>
        <h4><br/>Fundraising Campaign</h4>
  			<div id="campaign">
    			<div class="progress" onclick="window.location = 'http://support.creativecommons.org';"><span>&nbsp;</span></div>
				  <div class="results"><a href="http://support.creativecommons.org/">$<?= cc_monetize(cc_progress_total()) ?> / $500,000</a></div>
			  </div>         
      <?php
      }
      ?>
<h4><br/>The Commons</h4>
<ul>
  <li><img src="/images/commons/sc.png"> <a href="http://sciencecommons.org/">Science Commons</a></li>
  <li><img src="/images/commons/cci.png"> <a href="http://creativecommons.org/worldwide/">ccInternational</a></li>
  <li><img src="/images/commons/learn.png"> <a href="http://learn.creativecommons.org/">ccLearn</a></li>
  <li><img src="/images/commons/labs.png"> <a href="http://labs.creativecommons.org/">ccLabs</a></li>
  <li><img src="/images/commons/mix.png"> <a href="http://ccmixter.org/">ccMixter</a></li>
  <li><img src="/images/commons/ic.png"> <a href="http://icommons.org/">iCommons</a></li>
</ul>
<br/>
<h4>Explore</h4>
<ul>
<?php echo wpfm_create("explore",true,'list',true); ?>
</ul>

</div>
