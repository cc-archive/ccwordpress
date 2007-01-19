function orderby(sel) {
  var url = sel.options[sel.selectedIndex].value;
  if (url.indexOf('http') != 0) {
    url = "/worldwide/" + url;
  }
  location.href = url;
}
function swapbutton(buttonurl) {
  //document.getElementById('licensebutton').setAttribute('src',buttonurl);
  codetocopy = document.getElementById('codetocopy').innerHTML;
  newcodetocopy = codetocopy.replace(/src=".*?"/, 'src="'+buttonurl+'"');
  document.getElementById('codetocopy').innerHTML = newcodetocopy;
}
