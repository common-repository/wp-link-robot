<?php
require_once(dirname( __FILE__ ) . '/../../../../wp-admin/admin.php'); 
$title = __('Link Exchange - Search');
//$parent_file = 'wp-link-robot.php';
$today = current_time('mysql', 1); 
require_once(dirname( __FILE__ ) . '/../../../../wp-admin/admin-header.php');

///	Addition	14/07/2008	mVicenik	Foliovision
require_once('wp-link-robot-include.php');
///	end of addition

//$eblex_settings = $table_prefix . "eblex_settings";
$eblex_categories = $table_prefix . "eblex_categories";
$eblex_links = $table_prefix . "eblex_links";

$l_categorycount = $wpdb->get_var("SELECT count(*) FROM `$eblex_categories` WHERE `parent`=''");
$l_subcategorycount = $wpdb->get_var("SELECT count(*) FROM `$eblex_categories` WHERE `parent`!=''");
$l_linkcount = $wpdb->get_var("SELECT count(*) FROM `$eblex_links`");
///	Addition	14/07/2008	mVicenik	Foliovision
$l_trashlinkcount = $wpdb->get_var("SELECT count(*) FROM `$eblex_links` WHERE `status`='2'");
///	end of addition
$l_activelinkcount = $wpdb->get_var("SELECT count(*) FROM `$eblex_links` WHERE `status`='1'");
$l_inactivelinkcount = $wpdb->get_var("SELECT count(*) FROM `$eblex_links` WHERE `status`=0");
$l_reciprocallinkcount = $wpdb->get_var("SELECT count(*) FROM `$eblex_links` WHERE `reciprocalurl`!=''");
$l_linkcount = $wpdb->get_var("SELECT count(*) FROM `$eblex_links`");
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
font-size:16px;
font-weight:bold;
color:#000000;
}

.plink:hover {
color:#0000CC;
}

.purl {
color:#CCCCCC;
font-size:11px;
}

