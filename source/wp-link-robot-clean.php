
<?php
$today = current_time('mysql', 1);


///	Addition	14/07/2008	mVicenik	Foliovision
require_once('wp-link-robot-include.php');
///	end of addition

// FUNCTIONS
function eblex_findhrefs2($page)
{
    $using = false;
    $href = "";
    $hrefs[0] = "";
    $hrefcount = 0;

    for ($i = 0;$i < strlen($page)-1;$i++) {
        $a = $page[$i] . $page[$i + 1];
        ///	Modification	30/06/2008	mVicenik	Foliovision
        //if ($a == "<a") {
        if ($a == "<a" || $a == "<A") {
        ///	end of modification
            $using = true;
        }
		if ($page[$i] == ">") {
            $using = false;
            if ($href != "") {
                $hrefs[$hrefcount] = $href . ">";
                $hrefcount++;
            } 
            $href = "";
        } 

        if ($using == true) {
            $href .= $page[$i];
        } 
    } 

    return $hrefs;
} 

function eblex_parsehrefs($hrefs)
{
    $output[0] = "";
    for ($i = 0;$i < count($hrefs);$i++) {
        $work = str_replace("'", "\"", $hrefs[$i]);
        $position = strpos($work, "href=\"") + strlen("href=\"");
		///	Addition	30/06/2008	mVicenik	Foliovision
		$position2 = strpos($work, "HREF=\"") + strlen("HREF=\"");        
		if($position2 > $position) $position = $position2;        
   	    ///	end of addition

        $char = "";
        $url = "";
        for ($j = $position;$j < strlen($work);$j++) {
            $char = $work[$j];
            if ($char == "\"") {
                break;
            } 
            ///	Addition	30/06/2008	mVicenik	Foliovision
            if ($char == "'") {
                break;
            } 
			///	end of addition
            $url .= $char;
        } 

        $output[$i] = trim($url);
    } 
    return $output;
} 

