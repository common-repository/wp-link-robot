<?php
$today = current_time('mysql', 1);

// global $table_prefix, $wpdb;
$eblex_categories = $table_prefix . "eblex_categories";
$eblex_links = $table_prefix . "eblex_links";

$l_subcategory = $_GET['c'];
if ($l_subcategory == 'root') {
    header("Location:" . getPartBeforeRequest("sub") . "&sub=categories");
    exit();
} 
$l_id = $wpdb->get_var("SELECT `id` FROM `$eblex_categories` WHERE `nicename`='$l_subcategory'");
$l_parent = $l_id;
$l_count = $wpdb->get_var("SELECT count(*) FROM `$eblex_categories` WHERE `parent`='$l_id'");

if ($l_subcategory == "" || $l_id == "") {
    header("Location:" . getPartBeforeRequest("sub") . "&sub=categories");
    exit();
} 
$l_parentname = $wpdb->get_var("SELECT `title` FROM `$eblex_categories` WHERE `nicename`='$l_subcategory'");

function _mq($var)
{
    if (!get_magic_quotes_gpc()) {
        return addslashes($var);
    } else {
        return $var;
    } 
} 

function _rmq($var)
{
    if (get_magic_quotes_gpc()) {
        return stripslashes($var);
    } else {
        return $var;
    } 
} 

function eblex_niceify($v)
{
    $v = trim(strtolower($v));
    $v = str_replace(" ", "-", $v);
    $v = preg_replace('/[^a-z0-9\-]/i', '', $v);
    $v = str_replace("--", "", $v);
    if (strlen($v) > 250) {
        $newstr = "";
        for ($i = 0;$i <= 250;$i++) {
            $newstr .= $v[$i];
        } 
        $v = $newstr;
    } 
    return $v;
} 
// Add new category
$success = 0;
$cat = $wpdb->get_results("SELECT * FROM `$eblex_categories` WHERE `parent`='$l_id' ORDER BY `zindex` DESC,`title` ASC");
if (isset($_POST['title']) && $_POST['title'] != "") {
    $l_title = $_POST['title'];
    ///	Addition	17/06/2008	mVicenik	Foliopress
    $l_urltitle = $_POST['urltitle'];
    ///	end of addition
    $l_description = $_POST['description'];
    $l_keywords = $_POST['keywords'];
    $l_parent = $_POST['parent'];
    $l_visibility = $_POST['visibility'];
    $l_priorityindex = $_POST['priorityindex'];
    $l_nicename = eblex_niceify($l_title);

    $nduplicate = 0;
    foreach ($cat as $row) {
        if (strtolower($row->nicename) == strtolower($l_nicename)) {
            $nduplicate = 1;
            ///	Addition	17/06/2008	mVicenik	Foliopress
            $noticemsg = "Another subcategory with the same name already exists!";
            ///	end of addition
        } 
    } 

	///	Addition	17/06/2008	mVicenik	Foliovision
    //	This check if there is a category with the same URL-title or URL-title same as nicename of created category
    if( $l_urltitle != '') {
	    foreach ($cat as $row) {
	        if (strtolower($row->urltitle) == strtolower($l_urltitle)) {
	        	$nduplicate = 1;
	            $noticemsg = "Another subcategory with the same URL-title already exists!";
	        } 
	    }
	    foreach ($cat as $row) {
		        if (strtolower($row->nicename) == strtolower($l_urltitle)) {
		        	$nduplicate = 1;
		            $noticemsg = "Another subcategory with the same name as desired URL-title of this subcategory already exists!";
		        } 
	    }
    }
    foreach ($cat as $row) {
        if (strtolower($row->urltitle) == strtolower($l_nicename)) {
        	$nduplicate = 1;
            $noticemsg = "Another subcategory with the URL-title same as desired name of this subcategory already exists!";
        } 
    }   
    ///	end of addition  

    if ($nduplicate == 0) {
        $duplicate = 0;
        foreach ($cat as $row) {
            if (strtolower($row->title) == strtolower($l_title)) {
                $duplicate = 1;
            } 
        } 

        if ($duplicate == 0) {
            if ($l_visibility == "" || $l_visibility == "yes") {
                if ($l_priorityindex != "" && is_numeric($l_priorityindex)) {
                    $l_title = _mq($_POST['title']);
                    ///	Addition	17/06/2008	mVicenik	Foliopress
                    $l_urltitle = $_POST['urltitle'];
                    ///	end of addition
                    $l_description = _mq($_POST['description']);
                    $l_keywords = _mq($_POST['keywords']);
                    $l_parent = _mq($_POST['parent']);
                    $l_time = time();
                    $l_idx = md5(uniqid(rand(), true) . $l_title);

                    if ($l_visibility == "yes") {
                        $l_visibilityvalue = "1";
                    } else {
                        $l_visibilityvalue = "0";
                    } 

                    $validated = 1;
                    if ($l_parent != "") {
                        foreach ($cat as $row) {
                            if ($row->id != $l_parent) {
                                $validated = 1;
                            } 
                        } 
                    } 

                    if ($validated == 1) {
                        $wpdb->query("INSERT INTO `$eblex_categories` (`id`, `parent`, `title`, `urltitle`, `description`, `keywords`, `nicename`, `time`, `visible`, `zindex`) VALUES ('$l_idx', '$l_parent', '$l_title', '$l_urltitle', '$l_description', '$l_keywords', '$l_nicename', '$l_time', '$l_visibilityvalue', '$l_priorityindex');");
                        $success = 1;
                        $cat = $wpdb->get_results("SELECT * FROM `$eblex_categories` WHERE `parent`='$l_parent' ORDER BY `zindex` DESC,`title` ASC");
                        $l_count = $wpdb->get_var("SELECT count(*) FROM `$eblex_categories` WHERE `parent`='$l_parent'");
                    } else {
                        $noticemsg = "Invalid parent!";
                    } 
                } else {
                    $noticemsg = "Invalid priority index!";
                } 
            } else {
                $noticemsg = "Invalid visibility value!";
            } 
        } else {
            $noticemsg = "Another subcategory with the same name already exists!";
        } 
    } else {
    	///	Change	17/06/2008	mVicenik	Foliovision
        //$noticemsg = "Another subcategory with the same name already exists!";
        ///	end of change
    } 
} 
// *********************************EDIT********************************
$e_success = 0;
$e_c_id = $_GET['id'];
$e_c_check = $wpdb->get_var("SELECT `id` FROM `$eblex_categories` WHERE `id`='$e_c_id'");

