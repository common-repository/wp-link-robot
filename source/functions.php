<?php
function getRequest() {

  if (!empty($_SERVER['REQUEST_URI'])) {
      return $_SERVER['REQUEST_URI'];

  } else if (!empty($_SERVER['PHP_SELF'])) {
      if (!empty($_SERVER['QUERY_STRING'])) {
          return $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING'];
      }
      return $_SERVER['PHP_SELF'];

  } else if (!empty($_SERVER['SCRIPT_NAME'])) {
      if (!empty($_SERVER['QUERY_STRING'])) {
          return $_SERVER['SCRIPT_NAME'] .'?'. $_SERVER['QUERY_STRING'];
      }
      return $_SERVER['SCRIPT_NAME'];

  } else if (!empty($_SERVER['URL'])) {
      if (!empty($_SERVER['QUERY_STRING'])) {
          return $_SERVER['URL'] .'?'. $_SERVER['QUERY_STRING'];
      }
      return $_SERVER['URL'];

  } else {
     // notify('Warning: Could not find any of these web server variables: $REQUEST_URI, $PHP_SELF, $SCRIPT_NAME or $URL');
      return false;
  }
}


function getPartRequest($sparameter){
  $sRequest = getRequest();
  $sparameter = "&" . $sparameter . "=";
  $iAmpPos = strpos($sRequest, '&', strpos($sRequest, $sparameter) + 1);
  if ($iAmpPos === false) return $sRequest;
    
  return substr($sRequest, 0, $iAmpPos);  
}
function getPartBeforeRequest($sparameter){
  $sRequest = getRequest();
  $sparameter = "&" . $sparameter . "=";
  if (stripos($sRequest,$sparameter) !== false) return $request = substr($sRequest, 0, stripos($sRequest,$sparameter));
  return $sRequest; 
}
function eblex_executequery($query)
{
    global $wpdb;
    echo $wpdb->query($query);
}



 
 
  /*function LinkEdit($id, $edit) {
 	// Do some minor cleanup on the asin
 	global $wpdb;
	$book= $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}eblex_links WHERE id = '{$id}'" );
	LinkHTML( $book, $edit );
	die();
}       */
?>
