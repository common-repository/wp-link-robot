<?php
//require_once('admin.php');
//$title = __('Link Exchange - Browse links');
//$parent_file = 'wp-link-robot.php';
//$today = current_time('mysql', 1);
//require_once('admin-header.php');
require_once('functions.php');
$dir = get_bloginfo ('wpurl'). "/wp-includes";


//$dir = str_replace('wp-admin','wp-includes', $dir);
?>

<script src="<?php echo $dir; ?>/js/prototype.js"></script>

<script language="JavaScript" type="text/javascript">
function getPR(page,obj,id) {
	var checksum = "8" + getHash(page);
  	//return 'http://www.google.com/search?client=navclient-auto&ch=' + checksum + '&features=Rank&q=info:' + page;
	var	site = 'http://www.google.com/search?client=navclient-auto&ch=' + checksum + '&features=Rank&q=info:' + page;
	//alert(site);
	//alert('wp-link-robot-query.php?site='+encodeURIComponent(site));
  	new Ajax.Request('../wp-content/plugins/wp-link-robot/source/wp-link-robot-query.php?ch='+checksum+'&site='+encodeURIComponent(page),
	  {
	    method:'get',
	    onSuccess: function(transport){
			var response = transport.responseText || "no response text";
			var filtered = response.replace(/.*\d:\d:/g,"");
			//alert("Success! \n\n" + response + "\n\n" + filtered);
			if(response.match(/HTTP\/1.0 403 Forbidden/gi)!=null) {
				obj.innerHTML = '<b>-</b>';
				return;
			}
			if(filtered.match(/\d$/m)!=null) {
				obj.innerHTML = "<b>"+filtered+"</b>";
				new Ajax.Request('../wp-content/plugins/wp-link-robot/source/wp-link-robot-db.php?id='+id+'&rank='+filtered,
            	  {
            	    method:'get',
            	    onSuccess: function(transport){
            	       //alert(transport.responseText);
            			return;
            	    },
            	    onFailure: function(){ 
            	    	//alert('Something went wrong...');
            	    	return 'Error'; 	    
            	    	}
            	  });
				return;
			}
			obj.innerHTML = '<b>error</b>';
			return;
	    },
	    onFailure: function(){ 
	    	//alert('Something went wrong...');
	    	return 'Error'; 	    
	    	}
	  });
}
  		
	function getHash(value) {
	  var combination = 16909125;
	  var seed = "Mining PageRank is AGAINST GOOGLE'S TERMS OF SERVICE. Yes, I'm talking to you, scammer.";
	  var doIcare = 'no';
	  for(var i = 0; i < value.length; i++ ) {
	    combination ^= seed.charCodeAt(i % seed.length) ^ value.charCodeAt(i);
	    combination = combination >>> 23 | combination << 9;
	  }
	  return hexEncodeU32(combination);
	}
	
	function hexEncodeU32(num) {
	  var result = this.toHex8(num >>> 24);
	  result += this.toHex8(num >>> 16 & 255);
	  result += this.toHex8(num >>> 8 & 255);
	  return result + this.toHex8(num & 255);
	}
	function toHex8(num) {
	  return(num < 16 ? "0" : "") + num.toString(16);
	}

function getGCache(page,obj,id) {
	new Ajax.Request('../wp-content/plugins/wp-link-robot/source/wp-link-robot-query.php?gcache='+encodeURIComponent(page),
	  {
	    method:'get',
	    onSuccess: function(transport){
			var response = transport.responseText;
			var	filtered = response.replace(/ \d*:\d*:\d*(.|\n)*/m,"");
			//alert("Success! \n\n" + response + "\n\n" + filtered);

			obj.innerHTML = "<b>"+filtered+"</b>";
			new Ajax.Request('../wp-content/plugins/wp-link-robot/source/wp-link-robot-db.php?id='+id+'&gcache='+filtered,
            	  {
            	    method:'get',
            	    onSuccess: function(transport){
            	       //alert(transport.responseText);
            			return;
            	    },
            	    onFailure: function(){ 
            	    	//alert('Something went wrong...');
            	    	return 'Error'; 	    
            	    	}
            	  });
			return;
	    },
	    onFailure: function(){ 
	    	//alert('Something went wrong...');
	    	return 'Error'; 	    
	    	}
	  });
}