if ($e_c_check != '') {
    $e_cat = $wpdb->get_results("SELECT * FROM `$eblex_categories` WHERE `id`!='$e_c_id' AND `id`!='$e_c_id' ORDER BY `zindex` DESC,`title` ASC");
    if (isset($_POST['e_title']) && $_POST['e_title'] != "") {
        $e_c_title = $_POST['e_title'];
        ///
        $e_c_urltitle = $_POST['e_urltitle'];
        ///
        $e_c_description = $_POST['e_description'];
        $e_c_keywords = $_POST['e_keywords'];
        $e_c_parent = $_POST['e_category'];
        $e_c_visibility = $_POST['e_visibility'];
        $e_c_priorityindex = $_POST['e_priorityindex'];
        $e_c_nicename = eblex_niceify($e_c_title);
        $e_c_id = $_GET['id'];

        if ($e_c_nicename != "") {
            $nduplicate = 0;
            foreach ($e_cat as $row) {
                if (strtolower($row->nicename) == strtolower($e_c_nicename)) {
                    $e_c_nduplicate = 1;
                    ///	Addition	17/06/2008	mVicenik	Foliovision
                    $noticemsg = "Another subcategory with the same name already exists!";
                    ///	end of addition
                } 
            } 
            
            ///	Addition	17/06/2008	mVicenik	Foliovision
		    //	This check if there is a category with the same URL-title or URL-title same as nicename of created category
		    if( $e_c_urltitle != '') {
			    foreach ($e_cat as $row) {
			        if (strtolower($row->urltitle) == strtolower($e_c_urltitle)) {
			        	$e_c_nduplicate = 1;
			            $noticemsg = "Another subcategory with the same URL-title already exists!";
			        } 
			    }
			    foreach ($e_cat as $row) {
		        if (strtolower($row->nicename) == strtolower($e_c_urltitle)) {
		        	$e_c_nduplicate = 1;
		            $noticemsg = "Another category with the same name as desired URL-title of edited category already exists!";
		        } 
		    }
		    }
		    foreach ($cat as $row) {
		        if (strtolower($row->urltitle) == strtolower($e_c_nicename)) {
		        	$e_c_nduplicate = 1;
		            $noticemsg = "Another subcategory with the URL-title same as desired name of edited subcategory already exists!";
		        } 
		    }   
		    ///	end of addition  

            if ($e_c_nduplicate == 0) {
                $e_c_duplicate = 0;
                foreach ($e_cat as $row) {
                    if (strtolower($row->title) == strtolower($e_c_title)) {
                        $e_c_duplicate = 1;
                    } 
                } 

                if ($e_c_duplicate == 0) {
                    if ($e_c_visibility == "" || $e_c_visibility == "yes") {
                        if ($e_c_priorityindex != "" && is_numeric($e_c_priorityindex)) {
                            $e_c_title = _mq($_POST['e_title']);
                            ///	Addition	17/06/2008	mVicenik	Foliovision
                            $e_c_urltitle = _mq($_POST['e_urltitle']);
                            ///	end of addition
                            $e_c_description = _mq($_POST['e_description']);
                            $e_c_keywords = _mq($_POST['e_keywords']);
                            $e_c_parent = _mq($_POST['e_category']);
                            $e_c_time = time();
                            $e_c_id = $_GET['id'];

                            if ($e_c_visibility == "yes") {
                                $e_c_visibilityvalue = "1";
                            } else {
                                $e_c_visibilityvalue = "0";
                            } 
                            $wpdb->query("UPDATE `$eblex_categories` SET `parent`='$e_c_parent', `title`='$e_c_title', `urltitle`='$e_c_urltitle', `description`='$e_c_description', `keywords`='$e_c_keywords', `nicename`='$e_c_nicename', `visible`='$e_c_visibilityvalue', `zindex`='$e_c_priorityindex' WHERE `id`='$e_c_id'");
                            $e_success = 1;
                        } else {
                            $noticemsg = "Invalid priority index!";
                        } 
                    } else {
                        $noticemsg = "Invalid visibility value!";
                    } 
                } else {
                    $noticemsg = "Another category with the same name already exists!";
                } 
            } else {
            	///	Change	17/06/2008	mVicenik	Foliovision
                //$noticemsg = "Another category with the same name already exists!";
                ///	end of change
            } 
        } else {
            $noticemsg = "Too much non-url friendly characters exist inside your new subcategory name!";
        } 
    } 
} 
// ------------------------------------DELETE------------------------------------
if ($_GET['action'] == "delete" && $_POST['confirm'] == "yes") {
    $catid = $_GET['id'];
    $catidconfirmed = $wpdb->get_var("SELECT `id` FROM `$eblex_categories` WHERE `id`='$catid' LIMIT 1");

    if ($catidconfirmed != '') {
        $wpdb->query("DELETE FROM `$eblex_links` WHERE `category`='" . $catidconfirmed . "'");
        $wpdb->query("DELETE FROM `$eblex_categories` WHERE `id`='" . $catidconfirmed . "' LIMIT 1");
        $noticemsg = "Subcategory successfully deleted!";
    } 
} 
// ------------------------------------------------------------------------------
// -------------------------PAGING---------------------------
$categorycount = $wpdb->get_var("SELECT count(*) FROM `$eblex_categories` WHERE `parent`='$l_id' AND `id`!='0'");
$number_of_pages = ceil($categorycount / 10);
$page = $_GET['p'];

