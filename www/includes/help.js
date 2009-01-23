YAHOO.namespace("cc.help");

// convenience function for creating help tool tips
YAHOO.cc.help.init_help_item = function(help_anchor) { 

    var link_id = help_anchor.id;
    var help_id = 'help_' + link_id;

    // make sure we have an array to hold the list of panels
    if (!YAHOO.cc.help.help_panels) {
	YAHOO.cc.help.help_panels = new Array();
    }

    // create the new panel and position it
    var new_panel = new YAHOO.widget.Panel(help_id, 
                            {close: true, 
			     visible: false, 
			     draggable: false, 
			     width:'350px',
			     context:[help_anchor.id,'bl','tl']
			    } ); 

    var item_idx = YAHOO.cc.help.help_panels.push(new_panel) - 1;

    // remove the initial class
    YAHOO.util.Dom.removeClass(help_id, "help_panel");

    YAHOO.cc.help.help_panels[item_idx].render();

    // connect the event handlers
    var on_click = function(e) {
	YAHOO.cc.help.help_panels[item_idx].show();
	e.preventDefault();
    }; // on_click

    YAHOO.util.Event.addListener(link_id, "click", on_click);


} // init_help_text

YAHOO.cc.help.init = function() {
    // initialization for help pop-ups

    YAHOO.util.Dom.getElementsByClassName('helpLink', 'a', 'body',
				     YAHOO.cc.help.init_help_item);
   
} // init

YAHOO.util.Event.onDOMReady(YAHOO.cc.help.init); 