function process_url2 ( $checkcount,$jcounter,$recip_url,$uurl,$title,$status,$id,$timeout,$reciprocalurl,$father,$row, $class, $spoof ) {
	///	Addition	17/07/2008	mVicenik	Foliovision
	if(strpos($recip_url,'http://')===FALSE)
		$recip_url = 'http://'.$recip_url;
	///	end of addition	
	
	$recip_url=trim($recip_url);			
	$url =  @parse_url($recip_url);
	
	if ($url[query] != "")
	{
		///	Modification	17/07/2008	mVicenik	Foliovision
		$host = $url[host];
		//$host = $url[host]."?".$url[query];
		///	end of modification
	}
	else
	{
		$host = $url[host];
	}

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

	if ($spoof == 1)
	{
		$random = rand(0,35);
		$useragent = $randomuseragent[$random];
	}
	else
	{
		$useragent = "LinkExchange checker (script)";
	}
	
	if ($url[path] == "")
	{
		$geturl = "/";
	}
	else
	{
		$geturl = $url[path];
	}
	///	Addition	17/07/2008	mVicenik	Foliovision
	if ($url[query] != "")
	{
		$geturl.= '?'.$url[query];
	}
	$geturl = str_replace(' ','%20',$geturl);
	///	end of addition
	
	
	$out = "GET ".$geturl." HTTP/1.0\r\n";
	$out .= "Host: ".$host."\r\n";
	$out .= "User-Agent: $useragent\r\n";
	//$out .= "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n";
   $out .= "Connection: Close\r\n\r\n";
   
	if ($recip_url != '' && $recip_url != 'http://'){
		$sock = @fsockopen($url[host],80,$error,$errorstring,$timeout);
	}
	else{
		$sock = false;
	}
			
	if ($sock){
		fwrite($sock,$out);
		$contents = "";
		$time = time();
		do{
		    $part = fread($sock, 128);
			$contents .= $part;
			$ntime = time();
		}
		while (($part != "") && ($ntime - $time < $timeout));
	}
	
	@fclose($sock);
	
	//echo 'Heres what this page contains: /////////////';
	//echo $contents;
	//echo 'That\'s all folks: /////////////';
	
	//----------------------- CHECKING -----------------------------
	if ($contents != "Unable to open page."){	
		$foundlinks = eblex_findhrefs2($contents);
		$found = eblex_parsehrefs($foundlinks);
		$reciprocalurl_tmp = explode(' ',$reciprocalurl);
		foreach ($found as $href)
		{
		//echo(rtrim(ltrim(strtolower($href))));
		//echo("<!-- ".rtrim(ltrim(strtolower($href)))." -->");
			
			foreach ($reciprocalurl_tmp as $recip) {
				//echo "Porovnavam ".rtrim(ltrim(strtolower($href)))." s ".trim($recip)."<br />";
				if (strpos(rtrim(ltrim(strtolower($href))),trim($recip)) > -1)
				{
					//echo $reciprocalurl.' >>>>>';
					$ok = 1;
				}
			}
			//echo htmlspecialchars($href).'<br />';
		}	
	}
	
	
	if (1>0)	////($ok != 1)
	{
		/// Change	17/06/2008	mVicenik	Foliovision
		if ($contents == "")
			$class2 = "linkbox_yellow";
		else{
			$contents = explode("\n",$contents);
			$response = $contents[0];
			$response = rtrim(ltrim(str_replace("HTTP/1.1","",str_replace("HTTP/1.0","",$response))));
			//var_dump($response);
			if ($ok == 1)
				$class2 = "linkbox_green";
			else {
				if ($response == "404 Not Found") $class2 = "linkbox_red";
				else if ($response == "404 Component not found") $class2 = "linkbox_red";
				else if ($response == "400 Bad Request") $class2 = "linkbox_red";
				else if ($response == "200 OK") $class2 = "linkbox_yellow";
				else {
					$class2 = "linkbox_yellow";
					}
			}
		}
		//if ($class == "linkbox1") {$class = "linkbox2";} else {$class = "linkbox1";}
		///	end of change
		
		?>	

<?php		
   if($checkcount < 0){
?>
   <!--a href="wp-link-robot-browse.php<?php echo("?id=".$father."&action=update&newurl=".$uurl); ?>" id="hdel<?php echo($jcounter);?>" onclick="
          		obj = document.getElementById('del<?php echo($jcounter);?>');
          		hobj = document.getElementById('hdel<?php echo($jcounter);?>');
          		if(obj.className != 'linkbox_blue') {
          				var answer = updatelink();
          				if(answer==true) {	//	toto ide
          					obj.className = 'linkbox_blue';
          					hobj.innerHTML = '[Updated]';
          				}
          				return answer;
          		}
          		else return false;
          		" target="_blank">[Update URL]</a-->		
    		<!--a href="javascript:void(0);" onclick="lnkrob_linkCleanUpdateURL('<?php echo $id ?>', '<?php echo $uurl; ?>', '<?php echo($jcounter);?>') ">[Update&nbsp;URL]</a><br /-->		

<?php
}
if($checkcount > 0){  ?>
   <tr class="<?php echo($class); ?>" id="del<?php echo($jcounter);?>">
      <td>
         <input type="checkbox" name="deletecheck<?php echo($jcounter);?>" value="<?php echo($id); ?>">
      </td>
      
      <td>									
    		<input name="id<?php echo($jcounter);?>" id="id<?php echo($jcounter);?>" type="hidden" value="" />
    		<!-- /// Addition	17/06/2008	mVicenik	Foliovision	-->
    		<!--<div class="<?php echo($class); ?>" id="upd<?php echo($jcounter);?>">
    		<input name="id<?php echo($jcounter);?>" id="id<?php echo($jcounter);?>" type="hidden" value="" />-->
    
    		<?php /*if($id<0) {}
    			else {
    				echo($jcounter+1);
    				echo ". ";
    			}*/ ?>
    		<!-- /// end of addition	-->
    		 <span class="<?php echo($class2);?>"><a href="<?php echo($uurl); ?>" class="plink" target="_blank">	<?php echo($title); if ($status == "0") {echo("(inactive)");} ?></a></span><br />
    		 <span class="purl"><?php echo(str_replace("/","/",str_replace("http://","",$uurl))); ?></span><br />
    		 <span id="flag-<?php echo($id); ?>">
         <a href="javascript:void(0);" onclick="lnkrob_linkCleanShowFlag('<?php echo $id ?>') ">[Show notes]</a></span>
		  </td>
        
      <td>
    			<?php
            if($id<0) {}
      			else {
      				show_pr($row);
      			}
      			echo '';
      		?>
          <!--	///	end of addition	02/06/2008	mVicenik	Foliovision	-->
          <!--	///	Addition	25/06/2008	mVicenik	Foliovision	-->
          <?php
          	if($id<0) {}
          	else 
          		echo '<div id="LEHiddenControls" style="display: none;">';
          	if($checkcount==-1)
          		echo '<div id="LEHiddenControls" style="display: none;">';
          ?>
          	<a href="wp-link-robot-browse.php<?php echo("?id=".$father."&action=update&newurl=".$uurl); ?>" id="hdel<?php echo($jcounter);?>" onclick="
          		obj = document.getElementById('del<?php echo($jcounter);?>');
          		hobj = document.getElementById('hdel<?php echo($jcounter);?>');
          		if(obj.className != 'linkbox_blue') {
          				var answer = updatelink();
          				if(answer==true) {	//	toto ide
          					obj.className = 'linkbox_blue';
          					hobj.innerHTML = '[Updated]';
          				}
          				return answer;
          		}
          		else return false;
          		" target="_blank">[Update]</a>
          <?php
          	if($id<0) {}
          	else
          		echo '</div>';
          	if($checkcount==-1)	echo '</div>';
          ?>
		</td>
		
		<?php if($id<0) {}
    else {?>
		<td><a href="<?php echo $recip_url?>" ><?php echo $recip_url ?> </a></td>
		<?php } ?>
		 
		<?php
			
		if ($contents == "")
		{
			echo("<td>")
       ?>
       <a href="javascript:void(0);" onclick="lnkrob_checkBackLink('<?php echo $id ?>', '<?php echo $jcounter ?>')"><span class="check" id="checking-<?php echo $id ?>">Error
       <!--/span></a-->
       <?php
       //echo("</td><td></td>");
		}
		else
		{       
         ?>
        <td>
         <a href="javascript:void(0);" onclick="lnkrob_checkBackLink('<?php echo $id ?>', '<?php echo $jcounter ?>')"><span class="check" id="checking-<?php echo $id ?>">
         <?php
			if ($ok == 1) {echo("OK");}
			else {
			///	end of addition
				$redirect = '';
				if ($response == "404 Not Found") {echo("Page not found");}
				else if ($response == "400 Bad Request") {echo("Server failed to execute our request");}
				else if ($response == "404 Component not found") {echo("Page not found");}
				else if ($response == "200 OK") {echo("No backlinks found");}
				///	Addition	17/06/2008	mVicenik	Foliovision
				else if ($response == "301 Moved Permanently" || $response == "302 Found" || $response == "302 Moved Temporarily" || $response == "307 Temporary gt") {
					for ($i=0;$i<sizeof($contents);$i++) {
						if(strpos($contents[$i],"Location:")!==FALSE) {
							$redirect = str_replace("Location: ","",$contents[$i]);
							if($redirect[0]=='/')
								$redirect = substr($recip_url,0,strrpos($recip_url,'/')).$redirect;
						}
					}
					echo ($response.', redirected to: <a href="'.$redirect.'">'.$redirect.'</a><br />');
				}
				///	end of addition
				
			}
			
			if( $redirect != '') {
				///	Addition	17/06/2008	mVicenik	Foliovision
				if($id<-4)
					echo '<b>Redirect loop, aborting!</b>';
				else {
					if($id < 0){}
					//	$id--;
					else 
					{};//	$id = -1;
					if($response == "301 Moved Permanently")			
						process_url2( -2, $jcounter, trim($redirect), trim($redirect), "Redirect found", 1, $id, $timeout, $reciprocalurl, $id, $row, $class, $spoof );
					else				
						process_url2( -1, $jcounter, trim($redirect), trim($redirect), "Redirect found", 1, $id, $timeout, $reciprocalurl, $id, $row, $class, $spoof );
					//	echo($response);
				}
				///	end of addition
			}
		?>
    
      <?php
		}
	}
	
		?> 
      
      <?php		
         if($checkcount < 0){
      ?>
         <!--a href="wp-link-robot-browse.php<?php echo("?id=".$father."&action=update&newurl=".$uurl); ?>" id="hdel<?php echo($jcounter);?>" onclick="
                		obj = document.getElementById('del<?php echo($jcounter);?>');
                		hobj = document.getElementById('hdel<?php echo($jcounter);?>');
                		if(obj.className != 'linkbox_blue') {
                				var answer = updatelink();
                				if(answer==true) {	//	toto ide
                					obj.className = 'linkbox_blue';
                					hobj.innerHTML = '[Updated]';
                				}
                				return answer;
                		}
                		else return false;
                		" target="_blank">[Update URL]</a-->		
          		<a href="javascript:void(0);" onclick="lnkrob_linkCleanUpdateURL('<?php echo $id ?>', '<?php echo $uurl; ?>', '<?php echo($jcounter);?>') ">[Update&nbsp;URL]</a><br />		
      
      <?php
      }
      else{
      ?>
      </span></a></td>
      
      <td>
			<?php
				
			$viewresponce = "";
			
			for ($m=0;$m<10;$m++)
			{
				$viewresponce .= $contents[$m];
			}
			$viewresponce = str_replace("\"","&quot;",$viewresponce);
			$viewresponce = str_replace("'","",$viewresponce);
			$viewresponce = str_replace("\n","",$viewresponce);
			$viewresponce = str_replace("\r","\\n",$viewresponce);
			
			echo("<a href=\"#\" onclick=\"alert('$viewresponce'); return false;\">View</a><br/>");
			?>
       </td> 
<?php
}
?> 
			
<?php
   if($checkcount >0){
?>
      <td>
    		<?php
    			if($id<0) echo '<div id="LEHiddenControls" style="display: none;">'; 
    		?>
    
    		<a href="javascript:void(0);" onclick="lnkrob_linkCleanToggleEdit('<?php echo $id ?>', '<?php echo($jcounter);?>',true) ">[Edit]</a><br />		
    	
    		<!--	///	Addition	02/06/2008	mVicenik	Foliovision	-->				
    		<?php if($status!=2) {?>
    
                <a href="javascript:void(0);" onclick="lnkrob_removeBackLink('<?php echo $id ?>', '<?php echo($jcounter);?>')">[Trash]</a><br />
    		<?php } else { ?>
    	    	<a href="wp-link-robot-browse.php<?php echo($dlink."?id=".$row->id."&action=trash&undo"); ?>" onclick="return untrashlink()">[Recycle]</a><br />
    		<?php }		
    		?>
    					
    		<?php
    			if($id<0)	echo '</div>';
    			?>
      </td></tr>
  		<?php
  		}
	}
}

