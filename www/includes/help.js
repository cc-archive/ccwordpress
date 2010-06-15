function bannerHtml() {
    var banner = new Array(3); for (i = 0; i < banner.length; ++i) banner[i] = new Array(2);
    banner[0][0] = "&utm_medium=banner_1"; banner[0][1] = "Help the world harness the power of Creative Commons.";
    banner[1][0] = "&utm_medium=banner_2"; banner[1][1] = "Nothing is more powerful than shared knowledge.";
    banner[2][0] = "&utm_medium=banner_3"; banner[2][1] = "Ignite openness and innovation around the world.";
    return banner;
}
function thundercats() {
	if (!location.href.match(/creativecommons.org\/(publicdomain|licenses)/)) return;

    var i = Math.floor(Math.random() * 3);
    var banners = bannerHtml();

    var d = document.createElement("div");
    var mainContent = document.getElementById("globalWrapper");
    d.setAttribute('style', 'font-size: 22px; font-family: "helvetica neue", arial, sans-serif; line-height:1; text-shadow: 0 1px 0 #45c5ea; color: #ffff00; padding: 12px 0; border-bottom: 1px solid rgb(120, 159, 44); margin-top: -1px; background: #35afe3; z-index:1000;');
    d.innerHTML = '<a href="https://support.creativecommons.org/donate/?utm_campaign=catalyst' + banners[i][0] + '&utm_source=ccorg" style="color:#ffff00; text-decoration:none;"><strong><span style="color:#000">Catalyst Grants:</span> <span style="text-shadow: 0 -1px 0 #258ab9">' + banners[i][1] + '</span></strong> <span style="color:#000">&mdash; Donate Now</span></a>';
    mainContent.parentNode.insertBefore(d, mainContent);
}


if (typeof window.addEventListener !== 'undefined') {
    window.addEventListener('load', thundercats, false);
} else {
    window.attachEvent('onload', thundercats);
}


YAHOO.namespace("cc.help");

// convenience function for creating help tool tips
YAHOO.cc.help.init_help_item = function(help_anchor) { 

    var link_id = help_anchor.id;
    var help_id = 'help_' + link_id;

    // create the new panel and position it
    var new_panel = new YAHOO.widget.Panel(help_id, 
                            {close: true, 
			     visible: false, 
			     draggable: false, 
			     width:'350px',
			     context:[help_anchor.id,'bl','tl',['beforeShow']],
			     constraintoviewport: true
			    } ); 
    new_panel.rendered = false;

    var item_idx = YAHOO.cc.help.help_panels.push(new_panel) - 1;

    // remove the initial class (used to keep the panel hidden)
    YAHOO.util.Dom.removeClass(help_id, "help_panel");

    // connect the event handlers

    // show the panel on click
    YAHOO.util.Event.addListener(link_id, "click", 
    function(e) {
	YAHOO.cc.help.help_panels[item_idx].show();
	YAHOO.util.Event.stopEvent(e);
    });

    // we subscribe to beforeShow to handle rendering; 
    // rendering at load time causes the final panel to be skipped (wtf?)
    new_panel.beforeShowEvent.subscribe(
        function(e) {
	    if (!this.rendered) {
	        this.render();
		this.rendered = true;
	    }
	});

} // init_help_text

// initialization for help pop-ups
YAHOO.cc.help.init = function() {

    // initialize a container for the panels
    YAHOO.cc.help.help_panels = new Array();

    // find helpLinks and initialize them
    YAHOO.util.Dom.getElementsByClassName('helpLink', 'a', null,
    				     YAHOO.cc.help.init_help_item);
   
} // init

YAHOO.util.Event.onDOMReady(YAHOO.cc.help.init); 
