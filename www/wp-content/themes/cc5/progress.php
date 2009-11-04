
   			<div id="campaign">  
				<div class="progress <?php if (is_home()) {?>home<?}?>" onclick="window.location='https://support.creativecommons.org/donate';">
					<span>&nbsp;</span>
				</div>
				<?php if (is_home()) { ?><div class="homeGoal"><a href="https://support.creativecommons.org/donate">$500,000</a></div><? } ?>
				<div class="results<?php if (is_home()) {?>Home<?}?>">
					<a href="https://support.creativecommons.org/donate">
					<?php if (is_home()) { ?><strong><?php cc_progress_total() ?> Raised</strong> &mdash; Help us reach our goal by Dec 31!<?php } else { ?>
						<?php cc_progress_total() ?> / $500,000 by&nbsp;Dec&nbsp;31 
						<br/>
						<em>Help us reach our goal!</em>
					<?php } ?>
					</a>
				</div>
   			</div>