if (!is_numeric($page)) {
    $page = 1;
} 

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

$pg = $page;
$page--;
$limit1 = $page * 10;
$limit2 = 10;
$cat = $wpdb->get_results("SELECT * FROM `$eblex_categories` WHERE `parent`='$l_id' AND `id`!='0' ORDER BY `zindex` DESC,`title` ASC LIMIT $limit1,$limit2");
$checkcount = count($cat);
// ************************************************************************

?>
<?php
if ($noticemsg != "")
{
?>
<div id="message1" class="updated fade"><p><?php _e($noticemsg); ?></p></div>
<?php 
}
if ($success == 1)
{
?>
<div id="message1" class="updated fade"><p><?php _e("New subcategory successfully added!"); ?></p></div>
<?php 
}
?>
<div class="wrap"  style="clear:left">
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
<!--h2><?php _e('Navigation'); ?></h2-->
	<p>
	<div class="catbox">
	<a href="<?php echo getPartBeforeRequest("sub") . "&sub=categories"; if ($_GET['rp'] != "1") {echo("&p=".$_GET['rp']);} ?>" class="catsublink">&laquo; Back to Categories</a>
	</div>
	</p>
<br/>
<?php
//*******************************************VIEW********************************************
if ($_GET['action'] == "view")
{
	$catid = $_GET['id'];
	$details = $wpdb->get_results("SELECT * FROM `$eblex_categories` WHERE `id`='$catid' ORDER BY `zindex` DESC,`title` ASC");
	$numberoflinks = $wpdb->get_var("SELECT count(*) FROM `$eblex_links` WHERE `category`='".$catid."' AND `active`='1'");
	$parentcategoryid = $wpdb->get_var("SELECT `parent` FROM `$eblex_categories` WHERE `id`='".$catid."'");
	$parentcategory = $wpdb->get_var("SELECT `title` FROM `$eblex_categories` WHERE `id`='".$parentcategoryid."'");
	
	if (isset($details[0] -> id))
	{
	?>
	<script language="JavaScript" type="text/javascript">
 	function hideview()
	{
		document.getElementById('viewcategory').innerHTML = "";
	}
    </script>
	<div id="viewcategory">
	<h2><?php _e('Subcategory details'); ?></h2>
	<table>

	<tr><th scope="row" align="right">Title:</th><td> <?php echo($details[0] -> title); ?></td></tr>
	<tr><th scope="row" align="right">URL-Title:</th><td> <?php echo($details[0] -> urltitle); ?></td></tr>
	<tr><th scope="row" align="right">Description:</th><td> <?php echo($details[0] -> description); ?></td></tr>
	<tr><th scope="row" align="right">Keywords:</th><td> <?php if ($details[0] -> keywords != "") {echo($details[0] -> keywords);} else {echo("N/A");} ?></td></tr>
	<tr><th scope="row" align="right">Time added:</th><td> <?php echo(date("H:i:s j.n.Y.",$details[0] -> time)); ?></td></tr>
	<tr><th scope="row" align="right">Number of links:</th><td> <?php echo($numberoflinks); ?> </td></tr>
	<tr><th scope="row" align="right">Parent:</th><td> <?php echo($parentcategory); ?> </td></tr>
	<tr><th scope="row" align="right">Priority index:</th><td> <?php echo($details[0] -> zindex); ?></td></tr>
	<tr><th scope="row" align="right">Status:</th><td> <?php if ($details[0] -> visible == "1") {echo("Visible");} else {echo("Hidden");} ?></td></tr>
  <tr><td></td><td>  <p class="submit">
	<input type="button" value="<?php _e('OK') ?>" name="ok" style="float:left;" onclick="hideview()"/>
	</p></td>
</tr>
	</table>
	</div>
	<?php
}
}
//**********************************************************************************************
//**********************************************EDIT********************************************
if ($_GET['action'] == "edit" && $_POST['e_title'] == "")
{
	$catid = $_GET['id'];
	$details = $wpdb->get_results("SELECT * FROM `$eblex_categories` WHERE `id`='$catid' ORDER BY `zindex` DESC,`title` ASC");
	
	if (isset($details[0] -> id))
	{
	
	$e_validated = 1;
	$e_c_title = $details[0] -> title;

	$e_c_urltitle = $details[0] -> urltitle;

	$e_c_description = $details[0] -> description;
	$e_c_parent = $details[0] -> parent;
	$e_c_keywords = $details[0] -> keywords;
	$e_c_priorityindex = $details[0] -> zindex;
	$e_c_visibility = $details[0] -> visibility;
	$e_c_id = $details[0] -> id;
	echo("<!-- $e_c_parent -->");
	?>
	<script language="JavaScript" type="text/javascript">
 	function hideedit()
	{
		document.getElementById('editcat').innerHTML = "";
	}
    </script>
	<div id="editcat">
	<h2><?php _e('Edit category'); ?></h2>
	<p>
<form action="" method="post" name="form">
<br />
<table class="optiontable"> 
<tr valign="top"> 
<th scope="row" align="right"><?php _e('Title:') ?></th> 
<td><input name="e_title" type="text" id="e_title"<?php if ($e_validated != -1 && $e_validated != 0) {echo(" value=\"$e_c_title\"");} ?> size="40" /></td> 
</tr>
<!-- /// Addition	17/06/2008	mVicenik	Foliovision-->
<tr valign="top"> 
<th scope="row" align="right"><?php _e('URL:') ?></th> 
<td><input name="e_urltitle" type="text" id="e_urltitle"<?php if ($e_validated != -1 && $e_validated != 0) {echo(" value=\"$e_c_urltitle\"");} ?> size="40" /></td> 
</tr>
<!-- /// end of addition -->
<tr valign="top"> 
<th scope="row" align="right"><?php _e('Description:') ?></th> 
<td><textarea name="e_description" cols="40" rows="4" class="code" id="e_description"><?php if ($e_validated != -1 && $e_validated != 0) {echo("$e_c_description");} ?></textarea> <br />
        <?php _e('In a few words, explain what this category is about and what it should contain.') ?></td> 
</tr>
<tr valign="top">
  <th scope="row" align="right"><?php _e('Parent category:') ?></th>
  <td><select name="e_category" id="e_category">
    <option value="0" <?php if ($e_c_parent == "0" || $e_c_parent == "") echo(" selected"); ?> >Root</option>
    <?php
	$categorylist=$wpdb->get_results("SELECT * FROM `$eblex_categories` WHERE `parent`='' ORDER BY `zindex` DESC,`title` ASC");
	foreach ($categorylist as $row)
  	{
	if ($row->id != "0")
	{
	?>
	<option value="<?php echo $row->id;?>" <?php if ($e_c_parent == $row->id) echo(" selected"); ?>>&nbsp;&nbsp;<?php echo $row->title;?></option>
	<?php
	  }
	}
	?>
    </select></td>
</tr> 
<tr valign="top">
  <th scope="row" align="right"><?php _e('Visibility:') ?></th>
  <td><input name="e_visibility" type="checkbox" id="e_visibility" value="yes"<?php if ($noticemsg!="" && $c_visibility=="yes") {echo(" checked");} else {if ($noticemsg=="") {echo(" checked");}} ?>/>
      
        <?php _e(' If unchecked, this category will not be visible to the public.') ?></td>
</tr> 
<tr valign="top"> 
<th scope="row" align="right"><?php _e('Priority index:') ?></th> 
<td><label for="default_role">
  <input name="e_priorityindex" type="text" class="code" id="e_priorityindex"<?php if ($e_validated != -1 && $e_validated != 0) {echo(" value=\"$e_c_priorityindex\"");} else {echo(" value=\"0\"");} ?> size="5"/>
</label></td> 
</tr>
</table>
<p class="submit">
<input name="Dismiss" type="button" id="Dismiss" style="float:left;" onclick="hideedit()" value="<?php _e('Dismiss') ?>"/> 
<input type="reset" value="<?php _e('Reset form') ?>" name="reset" style="float:left;"/>
<input type="submit" value="<?php _e('Update category &raquo;') ?>" name="submit" />
</p>
</form>
	<br/><br/>
	</div>
	<p>
	<?php
}
}
//**********************************************************************************************
//*******************************************DELETE*********************************************
if ($_GET['action'] == "delete" && $_POST['confirm'] != "yes")
{
	$catid = $_GET['id'];
	$details = $wpdb->get_results("SELECT * FROM `$eblex_categories` WHERE `id`='$catid'");
	
	if (isset($details[0] -> id))
	{
	$numberoflinks = $wpdb->get_var("SELECT count(*) FROM `$eblex_links` WHERE `category`='".$catid."' AND `active`='1'");
	?>
	<script language="JavaScript" type="text/javascript">
 	function hidedelete()
	{
		document.getElementById('deletecategory').innerHTML = "";
	}
    </script>
	<div id="deletecategory">
	<h2><?php _e('Subcategory deletion'); ?></h2>
	<p>
	<div><strong>Are you sure you want to delete "<?php echo($details[0] -> title); ?>"?</strong></div>
	
	<?php
	if ($numberoflinks != 0)
	{
	?>
	<div>By doing so, you will also delete <strong><?php echo($numberoflinks); ?></strong> links(s)!</div>
	<?php
	}
	?>
	<form action="" method="post">
    <p class="submit">
	<input name="confirm" type="hidden" value="yes" />
	<input type="submit" value="<?php _e('Yes') ?>" name="ok" style="float:right;"/>
	<input type="button" value="<?php _e('No') ?>" name="ok" style="float:left;" onclick="hidedelete()"/>
	</p>
	</form>
	<br/><br/>
	</p>
	</div>
	<?php
}
}
//**********************************************************************************************
?>