//$eblex_settings = $table_prefix . "eblex_settings";
$eblex_categories = $table_prefix . "eblex_categories";
$eblex_links = $table_prefix . "eblex_links";

$spoof = get_option('wp_link_robot_spoof');
$reciprocalurl = get_option('wp_link_robot_reciprocalurl');

$l_activelinkcount = $wpdb->get_var("SELECT count(*) FROM `$eblex_links` WHERE `status`='1'");
$l_activelinkcountnref = $wpdb->get_var("SELECT count(*) FROM `$eblex_links` WHERE `status`='1' AND `nonreciprocal`!='1'");


if ($_POST['counter'] != "") {
    $counter = $_POST['counter'];
    $deletedlinks = 0;
    if (is_numeric($counter)) {
        for ($i = 0; $i < $counter; $i++) {
            $linkid = $_POST['deletecheck' . $i];
            
            ///	Modification	14/07/2008	mVicenik	Foliovision
            $details = $wpdb->get_var("SELECT `id` FROM `$eblex_links` WHERE `id`='$linkid' ORDER BY `zindex` DESC, `title` ASC");
            //$details = $wpdb->get_var("SELECT `id` FROM `$eblex_links` WHERE `id`='$linkid' ORDER BY `zindex` DESC");
            ///	end of modification

            if (isset($details)) {
            	///	Modification	04/06/2008
                //$wpdb->query("DELETE FROM `$eblex_links` WHERE `id`='$linkid'");
                $wpdb->query("UPDATE `$eblex_links` SET `status`='2' WHERE `id`='$linkid'");
                ///	end of modification
                $deletedlinks++;
            } 
        } 
        ///	Modification	04/06/2008
        //$noticemsg = "$deletedlinks link(s) deleted!";
        $noticemsg = "$deletedlinks link(s) trashed!";
        ///	end of modification
    } 
} 

