<?php      
function show_pr_new($row) { 
 $html = "<div style=\"font-size: 85%\">
	PR: <a href=\"#\" id=\"getPR" . $row->id . "\" onclick=\"
		obj = document.getElementById('getPR" . $row->id . "');
		getPR('" . $row->url . "',obj,'" . $row->id . "');
		return	false;
		\">";
  if($row->seo_pr=='') $html .= '?'; else $html .= $row->seo_pr; 
  $html .= "</a><br />";
	$html .= "<a href=\"http://www.google.com/search?q=cache%3A" . $row->url . "\" target=\"_blank\">GCache:</a> <a href=\"#\" id=\"getGCache" . $row->id ."\" onclick=\"
		obj = document.getElementById('getGCache" . $row->id ."');
		getGCache('" . urlencode($row->url)."',obj,'" . $row->id."');
		return	false;
		\">";
  if($row->seo_gcache=='') $html .= '?'; else $html .= $row->seo_gcache;
   $html .= "</a><br />
	<a href=\"http://www.alexa.com/data/details/main?q=".preg_replace('/\/.*/','',preg_replace('/.*:\/\//','',$row->url))."
    &url=".preg_replace('/\/.*/','',preg_replace('/.*:\/\//','',$row->url))."\" target=\"_blank\">Alexa:</a> <a href=\"#\" id=\"getAlexa".$row->id."\" onclick=\"
		obj = document.getElementById('getAlexa".$row->id."');
		getAlexa('".$row->url."',obj,'".$row->id."');
		return	false;
		\">";
    if($row->seo_alexa=='') $html .= '?'; else $html .= $row->seo_alexa;
     $html .= "</a><br />
	<a href=\"http://www.google.com/search?q=site%3A".preg_replace('/\/.*/','',preg_replace('/.*:\/\//','',$row->url))."
    &url=".preg_replace('/\/.*/','',preg_replace('/.*:\/\//','',$row->url))."\" target=\"_blank\">Cached:</a> <a href=\"#\" id=\"getGCached".$row->id."\" onclick=\"
		obj = document.getElementById('getGCached".$row->id."');
		getGCached('".$row->url."',obj,'".$row->id."');
		return	false;
		\">";
    if($row->seo_cached=='') $html .= '?'; else  $html .= $row->seo_cached;
     $html .= "</a></div>&nbsp;
	<a href=\"#\" id=\"getAll".$row->id."\" onclick=\"
		obj = document.getElementById('getPR".$row->id."');
		getPR('".$row->url."',obj,'".$row->id."');
		obj = document.getElementById('getGCache".$row->id."');
		getGCache('".urlencode($row->url)."',obj,'".$row->id."');
		obj = document.getElementById('getAlexa".$row->id."');
		getAlexa('".$row->url."',obj,'".$row->id."');
		obj = document.getElementById('getGCached".$row->id."');
		getGCached('".$row->url."',obj,'".$row->id."');
		return	false;
		\">[Refresh]</a>
	<br />";
return $html;
} 
function check_email($str)
{
    if (ereg("^.+@.+\..+$", $str))
        return 1;
    else
        return 0;
}
function LinkHTML ($link, $edit = 'false') {
		global $wpdb;
		$Cat = $wpdb->get_var("SELECT `title` FROM `{$wpdb->prefix}eblex_categories` WHERE `id` = \"$link->category\"");
		if( $link->found == '404' ){
       $bFound = "Error";
       $classlink = "linkbox_red";
    }
		elseif(!$link->found){
  		$bFound = "NO";
		  $classlink = "linkbox_yellow";
    }
    else{  
		  $bFound = "YES";
		  $classlink = "linkbox_green";
    }
    $sTrashAndDelete = ($link->status != 2) ? "[Trash]" : "[Delete]";
    $sCheckAndRestore = ($link->status != 2) ? "[Check]" : "[Restore]";
    $desc = explode(' ',strip_tags($link->description));
    $description = "";
    $k = 0;
    for ($k=0;$k<min(12,count($desc)); $k=$k+1){
      $description = $description . $desc[$k] . ' ';
    }
    $description .= '...';
//      var_dump($desc[0]);die;
    if( $edit == 'false' ) {
//			$aLinks = $wpdb->get_var( "SELECT display_name FROM `$eblex_links`");
			$html = <<<HTML
			<tr id="link-{$link->id}">
				<td><span class="{$classlink}"><a href="{$link->url}" class="plink" target="_blank">	{$link->title}</a></span><br />
        <span class="purl">{$link->url}</span></td>
				<td>{$Cat}</td>
				<td>{$description}</td>
				<td>{$link->administratorcomment}</td>
				<td>{$link->reciprocalurl}</td>
				<td>{$link->zindex}</td>
        <td><a href="javascript:void(0);" onclick="lnkrob_checkLink('{$link->id}')"><span class="check-{$bFound}" id="checking-{$link->id}">{$bFound}</span></a></td>				
				<td><a href="javascript:void(0);" onclick="lnkrob_linkToggleEdit('{$link->id}',true) ">[Edit]</a><br />
				<a href="javascript:void(0);" onclick="lnkrob_removeLink('{$link->id}')">{$sTrashAndDelete}</a><br />
				<a href="javascript:void(0);" onclick="lnkrob_checkLink('{$link->id}')">{$sCheckAndRestore}</a></td>
			</tr>
HTML;
		}
		else {
      $sOptions = SelectCategory($link->category);
			$html = <<<HTML
			<tr id="link-{$link->id}">
			   <td></td>
				<td colspan="9">
				<table id="link_details-{$link->id}" class="robotnewlink">
 				<tr><td colspan="2"><strong>Editing {$link->title}<strong></td></tr>
				<tr><td>Title:</td><td><input name="link-title-{$link->id}" type="text" value="{$link->title}" size="60"/</td></tr>
				<tr><td>Url:</td><td><input name="link-url-{$link->id}" type="text" value="{$link->url}"  size="60"/></td></tr>
				<tr><td>Category:</td><td><select name="link-category-{$link->id}">
					{$sOptions}
					</select></td></tr>
				<tr><td>Description:</td><td><input name="link-description-{$link->id}" type="text" value="{$link->description}" size="60" /></td></tr>
				<tr><td>E-mail:</td><td><input name="link-email-{$link->id}" type="text" value="{$link->email}" size="60" /></td></tr>
				<tr><td>Reciprocal Url:</td><td><input name="link-reciprocalurl-{$link->id}" type="text" value="{$link->reciprocalurl}" size="60" /></td></tr>
				<tr><td>Notes:</td><td><input name="link-administratorcomment-{$link->id}" type="text" value="{$link->administratorcomment}" size="60" /></td></tr>
				<tr><td>Priority index:</td><td><input name="link-priorityindex-{$link->id}" type="text" value="{$link->zindex}" size="20" /></td></tr>
				<!--tr><td>Status:</td><td><input name="link-status-{$link->id}" type="text" value="{$link->status}" size="20" /></td></tr-->
				<tr><td></td><td><a href="javascript:void(0);" onclick="lnkrob_saveLinkInfo('{$link->id}') ">[Save]</a> | <a href="javascript:void(0);" onclick="lnkrob_linkToggleEdit('{$link->id}', false) ">[Cancel]</a></td></tr>
				<table>
				</td>
			</tr>
HTML;
		}
		echo $html;
	}
