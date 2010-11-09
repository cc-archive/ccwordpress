function bannerHtml() {
    var banner = new Array(4); for (i = 0; i < banner.length; ++i) banner[i] = new Array(2);
    banner[0][0] = "&utm_medium=sbanner_1"; banner[0][1] = "Help the world harness the power of Creative Commons.";
    banner[1][0] = "&utm_medium=sbanner_2"; banner[1][1] = "Help the world harness the amazing strength of Creative Commons!";
    banner[2][0] = "&utm_medium=sbanner_3"; banner[2][1] = "Creative Commons, saving the world from failed sharing!";
    banner[3][0] = "&utm_medium=sbanner_4"; banner[3][1] = "Creative Commons is a nonprofit organization. We need your support, donate today!";

    return banner;
}
function thundercats() {
    if (location.href.match(/^http\:(.*)?creativecommons.org\/choose/)) return;
    var i = Math.floor(Math.random() * 5);
    var banners = bannerHtml();

    var d = document.createElement("div");
    var mainContent = document.getElementById("deed");
    d.setAttribute('style', 'font-size: 22px; font-family: "helvetica neue", arial, sans-serif; line-height:1; text-shadow: 0 1px 0 rgba(255, 255, 255, 0.4); color: #ffff00; padding: 7px 0 2px 0; border-bottom: 1px solid rgb(120, 159, 44); margin-top: -1px; background: #c5deed; background:-webkit-gradient(linear, left top, left bottom, from(#c6e1f2), to(#84bddf)); background:-moz-linear-gradient(center top, #c5deed, #91b6cd); z-index:1000;');
	if (i < 4) {
    	d.innerHTML = '<a href="https://creativecommons.net/donate?utm_campaign=superhero&utm_source=deed' + banners[i][0] + '" style="color:#000; text-decoration:none;"><img src="https://creativecommons.net/sites/default/themes/cc/images/superhero/supercc-banner.png" style="vertical-align:middle; padding-right: 10px;" border="0"/> <strong><span style="">' + banners[i][1] + '</span></strong> &mdash; <em style="color:#c01100;">Donate Now</em></a>';
    	mainContent.parentNode.insertBefore(d, mainContent);
	} else {
		var utm="?utm_campaign=superhero&utm_source=deed&utm_medium=sbanner_progress";
		d.innerHTML = '<div id="campaign" style="vertical-align:middle; color:#111; background:transparent; height: auto;"><div class="progress" style="width: 300px; vertical-align:middle; display:inline-block; margin: 0;" onclick="window.location=\'https://creativecommons.net/donate'+utm+'\'"><div class="inner"><span>&nbsp;</span></div></div> <span style="padding-left:10px; vertical-align:middle;"><span id="campaignRaised">&nbsp;</span> &mdash; <a href="https://creativecommons.net/donate'+utm+'"><em style="color:#c01100">Donate Now</em></a></span></div>';
		d.style.display = "none";
		mainContent.parentNode.insertBefore(d, mainContent);
		
		var c = document.createElement("link");
		c.setAttribute("type", "text/css");
		c.setAttribute("rel", "stylesheet");
		c.setAttribute("href", "/wp-content/themes/cc5/support.css");
		document.getElementsByTagName("head")[0].appendChild(c);

		var c = document.createElement("link");
		c.setAttribute("type", "text/css");
		c.setAttribute("rel", "stylesheet");
		c.setAttribute("href", "/includes/total.css");
		document.getElementsByTagName("head")[0].appendChild(c);

		var r = new XMLHttpRequest();
		r.open('GET', '/includes/total.txt', true);
		r.onreadystatechange = function(e) { 
			if (r.readyState == 4) { 
				document.getElementById("campaignRaised").innerHTML = r.responseText + ' <small>of $550,000</small> raised'; 
				d.style.display = "block"; 
			} 
		};
		r.send(null);
	}
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