/*
if ($_POST['counter'] != "") {
    $counter = $_POST['counter'];
    $deletedlinks = 0;
    if (is_numeric($counter)) {
        for ($i = 0; $i < $counter; $i++) {
            $linkid = $_POST['id' . $i];
            
            ///	Modification	14/07/2008	mVicenik	Foliovision
            $details = $wpdb->get_var("SELECT `id` FROM `$eblex_links` WHERE `id`='$linkid' ORDER BY `zindex` DESC, `title`");
            //$details = $wpdb->get_var("SELECT `id` FROM `$eblex_links` WHERE `id`='$linkid' ORDER BY `zindex` DESC");
            ///	end of modification

            if (isset($details)) {
            	///	Modification	04/06/2008
                //$wpdb->query("DELETE FROM `$eblex_links` WHERE `id`='$linkid'");
                $wpdb->query("UPDATE `$eblex_links` SET `status`='2' WHERE `id`='$linkid'");
                ///	end of modification
                $deletedlinks++;
            } 
        } 
        ///	Modification	04/06/2008
        //$noticemsg = "$deletedlinks link(s) deleted!";
        $noticemsg = "$deletedlinks link(s) trashed!";
        ///	end of modification
    } 
} 
*/
?>
<?php
if ($noticemsg!="")
{
?>
<div id="message1" class="updated fade"><p><?php _e($noticemsg); ?></p></div>
<?php 
}
?>
<style type="text/css">
<!--
.catlink {
border:0px;
font-size:14px;
font-weight:bold;
color:#6666FF;
}