function getYLinks(page,obj,id) {
/*	page=getDomain(page);
	//alert(page);
	new Ajax.Request('../wp-content/plugins/wp-link-robot/source/wp-link-robot-query.php?ylinks='+page,
	  {
	    method:'get',
	    onSuccess: function(transport){
			var response = transport.responseText || "no response text";
			//alert("Success! \n\n" + response);
			if(response.length>20)
				obj.innerHTML = "<b>error</b>";
			else {
				obj.innerHTML = "<b>"+response+"</b>";
				new Ajax.Request('../wp-content/plugins/wp-link-robot/source/wp-link-robot-db.php?id='+id+'&ylinks='+response,
            	  {
            	    method:'get',
            	    onSuccess: function(transport){
            	       //alert(transport.responseText);
            			return;
            	    },
            	    onFailure: function(){ 
            	    	//alert('Something went wrong...');
            	    	return 'Error'; 	    
            	    	}
            	  });
			}
			return;
	    },
	    onFailure: function(){ 
	    	//alert('Something went wrong...');
	    	return 'Error'; 	    
	    	}
	  });*/
	  return 'Error';
}

function getAlexa(page,obj,id) {
	page=getDomain(page);
	//alert(page);
	new Ajax.Request('../wp-content/plugins/wp-link-robot/source/wp-link-robot-query.php?alexa='+page,
	  {
	    method:'get',
	    onSuccess: function(transport){
			var response = transport.responseText || "no response text";
			//alert("Success! \n\n" + response);
			obj.innerHTML = "<b>"+response+"</b>";
			new Ajax.Request('../wp-content/plugins/wp-link-robot/source/wp-link-robot-db.php?id='+id+'&alexa='+response,
            	  {
            	    method:'get',
            	    onSuccess: function(transport){
            	       //alert(transport.responseText);
            			return;
            	    },
            	    onFailure: function(){ 
            	    	//alert('Something went wrong...');
            	    	return 'Error'; 	    
            	    	}
            	  });
			return;
	    },
	    onFailure: function(){ 
	    	//alert('Something went wrong...');
	    	return 'Error'; 	    
	    	}
	  });
}

function getGCached(page,obj,id) {
	page=getDomain(page);
	//alert(page);
	new Ajax.Request('../wp-content/plugins/wp-link-robot/source/wp-link-robot-query.php?gcached='+page,
	  {
	    method:'get',
	    onSuccess: function(transport){
			var response = transport.responseText || "no response text";
			//alert("Success! \n\n" + response);
			obj.innerHTML = "<b>"+response+"</b>";
			new Ajax.Request('../wp-content/plugins/wp-link-robot/source/wp-link-robot-db.php?id='+id+'&gcached='+response,
                {
                    method:'get',
                    onSuccess: function(transport){
                       // alert(transport.responseText);
                        return;
                    },
            	    onFailure: function(){ 
            	    	//alert('Something went wrong...');
            	    	return 'Error'; 	    
            	    	}
            	  });
			return;
	    },
	    onFailure: function(){ 
	    	//alert('AASomething went wrong...');
	    	return 'Error'; 	    
	    	}
	  });
}