.linkbox1
{
background-color:#FFFFFF;
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

.catbox {
width:95%;
padding:5px;
border:1px #FFFFFF solid;
}

.catbox:hover {
border:1px #EEEEEE solid;
}
.right{
   text-align: right;
   padding-right: 7px;
}
-->
</style>
<?php
if (strlen($_POST['title']) < 3 && $_POST['title']!="")
{
?>
<div id="message1" class="updated fade"><p><?php _e("Search terms are too short!"); ?></p></div>
<?php 
}
?>
<div class="wrap" style="clear:left">
<script language="JavaScript" type="text/javascript">
function deletelink(linkname) {
	var answer = confirm("Delete this link?")
	if (answer){
		return true;
	}
	else{
		return false;
	}
}
</script>
<h2><?php _e('Link search'); ?></h2>

<form id="form1" name="form1" method="post" action="">
  <table class="optiontable">
    <tr valign="top">
      <th scope="row" class="right"><?php _e('Search term:') ?></th>
      <td><input name="title" type="text" id="title" value="<?php echo $_POST['title'];?>" size="40"<?php if ($e_validated != -1 && $e_validated != 0) {echo(" value=\"$e_c_title\"");} ?> /><br />Words with less than 3 characters will be ignored</td>
    </tr>
    <tr valign="top">
      <th scope="row" class="right"><?php _e('Search method:') ?></th>
      <td><label>
        <input name="any" type="radio" value="yes" checked="checked" />
        Search for any terms</label><br />
		<label><input name="any" type="radio" value="no" />
        Search for all terms</label></td>
    </tr>
    <tr valign="top">
      <th scope="row" class="right"><?php _e('Search by:') ?></th>
      <td><select name="region" id="region">
        <option value="title" <?php if ($_POST['region'] == "title") {echo("selected");} ?>>Title</option>
        <option value="url" <?php if ($_POST['region'] == "url") {echo("selected");} ?>>URL</option>
        <option value="rurl" <?php if ($_POST['region'] == "rurl") {echo("selected");} ?>>Reciprocal URL</option>
        <option value="description" <?php if ($_POST['region'] == "description") {echo("selected");} ?>>Description</option>
        <option value="adescription" <?php if ($_POST['region'] == "adescription") {echo("selected");} ?>>Administrator description</option>
        <option value="email" <?php if ($_POST['region'] == "email") {echo("selected");} ?>>E-mail</option>
      </select>      </td>
    </tr>

    <tr valign="top">
      <th scope="row" class="right"><?php _e('Options:') ?></th>
      <td><label>
        <input name="visible" type="checkbox" id="visible" value="yes" <?php if ($_POST['visible'] == "yes" || $_POST['title'] == "") {echo("checked");} ?>/>
        Link is visible</label>
        <label>(active)<br />
        <input name="approved" type="checkbox" id="approved" value="yes" <?php if ($_POST['approved'] == "yes" || $_POST['title'] == "") {echo("checked");} ?> />
        Link is approved</label><br />
        <label><input name="reciprocal" type="checkbox" id="reciprocal" value="yes" <?php if ($_POST['reciprocal'] == "yes" || $_POST['title'] == "") {echo("checked");} ?>/>
        Link has a reciprocal link</label><br />
        <label><input name="trash" type="checkbox" id="trash" value="yes" <?php if ($_POST['trash'] == "yes") {echo("checked");} ?>/>
        Search trash only</label></td>
    </tr>
  </table>
<p class="submit">
<input name="Search" type="submit" id="Search" style="float:left;" value="<?php _e('Search') ?>"/> 
</p>
<br /><br />
</form>
<?php
if ($_POST['title'] != "")
{
	if (strlen($_POST['title']) > 2)
	{
?>
<script language="JavaScript" type="text/javascript">
function hideresults()
{
	document.getElementById("searchresults").innerHTML = "";
}
</script>
<div id="searchresults">
<h2><?php _e('Search results for "'.$_POST['title'].'"'); ?></h2>
<br />
<?php
switch ($_POST['region'])
{
	case "title":
	$region = "title";
	break;
	case "url":
	$region = "url";
	break;
	case "rurl":
	$region = "reciprocalurl";
	break;
	case "description":
	$region = "description";
	break;
	case "adescription":
	$region = "administratorcomment";
	break;
	case "email":
	$region = "email";
	break;
}

$addon = "";

if ($_POST['visible'] == "yes")
{
	$addon .= " AND `status`='1'";
}
else
{
	$addon .= " AND `status`='0'";
}

///	Addition	14/07/2008	mVicenik	Foliovision
if ($_POST['trash'] == "yes")
{
	$addon = " AND `status`='2'";
}
///	end of addition	

if ($_POST['approved'] == "yes")
{
	$addon .= " AND `active`='1'";
}
else
{
	$addon .= " AND `active`='0'";
}

if ($_POST['reciprocal'] == "yes")
{
	$addon .= " AND `nonreciprocal`='0'";
}
else
{
	$addon .= " AND `nonreciprocal`='1'";
}



$term = $_POST['title'];
$searchterms = "";
$x = 0;
///	Modification	15/07/2008	mVicenik	Foliovision
//if ($_POST['any'] == "yes")
//{
///	end of modification
	$searchtermsplit = explode(" ",$term);

	///	Addition	14/07/2008	mVicenik	Foliovision
	$searchterms .= "(";
	///	end of addition	
	for ($i=0;$i<count($searchtermsplit);$i++)
	{
		if (strlen($searchtermsplit[$i]) > 2)
		{
			if ($x != 0)
			{
				$searchterms .= "|| ";
				$x++;
			}
			$searchterms .= "`$region` LIKE '%".$searchtermsplit[$i]."%'";
			///	Addition	14/07/2008	mVicenik	Foliovision
			if(strlen($searchtermsplit[$i+1])>2)
				if ($_POST['any'] == "yes")
					$searchterms .= " OR ";
				else
					$searchterms .= " AND ";
			///	end of addition
		} 
	}
	$searchterms .= ")";
///	Modification	15/07/2008	mVicenik	Foliovision
//}
//else
//{
//	$searchterms = "`$region` LIKE '%$term%'";
//}
///	end of modification

if ($searchterms != "")
{
$search = $wpdb->get_results("SELECT * FROM `$eblex_links` WHERE $searchterms $addon ORDER BY `zindex` DESC,`title` ASC");

/*
echo '<table class="widefat">	
        <thead>
        <tr><th>Title</th><th>Actions</th><th width="15%">Info</th><th>Description</th></tr>
        </thead>
        <tbody>';*/
        
echo '<table class="widefat">
    <thead>
	   <tr><th>Title</th><th>Category</th><th>Description</th><th>Notes</th><th>Reciprocal URL</th><th>Priority Index</th><th>Found</th><th>Actions</th></tr>
    </thead>
    <tbody>';
                  
$resultcount = 0;
foreach ($search as $row)
{
	if ($class == "linkbox1") {$class = "linkbox2";} else {$class = "linkbox1";}
	$categoryparent = $wpdb->get_var("SELECT `parent` FROM `$eblex_categories` WHERE `id`='".$row -> category."'");
	$categoryparentnicename = $wpdb->get_var("SELECT `nicename` FROM `$eblex_categories` WHERE `parent`='".$row -> category."'");
	$category = $wpdb->get_var("SELECT `nicename` FROM `$eblex_categories` WHERE `id`='".$row -> category."'");
	
	if ($categoryparent != "")
	{
		$dlink = "&c=".$categoryparentnicename."&s=".$category;
	}
	else
	{
		$dlink = "&c=".$category;
	}
	
	$resultcount++;
	///	Addition	14/07/2008	mVicenik	Foliovision
//	show_link($row,"&c=",$class,"");
		LinkHTML( $row );
	///	end of addition
	?>
	<!--
	///	Modification	14/07/2008	mVicenik	Foliovision
	<tr class="<?php echo($class); ?>">
	<td>
	<a href="<?php echo($row->url); ?>" class="plink" target="_blank"><?php echo($row->title); if ($row->status == "0") {echo("(inactive)");} ?></a><br />
  <span class="purl"><?php echo(str_replace("/","",str_replace("http://","",$row->url))); ?></span></td>
	<td>
   <a href="wp-link-robot-browse.php<?php echo($dlink."&id=".$row->id."&action=details"); ?>">[Details]</a> 
   <a href="wp-link-robot-browse.php<?php echo($dlink."&id=".$row->id."&action=edit"); ?>">[Edit]</a> 
   <a href="wp-link-robot-browse.php<?php echo($dlink."&id=".$row->id."&action=delete"); ?>" onclick="return deletelink()">[Delete]</a><br />
   </td><td>
	<?php echo(str_replace("\n","<br />",$row->description)); ?>
	</td></td>
	///	end of modification	-->
	<?php
}

echo '</tbody>
  	</table>';
}

if ($resultcount == 0)
{
	_e("No results found.");
}
?>
<p class="submit">
<input name="Search" type="button" id="Search" style="float:left;" onclick="hideresults()" value="<?php _e('Dismiss') ?>"/> 
</p>
</div>
<?php
		}
}
?>
</div>
<?php
//require('./admin-footer.php');
?>
