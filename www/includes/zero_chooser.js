// CC-Zero
YAHOO.namespace("cc.zero");

YAHOO.cc.zero.show_dedication = function(e) {

    // show the container 
    // (which is hidden @ load to prevent initial flash of content)
    YAHOO.util.Dom.removeClass("waiver_ui_container", "ui_container");

    YAHOO.cc.zero.pnlAssertion.hide();
    YAHOO.cc.zero.pnlDedication.show();
    YAHOO.cc.zero.pnlResults.hide();

    // override the default event handling
    YAHOO.util.Event.preventDefault(e);
    
} // show_dedication


YAHOO.cc.zero.show_assertion = function(e) {
	
    // show the container 
    // (which is hidden @ load to prevent initial flash of content)
    YAHOO.util.Dom.removeClass("assertion_ui_container", "ui_container");

    YAHOO.cc.zero.pnlDedication.hide();
    YAHOO.cc.zero.pnlAssertion.show();
    YAHOO.cc.zero.pnlResults.hide();

    // override the default event handling
    YAHOO.util.Event.preventDefault(e);
    
} // show_assertion

YAHOO.cc.zero.show_results = function(e) {

    // show the container 
    // (which is hidden @ load to prevent initial flash of content)
    YAHOO.util.Dom.removeClass("results_container", "ui_container");

    YAHOO.cc.zero.pnlResults.show();

    // override the default event handling
    YAHOO.util.Event.preventDefault(e);
    
} // show_results

YAHOO.cc.zero.dedication_consent = function() {
    // return true if the user has checked the "consent" checkbox

    return document.getElementById('confirm_dedication').checked;

} // consent

YAHOO.cc.zero.assertion_consent = function() {
    // return true if the user has checked the "consent" checkbox

    return document.getElementById('confirm_assertion').checked;

} // consent

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

YAHOO.cc.zero.update_dedication = function(e) {

    if (YAHOO.cc.zero.dedication_consent()) {
	// post the fields, get the HTML+RDFa

	var dedication_form = document.getElementById('form-waiver');
	YAHOO.util.Connect.setForm(dedication_form);

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

YAHOO.cc.zero.init = function() {

    // init the UI panels
    YAHOO.cc.zero.pnlDedication = new YAHOO.widget.Module("waiver_ui", 
                                                          {visible:false,});
    YAHOO.cc.zero.pnlDedication.render();

    YAHOO.cc.zero.pnlAssertion = new YAHOO.widget.Module("assertion_ui",
                                                         {visible:false,});

    YAHOO.cc.zero.pnlAssertion.render();

    YAHOO.cc.zero.pnlResults = new YAHOO.widget.Module("results",
                                                       {visible:false,});

    YAHOO.cc.zero.pnlResults.render();

    // init the two path buttons
    var pathButtonGroup = new YAHOO.widget.ButtonGroup("pathbuttongroup");
    pathButtonGroup.getButton(0).addListener("click", 
					     YAHOO.cc.zero.show_dedication);
    pathButtonGroup.getButton(1).addListener("click", 
					     YAHOO.cc.zero.show_assertion);

    // initialize the "click-through" buttons
    var waiver_submit = new YAHOO.widget.Button("waiver-submit",
                                                {type:'push'});
    waiver_submit.addListener("click", YAHOO.cc.zero.show_results);
    var assertion_submit = new YAHOO.widget.Button("assertion-submit",
                                                {type:'push'});
    assertion_submit.addListener("click", YAHOO.cc.zero.show_results);
			   
    // add change listeners for the form elements
    YAHOO.util.Event.addListener(
           YAHOO.util.Dom.getElementsByClassName("form-field",
           "input", document.getElementById("form-dedication")),
           "change", YAHOO.cc.zero.update_dedication); 

    YAHOO.util.Event.addListener(
           YAHOO.util.Dom.getElementsByClassName("form-field",
           "input", document.getElementById("form-assertion")),
           "change", YAHOO.cc.zero.update_assertion); 


} // init

// hook for initialization
YAHOO.util.Event.onDOMReady(YAHOO.cc.zero.init);
