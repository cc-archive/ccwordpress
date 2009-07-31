
    <div id="footer">
      <div id="footerContent" class="box">
        <ul>
          <li><a href="#top">Top</a>&nbsp;&nbsp;&nbsp;&nbsp;</li>
          <li><a href="/weblog">Blog</a></li>
          <li><a href="http://support.creativecommons.org/">Donate</a></li>
          <li><a href="/policies">Policies</a></li>
          <li><a href="/privacy">Privacy</a></li>
          <li><a href="/terms">Terms of Use</a></li>
          <li><a href="/about/press">Press Room</a></li>
          <li><a href="/sitemap">Sitemap</a></li>
          <li><a href="/contact">Contact</a></li>
        </ul>
      </div>
      <div id="footerLicense">
        <p class="box">
          <a rel="license" href="http://creativecommons.org/licenses/by/3.0/">
            <img src="http://i.creativecommons.org/l/by/3.0/88x31.png" alt="Creative Commons License" style="border:none;" height="31" width="88">
          </a>
          Except where otherwise <a class="subfoot" href="/policies#license">noted</a>, content on this site is<br/>
          licensed under a <a rel="license" href="http://creativecommons.org/licenses/by/3.0/" class="subfoot">Creative Commons
          Attribution 3.0 License</a>
        </p>
     </div>
    </div>
    
  </div>

<? wp_footer(); ?>

<?php
// Tracer script
// http://tynt.com
// Only have tracer available for blog posts
if (is_single() || is_category() || is_home()) {
?>
<script type="text/javascript" src="http://tcr.tynt.com/javascripts/Tracer.js?user=aBgndMD7mr3Ovfab7jrHcU&s=61&cc=1"></script>
<?php } 

if ($extra_footer) { echo $extra_footer; }
?>

</body>
</html>