function InboxLinkHTML ($link, $edit = 'false') {
		global $wpdb;
		$Cat = $wpdb->get_var("SELECT `title` FROM `{$wpdb->prefix}eblex_categories` WHERE `id` = \"$link->category\"");
    $sTrashAndDelete = ($link->status != 2) ? "[Trash]" : "[Delete]";
    $sCheckAndRestore = ($link->status != 2) ? "[Check]" : "[Restore]";
    if( $edit == 'false' ) {
      $date = date("H:i:s j.n.Y",$link->time);
			$html = <<<HTML
			<tr id="link-{$link->id}">
				<td><span class="{$classlink}"><a href="{$link->url}" class="plink" target="_blank">	{$link->title}</a></span><br />
        <span class="purl">{$link->url}</span><br />
        <strong>Submitted at: </strong>{$date}<br />
        <strong>In category: </strong>{$Cat}
        </td>
				<td>$link->description</td>
				<td><a href="javascript:void(0);" onclick="lnkrob_approveLink('{$link->id}') ">[Approve]</a><br />
            <a href="javascript:void(0);" onclick="lnkrob_linkToggleEditInbox('{$link->id}',true) ">[Edit]</a><br />
            <a href="javascript:void(0);" onclick="lnkrob_rejectLink('{$link->id}') ">[Reject]</a>
        </td>
				<td><a href="{$link->url}" target="_blank">[Visit website]</a><br />
            <a href="{$link->reciprocalurl}" target="_blank">[Visit reciprocal link page]</a>
        </td>
			</tr>
HTML;
		}
		else {
      $sOptions = SelectCategory($link->category);
			$html = <<<HTML
			<tr id="link-{$link->id}">
			   <td></td>
				<td colspan="9">
				<table id="link_details-{$link->id}" class="robotnewlink">
 				<tr><td colspan="2"><strong>Editing {$link->title}<strong></td></tr>
				<tr><td>Title:</td><td><input name="link-title-{$link->id}" type="text" value="{$link->title}" size="60"/</td></tr>
				<tr><td>Url:</td><td><input name="link-url-{$link->id}" type="text" value="{$link->url}"  size="60"/></td></tr>
				<tr><td>Category:</td><td><select name="link-category-{$link->id}">
					{$sOptions}
					</select></td></tr>
				<tr><td>Description:</td><td><textarea name="link-description-{$link->id}" cols="50" rows="4">{$link->description}</textarea></td></tr>
				<tr><td>E-mail:</td><td><input name="link-email-{$link->id}" type="text" value="{$link->email}" size="60" /></td></tr>
				<tr><td>Reciprocal Url:</td><td><input name="link-reciprocalurl-{$link->id}" type="text" value="{$link->reciprocalurl}" size="60" /></td></tr>
				<tr><td>Notes:</td><td><input name="link-administratorcomment-{$link->id}" type="text" value="{$link->administratorcomment}" size="60" /></td></tr>
				<tr><td>Priority index:</td><td><input name="link-priorityindex-{$link->id}" type="text" value="{$link->zindex}" size="20" /></td></tr>
				<tr><td>Status:</td><td><input name="link-status-{$link->id}" type="text" value="{$link->status}" size="20" /></td></tr>
				<tr><td></td><td><a href="javascript:void(0);" onclick="lnkrob_saveLinkInfoInbox('{$link->id}') ">[Save]</a> | <a href="javascript:void(0);" onclick="lnkrob_linkToggleEditInbox('{$link->id}', false) ">[Cancel]</a></td></tr>
				<table>
				</td>
			</tr>
HTML;
		}
		echo $html;
	}	
