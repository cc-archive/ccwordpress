function bannerHtml() {
    var banner = new Array(3); for (i = 0; i < banner.length; ++i) banner[i] = new Array(2);
    banner[0][0] = "&utm_medium=sbanner_1"; banner[0][1] = "Help the world harness the power of Creative Commons.";
    banner[1][0] = "&utm_medium=sbanner_2"; banner[1][1] = "Help the world harness the amazing strength of Creative Commons!";
    banner[2][0] = "&utm_medium=sbanner_3"; banner[2][1] = "Creative Commons, saving the world from failed sharing!";
    banner[3][0] = "&utm_medium=sbanner_3"; banner[3][1] = "Creative Commons is a nonprofit organization. We need your support, donate today!";

	return banner;
}
function thundercats() {
    if ((location.href.match(/http\:(.*)?creativecommons.org\/$/) && !location.href.match(/pport.creativecommons.org\/$/)) || location.href.match(/creativecommons.org\/donate/)) return;

    var i = Math.floor(Math.random() * 4);
    var banners = bannerHtml();

    var d = document.createElement("div");
    var mainContent = document.getElementById("globalWrapper");
    d.setAttribute('style', 'font-size: 22px; font-family: "helvetica neue", arial, sans-serif; line-height:1; text-shadow: 0 1px 0 rgba(255, 255, 255, 0.4); color: #ffff00; padding: 7px 0 2px 0; border-bottom: 1px solid rgb(120, 159, 44); margin-top: -1px; background: #c5deed; background:-webkit-gradient(linear, left top, left bottom, from(#c6e1f2), to(#84bddf)); background:-moz-linear-gradient(center top, #c5deed, #91b6cd); z-index:1000;');
    d.innerHTML = '<a href="https://creativecommons.net/donate?utm_campaign=superhero&utm_source=ccorg' + banners[i][0] + '" style="color:#000; text-decoration:none;"><img src="https://creativecommons.net/sites/default/themes/cc/images/superhero/supercc-banner.png" style="vertical-align:middle; padding-right: 10px;" border="0"/> <strong><span style="">' + banners[i][1] + '</span></strong> &mdash; <em style="color:#c01100;">Donate Now</em></a>';
    mainContent.parentNode.insertBefore(d, mainContent);
}

if (typeof window.addEventListener !== 'undefined') {
    window.addEventListener('load', thundercats, false);
} else {
    window.attachEvent('onload', thundercats);
}



if (typeof YAHOO != "undefined") { 

YAHOO.namespace("cc.site");

// convenience function for creating help tool tips
YAHOO.cc.site.init_help_item = function(help_anchor) { 

    var link_id = help_anchor.id;
    var help_id = 'help_' + link_id;
	
	// Make sure help_id doesn't have the sideitem class
	YAHOO.util.Dom.removeClass(help_id, "sideitem");

    // make sure we have an array to hold the list of panels
    if (!YAHOO.cc.site.help_panels) {
			YAHOO.cc.site.help_panels = new Array();
    }
	
	 /* Align to the bottom right of the last column, 
	    default to bottom left */
	 if (YAHOO.util.Dom.hasClass(help_id, "topright")) {
		var theContext = [help_anchor, 'tr', 'br'];
	 } else {
		var theContext = [help_anchor, 'tl', 'bl'];
	 }
	 
	 if (help_id == "help_international_list") {
		var panelWidth = "180px";
	} else {
		var panelWidth = "280px";
	}
		
    // create the new panel and position it
    var new_panel = new YAHOO.widget.Panel(help_id, 
                            {close: false, title:false, 
			   visible: false, 
			   draggable: false, 
			   width: panelWidth, 
				effect: {effect:YAHOO.widget.ContainerEffect.FADE,duration:0.05},
			   context: theContext
			    } ); 

    var item_idx = YAHOO.cc.site.help_panels.push(new_panel) - 1;

    YAHOO.cc.site.help_panels[item_idx].render();

	// hideTimeout code adapted from http://jqueryfordesigners.com/coda-popup-bubbles/
	var hideDelay = 150;
	var hideTimeout = null;
	
	var showDelay = 500;
	var showTimeout = null;
	
	
   // connect the event handlers
	function showPanel(e) {
		if (hideTimeout) clearTimeout(hideTimeout);
		if (showTimeout) clearTimeout(showTimeout);
		
		showTimeout = setTimeout(function() {
			showTimeout = null;
			
		   YAHOO.cc.site.help_panels[item_idx].show();
		}, showDelay);

	
		YAHOO.util.Event.preventDefault(e);	   
	}
	
	function hidePanel(e) {
		if (hideTimeout) clearTimeout(hideTimeout);
		if (showTimeout) clearTimeout(showTimeout);
						
		hideTimeout = setTimeout(function() {
			hideTimeout = null;
							
		 	YAHOO.cc.site.help_panels[item_idx].hide();
		   
			YAHOO.util.Event.preventDefault(e);			
		}, hideDelay);
						
	}
	
   YAHOO.util.Event.addListener(link_id, "mouseover", showPanel);
	YAHOO.util.Event.addListener(link_id, "mouseout", hidePanel);
	
	YAHOO.util.Event.addListener(help_id, "mouseover", showPanel);
	YAHOO.util.Event.addListener(help_id, "mouseout", hidePanel);
		
} // init_help_text

var searchValue = "Search site";

function wakeSearch(e) {
		var search = document.getElementById("s");

		if (search.value == searchValue) {
			search.value = "";
			YAHOO.util.Dom.replaceClass(search, "inactive", "active");
			document.getElementById("searchsubmit").disabled = "";
		}	
	return false;
}
function resetSearch(e) {
	var search = document.getElementById("s");
	if (search.value == "") {
		search.value = searchValue;
		YAHOO.util.Dom.replaceClass(search, "active", "inactive");
		document.getElementById("searchsubmit").disabled = "disabled";
	}
	
	return false;
}

YAHOO.cc.site.init = function() {
    // initialization for help pop-ups

	YAHOO.util.Dom.getElementsByClassName('helpLink', null, null,
											YAHOO.cc.site.init_help_item);
	
	resetSearch();
	YAHOO.util.Event.addListener("s", "click", wakeSearch);
	YAHOO.util.Event.addListener("s", "blur", resetSearch);


} // init

YAHOO.util.Event.onDOMReady(YAHOO.cc.site.init);

}