<?php
if ($checkcount != "0") { 
?>
<h2><?php _e('Subcategories in "'.$l_parentname.'"'); ?></h2>

<table id="the-list-x" width="100%" cellpadding="3" cellspacing="3"  class="widefat">
<thead>
  <tr>
    <th width="58%"><strong>Subcategory name</strong></th>
    <th width="9%"><div align="center"><strong>Visibility</strong></div></th>
    <th width="8%"><div align="center"><strong>Priority index</strong></div></th>
    <th width="8%">&nbsp;</th>
    <th width="8%">&nbsp;</th>
    <th width="9%">&nbsp;</th>
  </tr>
  </thead>
  <?php
  foreach ($cat as $row)
  {
  $class = ('alternate' == $class) ? '' : 'alternate';
  $l_linkcount = $wpdb->get_var("SELECT count(*) FROM `$eblex_links` WHERE `category`='".$row->id."' AND `active`='1'");
  ?>
  <tr class="<?php echo $class;?>">
    <td><?php echo $row->title;?> (<?php if ($l_linkcount == 0) {echo("No links");} else {echo($l_linkcount);} ?>)</td>
    <td><div align="center"><?php if ($row->visible==1) {_e('yes');} else {_e('no');} ?></div></td>
    <td><div align="center">
      <?php echo $row->zindex;?>
    </div></td>

    <td><div align="center"><a href="<?php echo (getPartRequest("sub") . "&c=" . $_GET['c']);?>&id=<?php echo $row->id ."&p=".$pg."&rp=".$_GET['rp']; ?>&action=view" class="edit"> <?php _e("[View]"); ?> </a></div></td>
    <td><div align="center"><a href="<?php echo (getPartRequest("sub") . "&c=" . $_GET['c']);?>&id=<?php echo $row->id . "&p=".$pg."&rp=".$_GET['rp']; ?>&action=edit" class="edit"> <?php _e("[Edit]"); ?> </a></div></td>
    <td><div align="center"><a href="<?php echo (getPartRequest("sub") . "&c=" . $_GET['c']);?>&id=<?php echo $row->id . "&p=".$pg."&rp=".$_GET['rp']; ?>&action=delete" class="delete"> <?php _e("[Delete]"); ?> </a></div></td>
  </tr>
  <?php
  }
  ?>
</table>
<br />
<div align="right">
<?php
$realpage = $page+1;
//_e("Page <strong>$realpage</strong> of <strong>$number_of_pages</strong> &nbsp;");
$addon = "?c=".$_GET['c'];
$x = 0;
$threedots = 0;
if ($_GET['rp'] == "")
{
	$rp = "1";
}
else
{
	$rp = $_GET['rp'];
}
$returnaddon = "&rp=".$rp;

for ($i=1;$i<=$number_of_pages;$i++)
{
	if ($i > 5 && $threedots == 0 && $number_of_pages>10)
	{
		echo("...&nbsp;");
		$threedots = 1;
	}
	
	if ($i == $page+1)
	{
		$pageboxstyle = "selected";
	}
	else
	{
		$pageboxstyle = "";
	}
	
	if ($i <= 5 || $i > $number_of_pages-5)
	{
	echo("<a href=\"$addon"."&p=$i$returnaddon"."\" class=\"pagebox$pageboxstyle\">$i</a>&nbsp;");
	}
	$x++;
}
?>
<a href="<?php echo($addon."$returnaddon&p="); ?>" class="pagebox" id="gotopage" onclick="return getpage()">Go to page</a>
</div>
<?php } ?>
<form action="" method="post" name="categoryadd">
<p>  <br/>
  <h2><?php _e('Create new subcategory'); if ($l_count == "0") {_e(" in \"$l_parentname\"");} ?></h2>&nbsp;</p>
