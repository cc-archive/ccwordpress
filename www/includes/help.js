YAHOO.namespace("cc.help");

// convenience function for creating help tool tips
YAHOO.cc.help.init_help_item = function(help_anchor) { // link_id, help_id) {

    var link_id = help_anchor.id;
    var help_id = 'help_' + link_id;

    // make sure we have an array to hold the list of panels
    if (!YAHOO.cc.help.help_panels) {
	YAHOO.cc.help.help_panels = new Array();
    }

    // create the new panel and position it
    var new_panel = new YAHOO.widget.Panel(help_id, 
{close: false, visible: false, draggable: false, width:200,
 effect:{effect:YAHOO.widget.ContainerEffect.FADE,duration:0.35} } ); 

    var link_xy = YAHOO.util.Dom.getXY(link_id);
    new_panel.cfg.setProperty('xy',[link_xy[0] + 15, link_xy[1]] );
    var item_idx = YAHOO.cc.help.help_panels.push(new_panel) - 1;

    YAHOO.cc.help.help_panels[item_idx].render();

    // connect the event handlers
    YAHOO.util.Event.addListener(link_id, "mouseover", 
				 function(e) {YAHOO.cc.help.help_panels[item_idx].show();});
    YAHOO.util.Event.addListener(link_id, "mouseout", 
				function(e) {window.setTimeout("YAHOO.cc.help.help_panels[" + item_idx + "].hide();", 1000);});

    // disable clicks
    YAHOO.util.Event.addListener(link_id, 'click', function(e){YAHOO.util.Event.preventDefault(e);});

} // init_help_text

YAHOO.cc.help.init = function() {
    // initialization for help roll-overs (tooltips on steroids)

    YAHOO.util.Dom.getElementsByClassName('helpLink', 'a', 'body',
				     YAHOO.cc.help.init_help_item);
   
} // init

YAHOO.util.Event.onDOMReady(YAHOO.cc.help.init); 