.catlink:hover {
color:#000066;
}

.catsublink {
border:0px;
font-size:12px;
color:#6666FF;
}

.catsublink:hover {
color:#000066;
}

.plink {
border:0px;
font-size:12px;
font-weight:bold;
color:#000000;
}

.plink:hover {
color:#0000CC;
}

.purl {
color:#333333;
font-size:11px;
}

.linkbox1
{
background-color:#FFFFFF;
border:1px #FFFFFF solid;
width:100%;
padding:3px;
}

.linkbox_blue
{
background-color: #83B4D8;
border:1px #ddffdd solid;
width:100%;
padding:3px;
}

.linkbox_hidden
{
display: none;
}

.linkbox_green
{
background-color: #DDFFDD;
border:1px #ddffdd solid;
width:100%;
padding:3px;
}

.linkbox_yellow
{
background-color: #FFFF55;
border:1px #FFFFFF solid;
width:100%;
padding:3px;
}

.linkbox_orange
{
background-color: #FFAA55;
border:1px #FFFFFF solid;
width:100%;
padding:3px;
}

.linkbox_red
{
background-color: #FF5555;
border:1px #FFFFFF solid;
width:100%;
padding:3px;
}

.linkbox1:hover
{
background-color:#F9F9F9;
border:1px #CCCCCC solid;
}

.linkbox2
{
background-color:#FAFAFA;
border:1px #FFFFFF solid;
width:100%;
padding:3px;
}

.linkbox2:hover
{
background-color:#F9F9F9;
border:1px #CCCCCC solid;
}

.linkboxselected
{
background-color:#FFB7B7;
border:1px #FFFFFF solid;
width:100%;
padding:3px;
}

.linkboxselected:hover
{
background-color:#FF7777;
border:1px #CCCCCC solid;
}

.catbox {
width:95%;
padding:5px;
border:1px #FFFFFF solid;
}

.catbox:hover {
border:1px #EEEEEE solid;
}
.robotnewlink {
  padding:0px;
  border:0px;
}
.robotnewlink td{
  padding:0px;
  border:0px;
}
-->
</style>
<script language="JavaScript" type="text/javascript">
function trashlink(linkname) {
	var answer = confirm("Move this link to trash?")
	if (answer){
		return true;
	}
	else{
		return false;
	}
}

function deletelink(linkname) {
	var answer = confirm("Delete this link?")
	if (answer){
		return true;
	}
	else{
		return false;
	}
}

function updatelink(linkname) {
	var answer = confirm("Update the original link with redirected url?")
	if (answer){
		return true;
	}
	else{
		return false;
	}
}
</script>
<div class="wrap"  style="clear:left">
<h2><?php _e('Backlink cleansing'); ?></h2>

