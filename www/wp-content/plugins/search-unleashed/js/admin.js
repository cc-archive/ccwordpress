var running = false;

function index (offset,type)
{
  if (offset == 0)
  {
    running = true;
    jQuery('#outer').empty ();
    jQuery('#index_comments').fadeOut ();
    jQuery('#index_posts').fadeOut ('normal', function () { jQuery('#outer').fadeIn (); jQuery('#cancel').fadeIn ()});
  }
  
  jQuery('#outer').load (wp_search_base + '?cmd=index&offset=' + offset, { type: type }, function (response, status, text)
  {
    if (jQuery('#offset').attr ('value') != 'END' && running)
      setTimeout (function () {index (jQuery('#offset').attr ('value'), type) }, 0)
    else
    {
      jQuery('#cancel').fadeOut ();
      jQuery('#outer').click (function () { jQuery('#outer').fadeOut ('normal', function () { jQuery('#index_comments').fadeIn (); jQuery('#index_posts').fadeIn ()});});
    }
  }); 
}

function cancel_index ()
{
  running = false;
  jQuery('#outer').fadeOut ('normal', function () { jQuery('#index_posts').fadeIn (); jQuery('#index_comments').fadeIn (); jQuery('#cancel').fadeOut ();});
  return false;
}


function config (item)
{
  jQuery('#module_' + item).load (wp_search_base + '?cmd=edit_module', { id: item });
  return false;
}

function cancel_module (item)
{
  jQuery('#module_' + item).load (wp_search_base + '?cmd=cancel_module', { id: item});
  return false;
}

function toggle_module (item)
{
  if (jQuery('#' + item).attr ('checked'))
    jQuery.post (wp_search_base + '?cmd=module_on', { module: item});
  else
    jQuery.post (wp_search_base + '?cmd=module_off', { module: item});
  
  jQuery('#module_' + item).toggleClass ('disabled')
}

function highlight (index,item)
{
  jQuery('#color_' + index).css ('backgroundColor', '#' + item.value);
}