function getDomain(page) {
	page=page.replace(/.*:\/\//,"");
	page=page.replace(/\/.*/,"");
	return page;
}

function link_flag_store(id) {
	$('commentbutton'+id).value = 'Wait';
	new Ajax.Request('../wp-content/plugins/wp-link-robot/source/wp-link-robot-db.php?id='+id+'&comment='+$('comment'+id).value,
        {
            method:'get',
            onSuccess: function(transport){
            	//alert(transport.responseText);
                if(transport.responseText == 'comment ok')
                	$('commentbutton'+id).value = 'Save';
                else
                	alert('Unable to save comment...');
                return;
            },
    	    onFailure: function(){ 
    	    	alert('Unable to save comment...');
    	    	return 'Error'; 	    
    	    	}
    	  });
	return;
}

function link_flag(obj,obj_select,id) {
	obj.innerHTML = '';
	var html = ' <span class="linkbox_orange"><input type="text" size="100" name="comment" id="comment'+id+'" value=""><input type="button" id="commentbutton'+id+'" value="Save" onclick="link_flag_store(\''+id+'\'); return false"></span><br />';
	new Insertion.Before(obj_select,html);
	
  $('commentbutton'+id).value = 'Wait';
		
	var text = '';
	new Ajax.Request('../wp-content/plugins/wp-link-robot/source/wp-link-robot-db.php?id='+id+'&getcomment',
        {
            method:'get',
            onSuccess: function(transport){
            	//alert(transport.responseText);
            	$('comment'+id).value = transport.responseText;
            	$('commentbutton'+id).value = 'Save';
                return;
            },
    	    onFailure: function(){ 
    	    	alert('Unable to save comment...');
    	    	return 'Error'; 	    
    	    	}
    	  });
	return;
}

</script>

<style type="text/css">
<!--
.linkbox_orange
{
background-color: #FFAA55;
border:1px #FFFFFF solid;
width:100%;
padding:3px;
}
-->
</style>

<?php
function show_flag($row,$jcounter) {?> 

<a href="#" id="com<?php echo $jcounter;?>" onclick="
			obj = document.getElementById('com<?php echo $jcounter;?>');
			obj2 = document.getElementById('hdel<?php echo $jcounter;?>');
			link_flag(obj, obj2,'<?php echo $row->id;?>');
			return false;
		"><?php if($row->administratorcomment=='') echo '[Add&nbsp;flag]'; else echo '<span class="linkbox_orange">[Show&nbsp;flag]</span>'?></a>
<?php }

function show_pr($row) { ?> 
	<div style="font-size: 85%">
	PR: <a href="#" id="getPR<?php echo $row->id;?>" onclick="
		obj = document.getElementById('getPR<?php echo $row->id;?>');
		getPR('<?php echo $row->url;?>',obj,'<?php echo $row->id;?>');
		return	false;
		"><?php  if($row->seo_pr=='') echo '?'; else echo $row->seo_pr; ?></a><br />
	<a href="http://www.google.com/search?q=cache%3A<?php echo $row->url;?>" target="_blank">GCache:</a> <a href="#" id="getGCache<?php echo $row->id;?>" onclick="
		obj = document.getElementById('getGCache<?php echo $row->id;?>');
		getGCache('<?php echo urlencode($row->url);?>',obj,'<?php echo $row->id;?>');
		return	false;
		"><?php  if($row->seo_gcache=='') echo '?'; else echo $row->seo_gcache; ?></a><br />
	<!--a href="http://search.yahoo.com/search?p=linkdomain:<?php echo $row->url;?>%20-site:<?php echo $row->url;?>" target="_blank">Y! Links:</a> <a href="#" id="getYLinks<?php echo $row->id;?>" onclick="
		obj = document.getElementById('getYLinks<?php echo $row->id;?>');
		getYLinks('<?php echo $row->url;?>',obj,'<?php echo $row->id;?>');
		return	false;
		"><?php  if($row->seo_ylinks=='') echo '?'; else echo $row->seo_ylinks; ?></a><br /-->
	<a href="http://www.alexa.com/data/details/main?q=<?php  echo preg_replace('/\/.*/','',preg_replace('/.*:\/\//','',$row->url));
    ?>&url=<?php  echo preg_replace('/\/.*/','',preg_replace('/.*:\/\//','',$row->url));?>" target="_blank">Alexa:</a> <a href="#" id="getAlexa<?php echo $row->id;?>" onclick="
		obj = document.getElementById('getAlexa<?php echo $row->id;?>');
		getAlexa('<?php echo $row->url;?>',obj,'<?php echo $row->id;?>');
		return	false;
		"><?php  if($row->seo_alexa=='') echo '?'; else echo $row->seo_alexa; ?></a><br />
	<a href="http://www.google.com/search?q=site%3A<?php  echo preg_replace('/\/.*/','',preg_replace('/.*:\/\//','',$row->url));
    ?>&url=<?php  echo preg_replace('/\/.*/','',preg_replace('/.*:\/\//','',$row->url));?>" target="_blank">Cached:</a> <a href="#" id="getGCached<?php echo $row->id;?>" onclick="
		obj = document.getElementById('getGCached<?php echo $row->id;?>');
		getGCached('<?php echo $row->url;?>',obj,'<?php echo $row->id;?>');
		return	false;
		"><?php  if($row->seo_cached=='') echo '?'; else echo $row->seo_cached; ?></a></div>&nbsp;
		
	<a href="#" id="getAll<?php echo $row->id;?>" onclick="
		obj = document.getElementById('getPR<?php echo $row->id;?>');
		getPR('<?php echo $row->url;?>',obj,'<?php echo $row->id;?>');
		obj = document.getElementById('getGCache<?php echo $row->id;?>');
		getGCache('<?php echo urlencode($row->url);?>',obj,'<?php echo $row->id;?>');
		obj = document.getElementById('getYLinks<?php echo $row->id;?>');
		getYLinks('<?php echo $row->url;?>',obj,'<?php echo $row->id;?>');
		obj = document.getElementById('getAlexa<?php echo $row->id;?>');
		getAlexa('<?php echo $row->url;?>',obj,'<?php echo $row->id;?>');
		obj = document.getElementById('getGCached<?php echo $row->id;?>');
		getGCached('<?php echo $row->url;?>',obj,'<?php echo $row->id;?>');
		return	false;
		">[Refresh]</a>
	<br />
<?php } 

function show_link($row,$dlink,$class,$cattitle) {?>
	<tr class="<?php echo($class); ?>">
<td>
	<?php if (isset($_GET['trash'])) echo $cattitle. '/'; ?>
	<a href="<?php echo($row->url); ?>" class="plink" target="_blank"><?php echo($row->title); if ($row->status == "0") {echo("(inactive)");} ?></a><br />
	<span class="purl"><?php echo(str_replace("","",str_replace("http://","",$row->url))); ?></span></td>
	
	<td>
  <a id="hdel<?php echo $dlink;?>" href="<?php echo getPartRequest("sub") . ($dlink."&id=".$row->id."&action=edit"); ?>">[Edit]</a>
	<?php if (isset($_GET['trash'])) { ?>
		<a href="<?php echo getPartRequest("sub") . ($dlink."&id=".$row->id."&action=delete"); ?>" onclick="return deletelink()">[Delete]</a>
	<?php } ?> 
	<?php if($row->status!=2) {?>
	<a href="<?php echo getPartRequest("sub") . ($dlink."&id=".$row->id."&action=trash"); ?>" onclick="return trashlink()">[Trash]</a>
	<?php } else { ?>
	<a href="<?php echo getPartRequest("sub") . ($dlink."&id=".$row->id."&action=trash&undo"); ?>" onclick="return untrashlink()">[Recycle]</a>
	<?php } 
	 show_flag($row,$row->id);?>
	</td>
	<td>
	<?php
    show_pr($row);
	?>
	</td>
	<td>
	<?php echo(str_replace("\n","<br/>",$row->description)); ?></td>
	</tr>
<?php } ?>