<br />
<form id="form1" name="form1" method="post" action="">
<!--	Addition	04/07/2008	mVicenik	Foliovision	-->
	<label>Select a category:</label><br />
	<tr valign="top">
	  <td><select name="category" id="category">
	  	<?php $l_linkcount = $wpdb->get_var("SELECT count(*) FROM `$eblex_links` WHERE `category`='0' AND `active`='1' AND `status`!='2'"); ?>
	    <option value="0" style="font-weight:bold"<?php if ($_POST['category'] == "0" || $_POST['category'] == "") echo(" selected"); ?> >Root (<?php echo($l_linkcount);?> links)</option>
	    <?php
	    $cat = $wpdb->get_results("SELECT * FROM `$eblex_categories` WHERE `parent`='' ORDER BY `zindex` DESC, `title` ASC");
	    
		foreach ($cat as $row)
	  	{
   		if ($row->id != "0")
   		{
      		$l_linkcount = $wpdb->get_var("SELECT count(*) FROM `$eblex_links` WHERE `category`='".$row->id."' AND `active`='1' AND `status`!='2'");
      		?>
      		<option value="<?php echo($row->id);?>" <?php if ($_POST['category'] == $row->id) echo(" selected"); ?>>&nbsp;&nbsp;<?php echo($row->title);?> (<?php echo($l_linkcount);?> links)</option>
      		<?php
      		$subcat=$wpdb->get_results("SELECT * FROM `$eblex_categories` WHERE `parent`='".$row->id."' ORDER BY `zindex` DESC, `title` ASC");
      		
      			foreach ($subcat as $subrow)
      	  		{
      	  		$l_linkcount = $wpdb->get_var("SELECT count(*) FROM `$eblex_links` WHERE `category`='".$subrow->id."' AND `active`='1' AND `status`!='2'");
      			?>
      			<option value="<?php if ($subrow->parent!="") {echo($subrow->id);} ?>" style="font-style:italic; font-size:12px;"<?php if ($_POST['category'] == $subrow->id) echo(" selected"); ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo($subrow->title);?> (<?php echo($l_linkcount);?> links)</option>
      			<?php
      			}
   		  }
		}
		?>
	    </select></td>
	</tr><br />
	<!--	end of addition	-->
	<!--	Modification	04/07/2008	mVicenik	Foliovision	-->
	<!--	Number of links to searcthrough: <strong><?php echo($l_activelinkcountnref);?></strong> out of <strong><?php echo($l_activelinkcount);?></strong><br/>	-->
	<!--	end of modification	-->
  - <label>Assume a backlink is invalid if waiting for response exceeds <input name="timeout" type="text" class="code" id="timeout" value="10" size="5"/> seconds.
  </label><br />
  - <label>Recurse into subcategories <input type="checkbox" name="checkbox" <?php if(isset($_POST['checkbox'])) echo "checked";?>>
  </label><br />
  - <label>Look into trash only <input type="checkbox" name="checkbox2" <?php if(isset($_POST['checkbox2'])) echo "checked";?>>
  </label><br />
  This process may take a longer time to complete if there are too many links inside the database.
<p class="submit">
  <input type="submit" value="<?php _e('Start cleaning') ?>" name="Submit" />
</p>
</form>
<?php
if ($_POST['timeout'] != "")
{
?>
<h2><?php _e('Backlink cleansing results'); ?></h2>
<form id="form2" name="form2" method="post" action="">
<?php
}
			