function CleanLinkHTML ($link, $count, $edit = 'false') {
		global $wpdb;
		$Cat = $wpdb->get_var("SELECT `title` FROM `{$wpdb->prefix}eblex_categories` WHERE `id` = \"$link->category\"");
		$bFound = $link->found ? "YES":"NO";
    $sTrashAndDelete = ($link->status != 2) ? "[Trash]" : "[Delete]";
    $sCheckAndRestore = ($link->status != 2) ? "[Check]" : "[Restore]";
    if (($count%2) == 1) {$class = "linkbox2";} else {$class = "linkbox1";}
    $html = "";
    if($edit == 'false' ){
    $pr = show_pr_new($link);
    $html = <<<HTML
     <tr class= "{$class}" id="del{$count}">
  			  <td><input type="checkbox" name="deletecheck{$count}" value="{$link->id}"></td>
  			  <td><input name="id{$count}" id="id{$count}" type="hidden" value="" />
  			  <span><a href="{$link->url}" class="plink" target="_blank">	{$link->title}</a></span><br />
		      <span class="purl">{$link->url}</span><br />
          <span id="flag-{$link->id}"><a href="javascript:void(0);" onclick="lnkrob_linkCleanShowFlag('{$link->id}') ">[Show notes]</a></span>
          </td>
		      <td>{$pr}</td>
		      <td><a href="{$link->reciprocalurl}">{$link->reciprocalurl}</a></td>
		      <td><a href="javascript:void(0);" onclick="lnkrob_checkBackLink('{$link->id}', '{$count}')"><span class="check" id="checking-{$link->id}">Check</span></a>
          </td>
		      <td>&nbsp;</td>
		      <td>
		      <a href="javascript:void(0);" onclick="lnkrob_linkCleanToggleEdit('{$link->id}', '{$count}',true) ">[Edit]</a><br />
		      <a href="javascript:void(0);" onclick="lnkrob_removeBackLink('{$link->id}', '{$count}')">[Trash]</a><br />
		      </td>
  			</tr>
HTML;
    }
    else{
      $sOptions = SelectCategory($link->category);
      $html = <<<HTML
           <tr id="del{$count}">
        			  <td>&nbsp;</td>
  			  <td colspan="6">
	  				<table id="link_details-{$link->id}" class="robotnewlink">
	  				<tr><td colspan="2"><strong>Editing {$link->title}<strong></td></tr>
            <tr><td>Title:</td><td><input name="link-title-{$link->id}" type="text" value="{$link->title}" size="60"/></td></tr>
            <tr><td>Url:</td><td><input name="link-url-{$link->id}" type="text" value="{$link->url}" size="60"/></td></tr>
            <tr><td>Category:</td><td><select name="link-category-{$link->id}">
  					{$sOptions}
  					</select></td></tr>
            <tr><td>Description:</td><td><input name="link-description-{$link->id}" type="text" value="{$link->description}" size="60"/></td></tr>
            <tr><td>E-mail:</td><td><input name="link-email-{$link->id}" type="text" value="{$link->email}" size="60"/></td></tr>
            <tr><td>Reciprocal Url:</td><td><input name="link-reciprocalurl-{$link->id}" type="text" value="{$link->reciprocalurl}" size="60"/></td></tr>
            <tr><td>Note</td><td><input name="link-administratorcomment-{$link->id}" type="text" value="{$link->administratorcomment}" size="60"/></td></tr>
            <tr><td>Priority index:</td><td><input name="link-priorityindex-{$link->id}" type="text" value="{$link->zindex}" /></td></tr>
            <tr><td>Status:</td><td><input name="link-status-{$link->id}" type="text" value="{$link->status}" /></td></tr>
            <tr><td></td><td><a href="javascript:void(0);" onclick="lnkrob_saveCleanLinkInfo('{$link->id}', '{$count}') ">[Save]</a> | 
            <a href="javascript:void(0);" onclick="lnkrob_linkCleanToggleEdit('{$link->id}', '{$count}', false) ">[Cancel]</a></td></tr>
            <table>
  				</td>
  			</tr>
HTML;
    }
		echo $html;
	}
