<?php
//require_once('admin.php');
$today = current_time('mysql', 1);
//require_once('admin-header.php');
require_once( 'browse-functions.php');
$eblex_categories = $table_prefix . "eblex_categories";
$eblex_links = $table_prefix . "eblex_links";
if ($l_titlecategory == "") {
    $l_titlecategory = "Root";
} 
if ($l_ressubcategory == "") {
    $ccatid = $l_rescategory;
} else {
    $ccatid = $l_ressubcategory;
} 
if ($ccatid == "") {
    $ccatid = "0";
} 
// **********************************************************************************************
// ********************************************** PAGING ****************************************
// **********************************************************************************************
$linkcount = $wpdb->get_var("SELECT count(*) FROM `$eblex_links` WHERE `active`='0' AND `status`='1'");
$number_of_pages = ceil($linkcount / 10);
$page = $_GET['p'];
if ($page > $number_of_pages && $number_of_pages != "0") {
    if ($_GET['action'] == "delete" && $_POST['confirm'] == "yes") {
        $page--;
        if ($page > $number_of_pages) {
            $noticemsg = "Invalid page number!";
        } 
    } else {
        $noticemsg = "Invalid page number!";
    } 
} 
if (!is_numeric($page) || $page == "") {
    $page = 1;
} 
$page--;
$limit1 = $page * 10;
$limit2 = 10;
$link = $wpdb->get_results("SELECT * FROM `$eblex_links` WHERE `active`='0' AND `status`='1' ORDER BY `zindex` DESC,`title` ASC LIMIT $limit1,$limit2");
$checkcount = count($link);
// ************************************************************************
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
.pagebox {
border:1px #CCCCCC solid;
padding:4px;
padding-left:6px;
padding-right:6px;
text-align:center;
}
.pagebox:hover {
background-color:#F5F3FE;
}
.pageboxselected {
border:1px #CCCCCC solid;
padding:4px;
padding-left:6px;
padding-right:6px;
text-align:center;
background-color:#E6E8FF;
}
.pageboxselected:hover {
background-color:#CDCEFE;
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
function getpage()
{
	page = prompt("<?php _e('Enter the page number you would like to jump to:'); ?>");
	if (page != null)
	{
		document.getElementById('gotopage').href = document.getElementById('gotopage').href + page;
	}
	else
	{
		return false;
	}
}
</script>
<?php
if ($noticemsg!="")
{
?>
<div id="message1" class="updated fade"><p><?php _e($noticemsg); ?></p></div>
<?php 
}
?>
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
<div class="wrap" style="clear:left">
<?php
$linkcount = $wpdb->get_var("SELECT count(*) FROM `$eblex_links` WHERE `active`='0' AND `status`='1'"); //are there any links at all?
if ($linkcount > 0)
{
?>
<h2><?php _e('Links awaiting approval'); ?></h2>
<table class="widefat">
    <thead>
	   <tr><th>Details</th><th style="width:40%">Description</th><th>Action</th><th>Visit</th></tr>
    </thead>
    <tbody>
    <tbody>
<?php
$linkcount = 0;
foreach ($link as $row)
{
	if ($class == "linkbox1") {$class = "linkbox2";} else {$class = "linkbox1";}
	$parentcheck = $wpdb->get_var("SELECT `parent` FROM `$eblex_categories` WHERE `id`='".$row->category."'");
	$addonbrowselink = "";
	$categoryname = "";
	$categorynicename = "";
	if ($parentcheck != ""){
		$parentname = $wpdb->get_var("SELECT `title` FROM `$eblex_categories` WHERE `id`='".$parentcheck ."'");
		$parentnicename = $wpdb->get_var("SELECT `nicename` FROM `$eblex_categories` WHERE `id`='".$parentcheck ."'");
		$categoryname = $parentname." &raquo; ";
		$catstring = "subcategory";
	}
	else{
		$categoryname = "";
		$parentnicename = "";
		$catstring = "category";
	}
	$categoryname .= $wpdb->get_var("SELECT `title` FROM `$eblex_categories` WHERE `id`='".$row->category."'");
	$categorynicename .= $wpdb->get_var("SELECT `nicename` FROM `$eblex_categories` WHERE `id`='".$row->category."'");
	if ($parentnicename != ""){
		$addonbrowselink .= "?c=$parentnicename&s=$categorynicename";
	}
	else{
		$addonbrowselink .= "?c=$categorynicename";
	}
	?>
	<?php  
    InboxLinkHTML( $row );
  ?>
	<br />
	<?php
}?>
</tbody>
</table>
<?php
$linkcount++;
}
if ($linkcount == 0)
{
	///	Change	12/06/2008	mVicenik	Foliovision
	_e("<div align=\"center\"><strong>There are no new links to approve.</strong></div>");
	//_e("<div align=\"center\"><strong>There are currently no links to display.</strong></div>");
	///	end of change
}
else
{
?>
<br />
<div align="right">
<?php
$realpage = $page+1;
_e("Page <strong>$realpage</strong> of <strong>$number_of_pages</strong> &nbsp;");
$x = 0;
$threedots = 0;
for ($i=1;$i<=$number_of_pages;$i++){
	if ($i > 5 && $threedots == 0 && $number_of_pages>10){
		echo("...&nbsp;");
		$threedots = 1;
	}
	if ($i == $page+1){
		$pageboxstyle = "selected";
	}
	else{
		$pageboxstyle = "";
	}
	if ($i <= 5 || $i > $number_of_pages-5){
	echo("<a href=\"?p=$i"."\" class=\"pagebox$pageboxstyle\">$i</a>&nbsp;");
	}
	$x++;
}
?>
<a href="<?php echo("?p="); ?>" class="pagebox" id="gotopage" onclick="return getpage()">Go to page</a>
</div>
<?php
}
//require('./admin-footer.php');
?>