$jcounter = 0;
if ($_POST['timeout']!="")
{
	$timeout = $_POST['timeout'];
	if (is_numeric($timeout))
	{
		///	Addition	14/07/2008	mVicenik	Foliovision
		$l_category = $_POST['category'];
		if(isset($_POST['checkbox2'])) {
			$numberoflinks = $wpdb->get_var("SELECT count(*) FROM `$eblex_links` WHERE `status`='2' ORDER BY `zindex` DESC, `title` ASC");
			$numberofreciprociallinks = $wpdb->get_var("SELECT count(*) FROM `$eblex_links` WHERE `status`='2' AND `reciprocalurl`!='' AND `nonreciprocal`!='1' ORDER BY `zindex` DESC, `title` ASC");
		}
		else {
			if(isset($_POST['checkbox'])) {
				$numberoflinks = 0;
				$numberofreciprociallinks = 0;
				if ($_POST['category'] == "0" || $_POST['category'] == "") {
					$numberoflinks = $wpdb->get_var("SELECT count(*) FROM `$eblex_links` WHERE `status`='1' ORDER BY `zindex` DESC, `title` ASC");
					$numberofreciprociallinks = $wpdb->get_var("SELECT count(*) FROM `$eblex_links` WHERE `status`='1' AND `reciprocalurl`!='' AND `nonreciprocal`!='1' ORDER BY `zindex` DESC, `title` ASC");
				}
				else {
					$cat = $wpdb->get_results("SELECT * FROM `$eblex_categories` WHERE `id`='$l_category' OR `parent`='$l_category' ORDER BY `zindex` DESC, `title` ASC");
					foreach ($cat as $row) {
						$numberoflinks += $wpdb->get_var("SELECT count(*) FROM `$eblex_links` WHERE `status`='1' AND `category`='$row->id' ORDER BY `zindex` DESC, `title`");
						$numberofreciprociallinks += $wpdb->get_var("SELECT count(*) FROM `$eblex_links` WHERE `status`='1' AND `category`='$row->id' AND `reciprocalurl`!='' AND `nonreciprocal`!='1' ORDER BY `zindex` DESC, `title` ASC");
					}
				}
			}
			else {
				///	Modification	04/07/2008	mVicenik	Foliovision
				$numberoflinks = $wpdb->get_var("SELECT count(*) FROM `$eblex_links` WHERE `status`='1' AND `category`='$l_category' ORDER BY `zindex` DESC, `title` ASC");
				//$numberoflinks = $wpdb->get_var("SELECT count(*) FROM `$eblex_links` WHERE `status`='1' ORDER BY `zindex` DESC");
				///	end of modification
				///	Addition	04/07/2008	mVicenik	Foliovision
				$numberofreciprociallinks = $wpdb->get_var("SELECT count(*) FROM `$eblex_links` WHERE `status`='1' AND `category`='$l_category' AND `reciprocalurl`!='' AND `nonreciprocal`!='1' ORDER BY `zindex` DESC, `title` ASC");
				///	end of addition
			}
		}
		///	end of addition
		
		echo "Processing ".$numberofreciprociallinks." links out of ".$numberoflinks." links.";
					
		if ($numberoflinks != "0")
		{
			///	Addition	14/07/2008	mVicenik	Foliovision
			//$spoof = $wpdb->get_var("SELECT `value` FROM `$eblex_settings` WHERE `option`='spoof'");
			if(isset($_POST['checkbox2'])) {	//	clean trash only
				$lnk = $wpdb->get_results("SELECT * FROM `$eblex_links` WHERE `status`='2' ORDER BY `zindex` DESC, `title` ASC");
				////
			echo '<table class="widefat">	
                  <thead>
                  <tr><th></th><th>Title</th><th width="15%">Info</th><th width="20%">Reciprocal URL</th><th>Status</th><th>Response*</th><th>Actions</th></tr>
                  </thead>
                  <tbody>';
				foreach ($lnk as $row) {
					$ok = 0;
					$checkcount++;
					$contents = "";
					if ($row -> nonreciprocal != "1") {
						///	Addition	17/06/2008	mVicenik	Foliovision
						if ($class == "linkbox1") {$class = "linkbox2";} else {$class = "linkbox1";}
						process_url2( $checkcount, $jcounter, $row->reciprocalurl, $row->url, $row->title, $row->status, $row->id, $timeout, $reciprocalurl, -1, $row, $class, $spoof  );
						if($row->id!=-1)
							$jcounter++; //increment the counter for checkboxing
						///	end of addition
					}
			  	}
				  	echo '</tbody>
  			</table><br>* View first 10 lines of the server response';
			  	////
			}
			else {
				if(isset($_POST['checkbox'])) {	//	clean recursively	
					if ($_POST['category'] == "0" || $_POST['category'] == "") {	//	from Root
						$lnk = $wpdb->get_results("SELECT * FROM `$eblex_links` WHERE `status`='1' ORDER BY `zindex` DESC, `title` ASC");
						////
						echo '<table class="widefat">	
                  <thead>
                  <tr><th></th><th>Title</th><th width="15%">Info</th><th width="20%">Reciprocal URL</th><th>Status</th><th>Response*</th><th>Actions</th></tr>
                  </thead>
                  <tbody>';

						foreach ($lnk as $row) {
							$ok = 0;
							$checkcount++;
							$contents = "";
							if ($row -> nonreciprocal != "1") {
								///	Addition	17/06/2008	mVicenik	Foliovision
								if ($class == "linkbox1") {$class = "linkbox2";} else {$class = "linkbox1";}
								process_url2( $checkcount, $jcounter, $row->reciprocalurl, $row->url, $row->title, $row->status, $row->id, $timeout, $reciprocalurl, -1, $row, $class, $spoof );
								if($row->id!=-1)
									$jcounter++; //increment the counter for checkboxing
								///	end of addition
							}
					  	}
				  	echo '</tbody>
  			</table><br>* View first 10 lines of the server response';
					  	////
					}
					else {	//	from any other category
						$cat = $wpdb->get_results("SELECT * FROM `$eblex_categories` WHERE `id`='$l_category' OR `parent`='$l_category' ORDER BY `zindex` DESC, `title` ASC");
						$checkcount = 0;
						foreach ($cat as $row) {
							$lnk = $wpdb->get_results("SELECT * FROM `$eblex_links` WHERE `status`='1' AND `category`='$row->id' ORDER BY `zindex` DESC, `title` ASC");
							////
						echo '<table class="widefat">	
                  <thead>
                  <tr><th></th><th>Title</th><th width="15%">Info</th><th width="20%">Reciprocal URL</th><th>Status</th><th>Response*</th><th>Actions</th></tr>
                  </thead>
                  <tbody>';

							foreach ($lnk as $row) {
								$ok = 0;
								$checkcount++;
								$contents = "";
								if ($row -> nonreciprocal != "1") {
									///	Addition	17/06/2008	mVicenik	Foliovision
									if ($class == "linkbox1") {$class = "linkbox2";} else {$class = "linkbox1";}
									process_url2( $checkcount, $jcounter, $row->reciprocalurl, $row->url, $row->title, $row->status, $row->id, $timeout, $reciprocalurl, -1, $row, $class, $spoof  );
									if($row->id!=-1)
										$jcounter++; //increment the counter for checkboxing
									///	end of addition
								}
						  	}
					  	echo '</tbody>
  			</table><br>* View first 10 lines of the server response';
					  	////
						}
					}
				}
				else {	//	clean non-recursively
					///	Modification	04/07/2008	mVicenik	Foliovision
					$lnk = $wpdb->get_results("SELECT * FROM `$eblex_links` WHERE `status`='1' AND `category`='$l_category' ORDER BY `zindex` DESC, `title` DESC");
					//$lnk = $wpdb->get_results("SELECT * FROM `$eblex_links` WHERE `status`='1' ORDER BY `zindex` DESC");
					///	end of modification
					$checkcount = 0;
					////
					
					echo '<table class="widefat">	
                  <thead>
                  <tr><th></th><th>Title</th><th width="15%">Info</th><th width="20%">Reciprocal URL</th><th>Status</th><th>Response*</th><th>Actions</th></tr>
                  </thead>
                  <tbody>';
					
					foreach ($lnk as $row) {
						$ok = 0;
						$checkcount++;
						$contents = "";
						if ($row -> nonreciprocal != "1") {
							///	Addition	17/06/2008	mVicenik	Foliovision
							if ($class == "linkbox1") {$class = "linkbox2";} else {$class = "linkbox1";}
							process_url2( $checkcount, $jcounter, $row->reciprocalurl, $row->url, $row->title, $row->status, $row->id, $timeout, $reciprocalurl, -1, $row, $class, $spoof );
							if($row->id!=-1)
								$jcounter++; //increment the counter for checkboxing
							///	end of addition
						}
				  	}
				  	
				  	echo '</tbody>
  			</table><br>* View first 10 lines of the server response';
				  	
				  	////
				}
			}
			
			///	end of addition	
		}	//	if ($numberoflinks != "0")
		else
		{
			$noticemsg = "No backlinks to check!";
		}
	}	//	if (is_numeric($timeout))	
	else
	{
		$noticemsg = "Invalid response checking value!";
	}
?>
<script language="JavaScript" type="text/javascript">
function checkdelete()
{
   return true;
	count = document.getElementById('deletecounter').value;
  if (count == "0")
  {
  	//alert("Nothing to delete!");
	alert("Nothing to trash!");
	return false;
  }
  else
  {
	//if (confirm("Are you sure you want to delete "+count+" item(s)?"))
	if (confirm("Are you sure you want to trash "+count+" item(s)?"))
	{
		return true;
	}
	else
	{
		return false;
	}
   }
}
</script>
 <br /><strong><?php echo($checkcount);?> links were checked.</strong><br />
 <input name="counter" type="hidden" id="counter" value="<?php echo($jcounter++); ?>" />
 <input name="deletecounter" type="hidden" id="deletecounter" value="<?php echo("0"); ?>" />
 <p class="submit">
  <!--	<input type="submit" value="<?php _e('Delete selected items') ?>" name="Submit" onclick="return checkdelete()"/>	-->
  <input type="submit" value="<?php _e('Trash selected items') ?>" name="Submit" onclick="return checkdelete()"/>
</p>
</form>
<?php
}
?>
</div>