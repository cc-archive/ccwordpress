// CC-Zero
YAHOO.namespace("cc.zero");

YAHOO.cc.zero._show_panel = function(e, index) {

    e.preventDefault();

    // push the current item onto the stack
    YAHOO.cc.zero.chooser_path.push(this.getLayout().activeItem);

    // show the specified item
    this.getLayout().setActiveItem(index);

    this.getBottomToolbar().items.get('move-back').setDisabled(false);

} // _show_panel

YAHOO.cc.zero._back = function() {

    // pop the last place off the stack
    last = YAHOO.cc.zero.chooser_path.pop();

    // show the last page
    YAHOO.cc.zero.chooser.getLayout().setActiveItem(last);

    // see if we need to disable the back button
    if (YAHOO.cc.zero.chooser_path.length == 0) {
	YAHOO.cc.zero.chooser.getBottomToolbar().items.get('move-back').setDisabled(true);
    }

} // _back

YAHOO.cc.zero._enable_button = function(e, btn_id) {

    Ext.get(btn_id).dom.disabled = !(e.target.checked);

} // _enable_button

YAHOO.cc.zero.on_show_confirmation = function(zerotype) {

   
} // on_show_confirmation

YAHOO.cc.zero.update_assertion = function(e) {

    if (YAHOO.cc.zero.assertion_consent()) {
	// post the fields, get the HTML+RDFa

	var assertion_form = document.getElementById('form-assertion');
	YAHOO.util.Connect.setForm(assertion_form);

	var callback = {
	    success : function(o) {
		document.getElementById('results-html').value = o.responseText;
		document.getElementById('results-preview').innerHTML = o.responseText;
	    },

	    failure : function(o) { alert(o.statusText) },
	};

	var conn = YAHOO.util.Connect.asyncRequest('GET', 
					     '/license/get-rdfa', callback);

    }

} // update_dedication

YAHOO.cc.zero.on_show_results = function(e) {
    
    var mgr = Ext.get("results-preview").getUpdater();

    mgr.formUpdate("form-waiver", "/license/get-rdfa",
		   false, function (el, success, response) {
		       if (success) {
		   Ext.get("results-html").dom.value = response.responseText;
		       }
		   });

} // on_show_results

YAHOO.cc.zero.init = function() {

    YAHOO.cc.zero.chooser_path = new Array();

    YAHOO.cc.zero.chooser = new Ext.Panel({
        layout:'card',
	activeItem: 0, 
	renderTo:'zero-wizard',
	autoWidth:true,
	height:350,
	border:false,
	defaults: {
            // applied to each contained panel
	    border:false,
	    bodyStyle:"padding:10px",
	},

	bbar: [
        {
            id: 'move-back',
            text: '<< Back',
            handler: YAHOO.cc.zero._back,
            disabled: true
        },
	       ],

	// the panels (or "cards") within the layout
	items: [{contentEl:'page-welcome'},
	        {contentEl:'page-waiver'},
                {contentEl:'page-assertion'},
	        {contentEl:'page-confirmation'},
                {contentEl:'page-results'},
	       ]
	});


    // init the path buttons
    Ext.get("do-waiver").on("click", 
	     YAHOO.cc.zero._show_panel.createDelegate(YAHOO.cc.zero.chooser, 
						      [1], 1));
    Ext.get("do-assertion").on("click", 
	     YAHOO.cc.zero._show_panel.createDelegate(YAHOO.cc.zero.chooser, 
						      [2], 1));

    // forward-movement handlers
    Ext.get("assertion-submit").on("click",
       YAHOO.cc.zero._show_panel.createDelegate(YAHOO.cc.zero.chooser, 
						[3], 1));
    Ext.get("assertion-submit").dom.disabled = true;

    Ext.get("waiver-submit").on("click",
       YAHOO.cc.zero._show_panel.createDelegate(YAHOO.cc.zero.chooser, 
						[3], 1));
    Ext.get("waiver-submit").dom.disabled = true;

    Ext.get("confirm-submit").on("click",
       YAHOO.cc.zero._show_panel.createDelegate(YAHOO.cc.zero.chooser, 
						[4], 1));

    // add listeners for the "agreement" checkboxes
    Ext.get("confirm_waiver").on("click",
				 YAHOO.cc.zero._enable_button.createDelegate(this, ["waiver-submit"], 1));

    Ext.get("confirm_assertion").on("click",
				    YAHOO.cc.zero._enable_button.createDelegate(this, ["assertion-submit"], 1));


    // add panel-show listeners
    YAHOO.cc.zero.chooser.items.get(3).on("show", 
					  YAHOO.cc.zero.on_show_confirmation);
    YAHOO.cc.zero.chooser.items.get(4).on("show", 
					  YAHOO.cc.zero.on_show_results);

				     
} // init

// hook for initialization
YAHOO.util.Event.onDOMReady(YAHOO.cc.zero.init);