function SelectCategory ($sSelValue){
  $sOut = "";
  global $wpdb;
//  var_dump($sSelValue);
  $aaCategories = $wpdb->get_results( "SELECT id,title FROM `{$wpdb->prefix}eblex_categories` WHERE parent = \"\" ORDER BY `zindex` DESC,`title` ASC" );
  foreach($aaCategories as $aCategory){
    $sAppender1 = ($aCategory->id == $sSelValue) ?  'selected="selected"' : "";
    $sOut .= "<option value=\"{$aCategory->id}\" $sAppender1>{$aCategory->title}</option>\n";
    if (!empty($aCategory->id)){
         $aaSubCategories = $wpdb->get_results( "SELECT id,title FROM `{$wpdb->prefix}eblex_categories` WHERE parent = \"{$aCategory->id}\" ORDER BY `zindex` DESC,`title` ASC" );
      foreach($aaSubCategories as $aSubcategory){
         $sAppender2 = ($aSubcategory->id == $sSelValue) ?  'selected="selected"' : "";
         $sOut .= "<option value=\"{$aSubcategory->id}\" $sAppender2>&nbsp;&nbsp;&nbsp;{$aSubcategory->title}</option>\n";
      }
    }    
  }
  return $sOut;
}
function ShowFlags($link, $show){
  if($show==false){
  $html = <<<HTML
          <span id="flag-{$link->id}"><a href="javascript:void(0);" onclick="lnkrob_linkCleanShowFlag('{$link->id}') ">[Show notes]</a></span>
HTML;
  }
  else{
  $html = <<<HTML
           <span id="flag-{$link->id}">
            <input name="newflags-{$link->id}" type="text" value="{$link->administratorcomment}" size="40"><br />
            <a href="javascript:void(0);" onclick="lnkrob_linkCleanAddFlag('{$link->id}') ">[Add notes]</a>
            </span>
HTML;
  }
		echo $html;
}
function NewLinkInterface($nIdCat){
  global $wpdb;
  if ($nIdCat == "-1" or $nIdCat == "2") $nIdCat = "0";
  $sCatTitle =  $wpdb->get_var("SELECT `title` FROM `{$wpdb->prefix}eblex_categories` WHERE `id` = \"$nIdCat\"");
  $sOptions = SelectCategory($link->category);
  $html = <<<HTML
  <table id="link_details-new" class="robotnewlink">
	<tr><td colspan="2"><strong>Add new link: <strong></td></tr>
  <tr><td>Title:</td><td><input name="link-title-new" type="text"  size="60"/></td></tr>
  <tr><td>Url:</td><td><input name="link-url-new" type="text"  size="60"/></td></tr>
  <tr><td>Category:</td><td><select name="link-category-{$link->id}">
					{$sOptions}
					</select></td></tr>
  <tr><td>Description:</td><td><input name="link-description-new" type="text"  size="60"/></td></tr>
  <tr><td>E-mail:</td><td><input name="link-email-new" type="text"  size="60"/></td></tr>
  <tr><td>Reciprocal Url:</td><td><input name="link-reciprocalurl-new" type="text"  size="60"/></td></tr>
  <tr><td>Note:</td><td><input name="link-administratorcomment-new" type="text"  size="60"/></td></tr>
  <tr><td>Priority index:</td><td><input name="link-priorityindex-new" type="text" value="0" /></td></tr>
  <tr><td>Status:</td><td><input name="link-status-new" type="text" /></td></tr>
  <tr><td></td><td><span id="AddErrorMessage">&nbsp;</span></td></tr>
  <tr><td></td><td><a href="javascript:void(0);" onclick="lnkrob_addNewLinkInfo('new') ">[Add]</a> | <a href="javascript:void(0);" onclick="lnkrob_linkToggleEdit('new', false) ">[Cancel]</a></td></tr>
  </table>
HTML;
  echo $html;
}
function isValidURL($url)
{
   return preg_match('/^http(s)?:\/\/[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(\/.*)?$/i', $url);
}
function process_url_fv($sRecDirUrl, $sRecUrl ) {
  if (!strlen($sRecDirUrl = trim($sRecDirUrl))) return 0; 
  if ((stripos($sRecDirUrl,'http://') === FALSE) && (stripos($sRecDirUrl,'https://') === FALSE)) $sRecDirUrl = 'http://'.$sRecDirUrl;
  $url =  @parse_url($sRecDirUrl);
//    $url =  parse_url($sRecDirUrl);
  $sRecDirUrl = str_replace(' ','%20',$sRecDirUrl);
  $sRecDirUrl = str_replace($url['scheme'] . "://" . $url['host'], "", $sRecDirUrl);
	$randomuseragent[0] = "Firefox/1.0 (Windows; U; Win98; en-US; Localization; rv:1.4) Gecko/20030624 Netscape/7.1 (ax)";
	$randomuseragent[1] = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.7) Gecko/20060909 Firefox/1.5.0.7";
	$randomuseragent[2] = "Mozilla/5.0 (Windows; U; Windows NT 6.0; pt-BR; rv:1.8.0.7) Gecko/20060909 Firefox/1.5.0.7";
	$randomuseragent[3] = "Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.8.0.4) Gecko/20060508 Firefox/1.5.0.4";
	$randomuseragent[4] = "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.0.4) Gecko/20060602 Firefox/1.5.0.4";
	$randomuseragent[5] = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.2) Gecko/20060308 Firefox/1.5.0.2";
	$randomuseragent[6] = "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.2; WOW64; .NET CLR 2.0.50727)";
	$randomuseragent[7] = "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.2; Win64; x64; .NET CLR 2.0.50727)";
	$randomuseragent[8] = "Mozilla/4.0 (compatible; MSIE 7.0b; Windows NT 6.0)";
	$randomuseragent[9] = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2; WOW64; SV1; .NET CLR 2.0.50727)";
	$randomuseragent[10] = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2; Win64; x64; SV1; .NET CLR 2.0.50727)";
	$randomuseragent[11] = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1) Netscape/8.0.4";
	$randomuseragent[12] = "Mozilla/4.0 (compatible; MSIE 6.0; Windows XP)";
	$randomuseragent[13] = "Mozilla/4.0 (compatible; MSIE 6.0; WINDOWS; .NET CLR 1.1.4322)";
	$randomuseragent[14] = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; Maxthon; .NET CLR 1.1.4322)";
	$randomuseragent[15] = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2; Win64; AMD64)";
	$randomuseragent[16] = "Mozilla/5.0 (compatible; Konqueror/3.5; Linux; X11; i686; en_US) KHTML/3.5.3 (like Gecko)";
	$randomuseragent[17] = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)";
	$randomuseragent[18] = "Mozilla/5.0 (compatible; Konqueror/3.4; Linux 2.6.8; X11; i686; en_US) KHTML/3.4.0 (like Gecko)";
	$randomuseragent[19] = "Mozilla/5.0 (compatible; Konqueror/3.4; Linux) KHTML/3.4.1 (like Gecko)";
	$randomuseragent[20] = "Mozilla/5.0 (compatible; Konqueror/3.3; Linux) (KHTML, like Gecko)";
	$randomuseragent[21] = "Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/418.8 (KHTML, like Gecko) Safari/419.3";
	$randomuseragent[22] = "Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/417.9 (KHTML, like Gecko) Safari/417.8";
	$randomuseragent[23] = "Mozilla/5.0 (Macintosh; U; PPC Mac OS X; fr-fr) AppleWebKit/312.5.1 (KHTML, like Gecko) Safari/312.3.1";
	$randomuseragent[24] = "Mozilla/5.0 (Macintosh; U; PPC Mac OS X; es) AppleWebKit/85 (KHTML, like Gecko) Safari/85";
	$randomuseragent[25] = "Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/51 (like Gecko) Safari/51";
	$randomuseragent[26] = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20050302 Firefox/0.9.6";
	$randomuseragent[27] = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20050302 Firefox/0.9.6";
	$randomuseragent[28] = "Mozilla/5.0 (Windows; U; Windows NT 5.2 x64; en-US; rv:1.9a1) Gecko/20061007 Minefield/3.0a1";
	$randomuseragent[29] = "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-GB; rv:1.8.1) Gecko/20060918 Firefox/2.0";
	$randomuseragent[30] = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8) Gecko/20060319 Firefox/2.0a1";
	$randomuseragent[31] = "Mozilla/5.0 (Windows; U; Windows NT 5.1; pt-BR; rv:1.8) Gecko/20051111 Firefox/1.5";
	$randomuseragent[32] = "Mozilla/5.0 (X11; U; FreeBSD i386; en-US; rv:1.7.12) Gecko/20051105 Firefox/1.0.7";
	$randomuseragent[33] = "Mozilla/5.0 (Windows; U; Windows NT 5.1; pt-BR; rv:1.7.10) Gecko/20050717 Firefox/1.0.6";
	$randomuseragent[34] = "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-GB; rv:1.7.8) Gecko/20050418 Firefox/1.0.4";
	$randomuseragent[35] = "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.6) Gecko/20050224 Firefox/1.0.2";
	$useragent = $randomuseragent[rand(0,35)];
	///	end of addition
	$out = "GET ".$sRecDirUrl." HTTP/1.0\r\n";
	$out .= "Host: ".$url['host']."\r\n";
	$out .= "User-Agent: $useragent\r\n";
	//$out .= "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n";
   $out .= "Connection: Close\r\n\r\n";
 //  if (!isValidURL($url['host'])) return false;   // fix this - check for valid url, otherwise the fsockopen fails
   @fclose($sock);
