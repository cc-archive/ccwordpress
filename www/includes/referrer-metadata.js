function makeRequest(url) {
    var http_request = false;

    if (window.XMLHttpRequest) { // Mozilla, Safari, ...
        http_request = new XMLHttpRequest();
        if (http_request.overrideMimeType) {
            http_request.overrideMimeType('text/plain');
            // See note below about this line
        }
    } else if (window.ActiveXObject) { // IE
        try {
            http_request = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                http_request = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {}
        }
    }

    if (!http_request) {
        //alert('Giving up :( Cannot create an XMLHTTP instance');
        return false;
    }
    http_request.onreadystatechange = function() {
        processResponse(http_request);
    };
    http_request.open('GET', url, true);
    http_request.send(null);
}

function processResponse(http_request) {
    if (http_request.readyState == 4) {
        if (http_request.status == 200) {
            injectReferrerMetadata(http_request.responseText);
        } else {
            //alert('There was a problem with the request.');
        }
    }
}

function injectReferrerMetadata(response) {

    metadata = eval(response);

    var attributionName = metadata.attributionName;
    var attributionUrl = metadata.attributionUrl;

    if (attributionName && attributionUrl) {
	document.getElementById('attribution-container').innerHTML = "You must attribute this work to <strong><a href='" + attributionUrl + "'>" + attributionName + "</a></strong> (with link). <a onclick=\"window.open('http://mirrors.creativecommons.org/demo3/by-popup.html', 'attribution_help', 'width=375,height=300,scrollbars=yes,resizable=yes,toolbar=no,directories=no,location=yes,menubar=no,status=yes');return false;\" href=''>Find out how.</a>";
    }

    var morePermissionsURL = metadata.morePermissions;
    var morePermissionsDomain = metadata.morePermissionsDomain;

    if (morePermissionsURL && morePermissionsDomain) {
	document.getElementById('more-container').innerHTML = "<li class='license more'><strong>Permissions beyond</strong> the scope of this public license are available at <strong><a href='" + morePermissionsURL + "'>" + morePermissionsDomain + "</a></strong>.</li>";
    }

} // injectReferrerMetadata

function referrerMetadata() {
    r = document.referrer;
    if (r.match('^http://')) makeRequest('/apps/scrape?url='+r);
}

