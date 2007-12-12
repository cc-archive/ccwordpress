      // CC-Zero
      YAHOO.namespace("cc.zero");

      YAHOO.cc.zero.show_dedication = function(e) {

        YAHOO.cc.zero.pnlAssertion.hide();
        YAHOO.cc.zero.pnlDedication.show();

      } // show_dedication


      YAHOO.cc.zero.show_assertion = function(e) {

        YAHOO.cc.zero.pnlDedication.hide();
        YAHOO.cc.zero.pnlAssertion.show();

      } // show_assertion


      function conn_test(e) {

        document.getElementById('dedication-html').value = 'foo';

      } // conn_test

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
                    document.getElementById('assertion-html').value =
                o.responseText;
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

            var dedication_form = document.getElementById('form-dedication');
            YAHOO.util.Connect.setForm(dedication_form);

            var callback = {
                success : function(o) {
                    document.getElementById('dedication-html').value =
                o.responseText;
                },

                failure : function(o) { alert(o.statusText) },
                };

            var conn = YAHOO.util.Connect.asyncRequest('GET', 
                       '/license/get-rdfa', callback);

         }

      } // update_dedication

      YAHOO.cc.zero.init = function() {

        // init the two UI panels
        YAHOO.cc.zero.pnlDedication = new YAHOO.widget.Module(
                           "dedication_ui", 
                            {visible:false,} 
                           );
        YAHOO.cc.zero.pnlDedication.render();

        YAHOO.cc.zero.pnlAssertion = new YAHOO.widget.Module(
                           "assertion_ui", 
                            {visible:false,} 
                           );

        YAHOO.cc.zero.pnlAssertion.render();

        // init the two path buttons
        var pathButtonGroup = new YAHOO.widget.ButtonGroup("pathbuttongroup");
        pathButtonGroup.getButton(0).addListener("click", 
            YAHOO.cc.zero.show_dedication);
        pathButtonGroup.getButton(1).addListener("click", 
            YAHOO.cc.zero.show_assertion);

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

      YAHOO.util.Event.onDOMReady(YAHOO.cc.zero.init);