//	$sock = @fsockopen($url['host'], 80, $errno, $errstr, 20.0);
	$sock = @fsockopen($url[host], 80, $errno, $errstr, 10);
//var_dump($sRecDirUrl);die;
   if (!$sock) {
    //echo "$errstr ($errno)<br />\n";
    return false;
   }
  fwrite($sock, $out, strlen($out));
  $content="";
  while (!feof($sock)){
    $content.= fgets($sock);
  }
  @fclose($sock);
	//----------------------- CHECKING -----------------------------
	if (($content == "Unable to open page.") || ($content == ""))return '404 Page not found';	
   $sHttpCode = substr($content, 0, stripos($content,"\n"));
	$aCodeNumbers = array();
   if (preg_match("/([45]\d\d)/",$sHttpCode,$aCodeNumbers)){
      return $aCodeNumbers[0] . ' Error';
   }
  $foundlinks = eblex_findhrefs($content);
  //file_put_contents("C:\\XAMPP\\xampp\\tests\\blamaz4.txt", $sRecUrl);
  $sReciprocalurls = explode(' ',$sRecUrl); 
/*  
	foreach ($sReciprocalurls as $sReciprocalurl) {
    if (array_search($sReciprocalurl,$foundlinks) !== FALSE) return true;
  }
  */  
  // var_dump($foundlinks);
  foreach ($sReciprocalurls as $sReciprocalurl) {
    foreach($foundlinks as $foundlink){
      if(($foundlink)&&($sReciprocalurl))
      if (strpos($foundlink,$sReciprocalurl) !== FALSE) return 'OK';
    }
  }
  return 'No backlinks found';
}
function eblex_findhrefs($sPage){
    $aATags = array();
    $aComments = array();
    preg_match_all("/<a\s*[^>]+>/iS",$sPage, $aATags);
    $aATags = $aATags[0];
    //file_put_contents("C:\\XAMPP\\xampp\\tests\\blamaz1.txt", implode("\n",$aATags));
    preg_match_all("/<!--.*-->/msUS",$sPage, $aComments);
    $aComments = $aComments[0];
    //file_put_contents("C:\\XAMPP\\xampp\\tests\\blamaz2.txt", implode("\n$\n",$aComments));    
    foreach ($aComments as $key => $sComment){
        $aABadTags = array();
        preg_match_all("/<a\s*[^>]+>/iS",$sComment, $aABadTags);
        $aABadTags = $aABadTags[0];
       // file_put_contents("C:\\XAMPP\\xampp\\tests\\blamaz3.txt", implode("\n",$aABadTags),FILE_APPEND);
        $aAResult = array_intersect($aABadTags,$aATags);
        foreach ($aAResult as $sResult ){
          unset($aATags[array_search($sResult, $aATags)]);   
        }
    }
//      var_dump($aATags);die;
    //file_put_contents("C:\\XAMPP\\xampp\\tests\\blamaz4.txt", implode("\n",$aATags));
    foreach ($aATags as $i => $value){
      $aUrl = array();
      //var_dump($sAtag);
//      preg_match("/\shref\s?=\s?[\"']([^\"']+?)[\"']/",$sAtag,$aUrl);
        preg_match("/href\s*?=\s?[\"'](.+?)[\"']/i",$value,$aUrl);
  //    preg_match("/[\"'](.+?)[\"']/",$sAtag,$aUrl);
      if (substr($aUrl[1], -1)=='/') $aUrl[1] = substr($aUrl[1], 0, -1);
//      $sAtag = str_replace(' ','%20',$aUrl[1]);
      $aATags[$i] = str_replace(' ','%20',$aUrl[1]);
    }
    //file_put_contents("C:\\XAMPP\\xampp\\tests\\blamaz4.txt", implode("\n",$aATags));
return $aATags;
}
function lnkrob_ajax_action_edit()
{
  global $sSelValue,$wpdb;
  //var_dump($sSelValue);
	$link= $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}eblex_links WHERE id = '{$_POST['id']}'" );
	LinkHTML( $link, $_POST['edit'] );
	die;
}
function lnkrob_ajax_action_editinbox()
{
  global $sSelValue,$wpdb;
  //var_dump($sSelValue);
	$link= $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}eblex_links WHERE id = '{$_POST['id']}'" );
	InboxLinkHTML( $link, $_POST['edit'] );
	die;
}
function lnkrob_ajax_action_edit_clean()
{
  global $sSelValue,$wpdb;
  //var_dump($sSelValue);
	$link = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}eblex_links WHERE id = '{$_POST['id']}'" );
	CleanLinkHTML( $link, $_POST['count'], $_POST['edit'] );
	die;
}
function lnkrob_ajax_action_save(){
  global $wpdb;
//  $linkcatid = 'link-category-'.$_POST['id'];
  if ($_POST['id'] == "new") {}//,..
  else{
    if($_POST['found'])
      $wpdb->query( "UPDATE {$wpdb->prefix}eblex_links SET title = '{$_POST['title']}', url = '{$_POST['url']}', category = '{$_POST['category']}', description = '{$_POST['description']}', email = '{$_POST['email']}', reciprocalurl = '{$_POST['rec_url']}', administratorcomment = '{$_POST['admin_comment']}', zindex = '{$_POST['priority_index']}', status = 1, found = '{$_POST['found']}' WHERE id = '{$_POST['id']}' " );
    else
      $wpdb->query( "UPDATE {$wpdb->prefix}eblex_links SET title = '{$_POST['title']}', url = '{$_POST['url']}', category = '{$_POST['category']}', description = '{$_POST['description']}', email = '{$_POST['email']}', reciprocalurl = '{$_POST['rec_url']}', administratorcomment = '{$_POST['admin_comment']}', zindex = '{$_POST['priority_index']}', status = 1 WHERE id = '{$_POST['id']}' " );
    }
	if( strlen( trim($wpdb->last_error) == 0 ) ) {
		$link= $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}eblex_links WHERE id = '{$_POST['id']}'" );
    LinkHTML( $link, 'false' );
	}
	die;
}
function lnkrob_ajax_action_approve(){
  global $wpdb;
//  $details = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}eblex_links` WHERE `id`='{$_POST['id']}' ORDER BY `zindex` DESC");
  $details = $wpdb->get_row("SELECT * FROM `{$wpdb->prefix}eblex_links` WHERE `id`='{$_POST['id']}'");
  $s_email2 = get_option('wp_link_robot_email2');
  $s_emailt1 = get_option('wp_link_robot_emailt1');
  $s_emailfrom = get_option('wp_link_robot_emailfrom');
  if (isset($_POST['id'])) {
     $wpdb->query( "UPDATE {$wpdb->prefix}eblex_links SET active = '1' WHERE id = '{$_POST['id']}' " );
     $noticemsg = "Link approved!";
     if ($s_email2 == "1") {
        $s_emailt1 = str_replace("{LINK}", $details->url, $s_emailt1);
/*        echo($_POST['id']);
        var_dump($details);var_dump($details2); echo($details2->email);
        echo($details[0]->email);die;*/
       // echo($details[0]->email);  
        $headers = 'From: Link Administrator <'.$s_emailfrom.'>' . "\r\n\\";       
        wp_mail($details->email, "Link approval notification", $s_emailt1, $headers);
        //mail($details[0]->email, "Link approval notification", $s_emailt1);
     } 
  }
	$link= $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}eblex_links WHERE id = '{$_POST['id']}'" );
  InboxLinkHTML( $link, 'false' );
	die;
}
function lnkrob_ajax_action_reject(){
  global $wpdb;
  $details = $wpdb->get_row("SELECT * FROM `{$wpdb->prefix}eblex_links` WHERE `id`='{$_POST['id']}'");
  $s_email3 = get_option('wp_link_robot_email3');
  $s_emailt2 = get_option('wp_link_robot_emailt2');
  $s_emailfrom = get_option('wp_link_robot_emailfrom');
  if (isset($_POST['id'])) {
    $wpdb->query( "UPDATE {$wpdb->prefix}eblex_links SET status = '2' WHERE id = '{$_POST['id']}'" );
     $noticemsg = "Link rejected!";
     if ($s_email3 == "1") {
        $s_emailt2 = str_replace("{LINK}", $details->url, $s_emailt2);
        $headers = 'From: Link Administrator <'.$s_emailfrom.'>' . "\r\n\\";       
        wp_mail($details->email, "Link rejection notification", $s_emailt2, $headers);
     } 
  }
	$link= $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}eblex_links WHERE id = '{$_POST['id']}'" );
  InboxLinkHTML( $link, 'false' );
	die;
}
function lnkrob_ajax_action_save_inbox(){
  global $wpdb;
//  $linkcatid = 'link-category-'.$_POST['id'];
  if ($_POST['id'] == "new") {}//,..
  else{
    if($_POST['found'])
      $wpdb->query( "UPDATE {$wpdb->prefix}eblex_links SET title = '{$_POST['title']}', url = '{$_POST['url']}', category = '{$_POST['category']}', description = '{$_POST['description']}', email = '{$_POST['email']}', reciprocalurl = '{$_POST['rec_url']}', administratorcomment = '{$_POST['admin_comment']}', zindex = '{$_POST['priority_index']}', status = '{$_POST['status']}', found = '{$_POST['found']}' WHERE id = '{$_POST['id']}' " );
    else
      $wpdb->query( "UPDATE {$wpdb->prefix}eblex_links SET title = '{$_POST['title']}', url = '{$_POST['url']}', category = '{$_POST['category']}', description = '{$_POST['description']}', email = '{$_POST['email']}', reciprocalurl = '{$_POST['rec_url']}', administratorcomment = '{$_POST['admin_comment']}', zindex = '{$_POST['priority_index']}', status = '{$_POST['status']}' WHERE id = '{$_POST['id']}' " );
    }
	if( strlen( trim($wpdb->last_error) == 0 ) ) {
		$link= $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}eblex_links WHERE id = '{$_POST['id']}'" );
    InboxLinkHTML( $link, 'false' );
	}
	die;
}
function lnkrob_ajax_action_clean_save(){
  global $wpdb;
//  $linkcatid = 'link-category-'.$_POST['id'];
// echo($_POST['id']);
  if ($_POST['id'] == "new") {}//,..
  else{
      $wpdb->query( "UPDATE {$wpdb->prefix}eblex_links SET title = '{$_POST['title']}', url = '{$_POST['url']}', category = '{$_POST['category']}', description = '{$_POST['description']}', email = '{$_POST['email']}', reciprocalurl = '{$_POST['rec_url']}', administratorcomment = '{$_POST['admin_comment']}', zindex = '{$_POST['priority_index']}', status = '{$_POST['status']}' WHERE id = '{$_POST['id']}' " );
    }
	if( strlen( trim($wpdb->last_error) == 0 ) ) {
		$link= $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}eblex_links WHERE id = '{$_POST['id']}'" );
    CleanLinkHTML( $link,$_POST['count'], 'false' );
	}
	die;
}
function lnkrob_ajax_action_new(){
  global $wpdb;
  $l_id = md5(uniqid(rand(), true) . $_POST['title']);
  $l_time = time();
   if (empty($_POST['title'])) die('TitleError');
   if (empty($_POST['email'])) die('EmailError');
   if (empty($_POST['url'])) die('UrlError');
  $wpdb->query("INSERT INTO {$wpdb->prefix}eblex_links (`title`, `active`, `nonreciprocal`, `url`, `category`, `description`, `email`, `reciprocalurl`, `status`, `time`, `administratorcomment`, `zindex`, `id`, found) 
                  VALUES ('{$_POST['title']}', '1', '0', '{$_POST['url']}', '{$_POST['category']}', '{$_POST['description']}', '{$_POST['email']}', '{$_POST['rec_url']}', '1', '$l_time', '{$_POST['administratorcomment']}', '$l_priorityindex', '$l_id', '{$_POST['found']}');");
	if( strlen( trim($wpdb->last_error) == 0 ) ) {
		$link= $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}eblex_links WHERE id = '{$l_id}'" );
    LinkHTML( $link, 'false' );
	}
	die;
}
function lnkrob_ajax_action_remove(){
   global $wpdb;
//   $status = $wpdb->query( "SELECT `status` FROM {$wpdb->prefix}eblex_links WHERE id = '{$_POST['id']}'");
//   if($status=='2')
	  $wpdb->query( "DELETE FROM {$wpdb->prefix}eblex_links WHERE status = '2' AND id = '{$_POST['id']}'" );  
//   else
   	$wpdb->query( "UPDATE {$wpdb->prefix}eblex_links SET status = '2' WHERE id = '{$_POST['id']}'" );
	die;
}
function lnkrob_ajax_action_check(){
  global $wpdb;
  $link_dir= $wpdb->get_row( "SELECT reciprocalurl FROM {$wpdb->prefix}eblex_links WHERE id = '{$_POST['id']}'" );
  if(!$link_dir) die('Failed');  
  $link = get_option('wp_link_robot_reciprocalurl');
  if(!$link) die('Failed');  
  $wpdb->query( "UPDATE {$wpdb->prefix}eblex_links SET status = '1' WHERE id = '{$_POST['id']}'" );
//  $result = process_url_fv($link_dir->reciprocalurl,$link->value);
  $result = process_url_fv($link_dir->reciprocalurl,$link);
  if($result=='No backlinks found'){
    $wpdb->query( "UPDATE {$wpdb->prefix}eblex_links SET found = '0' WHERE id = '{$_POST['id']}'" );
  }
  else if($result=='OK'){
    $wpdb->query( "UPDATE {$wpdb->prefix}eblex_links SET found = '1' WHERE id = '{$_POST['id']}'" );
  }  
  else
    $wpdb->query( "UPDATE {$wpdb->prefix}eblex_links SET found = '404' WHERE id = '{$_POST['id']}'" );
  $link= $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}eblex_links WHERE id = '{$_POST['id']}'" );
	LinkHTML( $link, 'false' );
	die;
}
function lnkrob_ajax_action_update_url(){
  global $wpdb;
  $link = get_option('wp_link_robot_reciprocalurl');
  if(!$link) die('Failed');  
  $wpdb->query( "UPDATE {$wpdb->prefix}eblex_links SET reciprocalurl = '{$_POST['url']}' WHERE id = '{$_POST['id']}'" );
  $link_dir= $wpdb->get_row( "SELECT reciprocalurl FROM {$wpdb->prefix}eblex_links WHERE id = '{$_POST['id']}'" );
  $result = process_url_fv($link_dir->reciprocalurl,$link->value);
  if($result=='404'){
    $wpdb->query( "UPDATE {$wpdb->prefix}eblex_links SET found = '404' WHERE id = '{$_POST['id']}'" );
  }
  else if(!$result){
    $wpdb->query( "UPDATE {$wpdb->prefix}eblex_links SET found = '0' WHERE id = '{$_POST['id']}'" );
  }  
  else
    $wpdb->query( "UPDATE {$wpdb->prefix}eblex_links SET found = '1' WHERE id = '{$_POST['id']}'" );
  $link= $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}eblex_links WHERE id = '{$_POST['id']}'" );
	CleanLinkHTML( $link, 'false' );
	die;
}
function lnkrob_ajax_action_backcheck(){
  global $wpdb;
  $link_dir= $wpdb->get_row( "SELECT reciprocalurl FROM {$wpdb->prefix}eblex_links WHERE id = '{$_POST['id']}'" );
  if(!$link_dir) die('Failed');  
  $link = get_option('wp_link_robot_reciprocalurl');
  if(!$link) return 'Failed';  
  $result = process_url_fv($link_dir->reciprocalurl,$link->value);
  if($result=='OK'){
    $wpdb->query( "UPDATE {$wpdb->prefix}eblex_links SET found = '1' WHERE id = '{$_POST['id']}'" );
  }
  else if($result=='No backlinks found'){
    $wpdb->query( "UPDATE {$wpdb->prefix}eblex_links SET found = '0' WHERE id = '{$_POST['id']}'" );
  }  
  else
    $wpdb->query( "UPDATE {$wpdb->prefix}eblex_links SET found = '404' WHERE id = '{$_POST['id']}'" );
   echo $result;
/*  if($result=='404'){ echo '404'; die;}  
  if($result=='NO'){ echo('NO'); die;} 
  if($result===false){ echo('Failed');  die;}
  else {echo ('OK');die;}
*/
	die;
}
function lnkrob_ajax_action_addnew(){
    global $table_prefix, $wpdb;
    NewLinkInterface($_POST['idCategory']);
    die;
}
function lnkrob_ajax_action_show_flags(){
  global $wpdb;
  $link= $wpdb->get_row( "SELECT id, administratorcomment FROM {$wpdb->prefix}eblex_links WHERE id = '{$_POST['id']}'" );
  ShowFlags($link, true);
  die;
}
function lnkrob_ajax_action_add_flags(){
  global $wpdb;
  $link= $wpdb->get_row( "SELECT id, administratorcomment FROM {$wpdb->prefix}eblex_links WHERE id = '{$_POST['id']}'" );
 	$wpdb->query( "UPDATE {$wpdb->prefix}eblex_links SET administratorcomment = '{$_POST['flags']}' WHERE id = '{$_POST['id']}'" );
  ShowFlags($link, false);
  die;
}
?>