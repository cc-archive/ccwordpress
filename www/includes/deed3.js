/**
 *  Support functions for Creative Commons Deeds
 * 
 *  (c) 2008, Nathan R. Yergler <nathan@creativecommons.org>
 *  Licensed to the public under the GNU GPL v2.
 *
 *  Depends upon the YUI library.
 * 
 **/

YAHOO.namespace("cc.deed");

YAHOO.cc.deed.init_disclaimer = function () {
   var pnl_disclaimer = new YAHOO.widget.Panel("disclaimer_panel", { 
       close:true,  
       visible:false,  
       draggable:false,
       width:'300px',
       context:['disclaimer','br','tl']
       } ); 
   pnl_disclaimer.render();

   YAHOO.util.Event.addListener("disclaimer", "click",
       function(e) {
           pnl_disclaimer.show();
           YAHOO.util.Event.stopEvent(e);
       }
   );

} // init_disclaimer

YAHOO.cc.deed.init_attribution = function () {
   var pnl_attribution = new YAHOO.widget.Panel("attribution_help_panel", { 
       close:true,  
       visible:false,  
       draggable:false,
       width:'400px',
       context:['attribution_help','tl','bl']
       } ); 
   pnl_attribution.render();

   YAHOO.util.Event.addListener("attribution_help", "click",
       function(e) {
           // show the panel and cancel event propogation
           pnl_attribution.show();
           YAHOO.util.Event.stopEvent(e);
       }
   );

} // init_disclaimer

YAHOO.util.Event.onDOMReady(YAHOO.cc.deed.init_disclaimer);
YAHOO.util.Event.onDOMReady(YAHOO.cc.deed.init_attribution);