<table class="optiontable">
  <tr valign="top">
    <th scope="row"><?php _e('Title:') ?></th>
    <td><input name="title" type="text" id="title" size="40" value="<?php if ($noticemsg!="") {echo($l_title);} ?>"/></td>
  </tr>
  <!-- /// Addition	17/06/2008	mVicenik	Foliovision	-->
  <tr valign="top">
    <th scope="row"><?php _e('URL-Title:') ?></th>
    <td><input name="urltitle" type="text" id="urltitle" size="40" value="<?php if ($noticemsg!="") {echo($l_urltitle);} ?>"/></td>
  </tr>
  <!-- /// end of addition	-->  
  <tr valign="top">
    <th scope="row"><?php _e('Description:') ?></th>
    <td><textarea name="description" cols="42" rows="3" id="description"><?php if ($noticemsg!="") {echo($l_description);} ?></textarea>
      <br />
        <?php _e('In a few words, explain what this subcategory is about and what it should contain.') ?></td>
  </tr>
  <tr valign="top">
    <th scope="row"><?php _e('Parent category:') ?></th>
    <td>
	<?php echo("$l_parentname"); ?>
	<input name="parent" type="hidden" id="parent" value="<?php echo($l_parent); ?>" /></td>
  </tr>
  <tr valign="top">
    <th scope="row"><?php _e('Visibility:') ?>    </th>
    <td><input name="visibility" type="checkbox" id="visibility" value="yes"<?php if ($noticemsg!="" && $l_visibility=="yes") {echo(" checked");} else {if ($noticemsg=="") {echo(" checked");}} ?>/>
      <br />
        <?php _e('If unchecked, this subcategory will not be visible to the public.') ?></td>
  </tr>
  <tr valign="top">
    <th scope="row"><?php _e('Priority index:') ?></th>
    <td><label for="users_can_register">
      <input name="priorityindex" type="text" class="code" id="priorityindex" size="5" value="<?php if ($noticemsg!="") {echo($l_priorityindex);} else {echo("0");} ?>"/>
    </label></td>
  </tr>
</table>
<p class="submit">
<input type="submit" value="<?php _e('Create subcategory &raquo;') ?>" name="submit" />
</p>
</form>
<br/>
<br/>
</div>