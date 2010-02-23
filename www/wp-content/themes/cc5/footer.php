
    <div id="footer">
      <div id="footerContent" class="box">
        <ul>
          <li><a href="#top"><?php _e('Top', 'cc5'); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;</li>
          <li><a href="/weblog"><?php _e('Blog', 'cc5'); ?></a></li>
          <li><a href="http://support.creativecommons.org/"><?php _e('Donate', 'cc5'); ?></a></li>
          <li><a href="/policies"><?php _e('Policies', 'cc5'); ?></a></li>
          <li><a href="/privacy"><?php _e('Privacy', 'cc5'); ?></a></li>
          <li><a href="/terms"><?php _e('Terms of Use', 'cc5'); ?></a></li>
          <li><a href="/about/press"><?php _e('Press Room', 'cc5'); ?></a></li>
          <li><a href="/sitemap"><?php _e('Sitemap', 'cc5'); ?></a></li>
          <li><a href="/contact"><?php _e('Contact', 'cc5'); ?></a></li>
        </ul>
      </div>
      <div id="footerLicense">
        <p class="box">
          <a rel="license" href="http://creativecommons.org/licenses/by/3.0/">
            <img src="http://i.creativecommons.org/l/by/3.0/88x31.png" alt="<?php _e('Creative Commons License', 'cc5'); ?>" style="border:none;" height="31" width="88">
          </a>
          <?php _e('Except where otherwise <a class="subfoot" href="/policies#license">noted</a>, content on this site is<br/>
          licensed under a <a rel="license" href="http://creativecommons.org/licenses/by/3.0/" class="subfoot">Creative Commons
          Attribution 3.0 License</a>', 'cc5'); ?>
        </p>
     </div>
    </div>
    
  </div>

<? wp_footer(); ?>

<?php 
if ($extra_footer) { echo $extra_footer; }
?>
<script type="text/javascript" charset="utf-8" src="<?php bloginfo('stylesheet_directory'); ?>/pageviewHelper.js"></script>
</body>
</html>
