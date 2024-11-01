<?php

$today = current_time('mysql', 1);

?>

<style type="text/css">

<!--

.right{

   text-align: right;

   padding-right: 7px;

}

-->

</style>



<?php

//$eblex_settings = $table_prefix . "eblex_settings";

$eblex_categories = $table_prefix . "eblex_categories";

$eblex_links = $table_prefix . "eblex_links";



function eblex_fixinput($str)

{

    $str = str_replace("\"", "&quot;", $str);

    $str = str_replace("<", "&lt;", $str);

    $str = str_replace(">", "&gt;", $str);



    return $str;

} 

// *******************************************DELETE********************************************

if ($_GET['action'] == "delete") {

    $id = $_GET['id'];

    $linkid = $_GET['id'];

    $details = $wpdb->get_results("SELECT * FROM `$eblex_links` WHERE `id`='$linkid' ORDER BY `zindex` DESC,`title` ASC");



    if (isset($details[0]->id)) {

        $wpdb->query("DELETE FROM `$eblex_links` WHERE `id`='$linkid'");

        $noticemsg = "Link deleted!";

    } 

} 

// **********************************************************************************************

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

if (!isset($_GET['id'])){

  $cat = $wpdb->get_results("SELECT * FROM `$eblex_categories` WHERE `parent`='' ORDER BY `zindex` DESC,`title` ASC");

  if (isset($_POST['title']) && $_POST['title'] != "") {

      $l_title = eblex_fixinput($_POST['title']);

      ///	Addition	17/06/2008	mVicenik	Foliovision

      $l_urltitle = eblex_niceify(eblex_fixinput($_POST['urltitle']));

      ///	end of addition

      $l_description = eblex_fixinput($_POST['description']);

      $l_keywords = eblex_fixinput($_POST['keywords']);

      $l_parent = $_POST['parent'];

      $l_visibility = $_POST['visibility'];

      $l_priorityindex = $_POST['priorityindex'];

      $l_nicename = eblex_niceify($l_title);

  

      $nduplicate = 0;

      foreach ($cat as $row) {

          if (strtolower($row->nicename) == strtolower($l_nicename)) {

              $nduplicate = 1;

              ///	Addition	17/06/2008	mVicenik	Foliovision

              $noticemsg = "Another category with the same name already exists!";

              ///	end of addition

          } 

      }

      

      ///	Addition	17/06/2008	mVicenik	Foliovision

      //	This check if there is a category with the same URL-title or URL-title same as nicename of created category

      if( $l_urltitle != '') {

  	    foreach ($cat as $row) {

  	        if (strtolower($row->urltitle) == strtolower($l_urltitle)) {

  	        	$nduplicate = 1;

  	            $noticemsg = "Another category with the same URL-title already exists!";

  	        } 

  	    }

  	    foreach ($cat as $row) {

  		        if (strtolower($row->nicename) == strtolower($l_urltitle)) {

  		        	$nduplicate = 1;

  		            $noticemsg = "Another category with the same name as desired URL-title of category already exists!";

  		        } 

  		    }

      }

      foreach ($cat as $row) {

          if (strtolower($row->urltitle) == strtolower($l_nicename)) {

          	$nduplicate = 1;

              $noticemsg = "Another category with the URL-title same as desired name of category already exists!";

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

                      $l_title = eblex_fixinput(_mq($_POST['title']));

                      ///	Addition	17/06/2008	mVicenik	Foliovision

  				    $l_urltitle = eblex_niceify(eblex_fixinput($_POST['urltitle']));

  				    /// end of addition

                      $l_description = eblex_fixinput(_mq($_POST['description']));

                      $l_keywords = eblex_fixinput(_mq($_POST['keywords']));

                      $l_parent = eblex_fixinput(_mq($_POST['parent']));

                      $l_time = time();

                      $l_id = md5(uniqid(rand(), true) . $l_title);

  

  					if ($l_visibility == "yes") {

                          $l_visibilityvalue = "1";

                      } else {

                          $l_visibilityvalue = "0";

                      } 

  					///	Change	17/06/2008	mVicenik	Foliovision

                      $wpdb->query("INSERT INTO `$eblex_categories` (`id`, `parent`, `title`, `urltitle`, `description`, `keywords`, `nicename`, `time`, `visible`, `zindex`) VALUES ('$l_id', '$l_parent', '$l_title', '$l_urltitle', '$l_description', '$l_keywords', '$l_nicename', '$l_time', '$l_visibilityvalue', '$l_priorityindex');");

                      //$wpdb->query("INSERT INTO `$eblex_categories` (`id`, `parent`, `title`, `description`, `keywords`, `nicename`, `time`, `visible`, `zindex`) VALUES ('$l_id', '$l_parent', '$l_title', '$l_description', '$l_keywords', '$l_nicename', '$l_time', '$l_visibilityvalue', '$l_priorityindex');");

                      ///	end of change

                      $success = 1;

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

      	///	Change	mVicenik	17/06/2008	Foliovision

          //$noticemsg = "Another category with the same name already exists!";

          ///	end of change

      } 

  } 

}

// *********************************EDIT********************************

$e_success = 0;

$e_c_id = $_GET['id'];

$e_c_check = $wpdb->get_var("SELECT `id` FROM `$eblex_categories` WHERE `id`='$e_c_id'");



if ($e_c_check != '') {

    $e_cat = $wpdb->get_results("SELECT * FROM `$eblex_categories` WHERE `parent`='' AND `id`!='$e_c_id' ORDER BY `zindex` DESC,`title` ASC");

    if (isset($_POST['e_title']) && $_POST['e_title'] != "" && $e_c_check != "") {

        $e_c_title = $_POST['e_title'];

        ///	Addition	17/06/2008	mVicenik	Foliovision

	    $e_c_urltitle = eblex_niceify(eblex_fixinput($_POST['e_urltitle']));

    	///	end of addition

        $e_c_description = $_POST['e_description'];

        $e_c_keywords = $_POST['e_keywords'];

        $e_c_parent = $_POST['e_parent'];

        $e_c_visibility = $_POST['e_visibility'];

        $e_c_priorityindex = $_POST['e_priorityindex'];

        $e_c_nicename = eblex_niceify($e_c_title);



        $nduplicate = 0;

        foreach ($e_cat as $row) {

            if (strtolower($row->nicename) == strtolower($e_c_nicename)) {

                $e_c_nduplicate = 1;

                $noticemsg = "Another category with the same name already exists!";

            } 

        } 



	    ///	Addition	17/06/2008	mVicenik	Foliovision

	    //	This check if there is a category with the same URL-title or URL-title same as nicename of created category

	    if( $e_c_urltitle != '') {

	       if($e_cat)

		    foreach ($e_cat as $row) {

		        if (strtolower($row->urltitle) == strtolower($e_c_urltitle)) {

		        	$e_c_nduplicate = 1;

		            $noticemsg = "Another category with the same URL-title already exists!";

		        } 

		    }

		    if($e_cat)

		    foreach ($e_cat as $row) {

		        if (strtolower($row->nicename) == strtolower($e_c_urltitle)) {

		        	$e_c_nduplicate = 1;

		            $noticemsg = "Another category with the same name as desired URL-title of edited category already exists!";

		        } 

		    }

		}

		if($cat)

	    foreach ($cat as $row) {

	        if (strtolower($row->urltitle) == strtolower($e_c_nicename)) {

	        	$e_c_nduplicate = 1;

	            $noticemsg = "Another category with the URL-title same as desired name of edited category already exists!";

	        } 

	    }   

	    ///	end of addition  



        if ($e_c_nduplicate == 0) {

            $e_c_duplicate = 0;

            if($e_cat)

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

					    $e_c_urltitle = eblex_niceify(eblex_fixinput($_POST['e_urltitle']));

					    ///	end of addition

                        $e_c_description = _mq($_POST['e_description']);

                        $e_c_keywords = _mq($_POST['e_keywords']);

                        $e_c_parent = _mq($_POST['e_parent']);

                        $e_c_time = time();



                        if ($e_c_visibility == "yes") {

                            $e_c_visibilityvalue = "1";

                        } else {

                            $e_c_visibilityvalue = "0";

                        } 

						///	Change	17/06/2008	mVicenik	Foliovision

						$wpdb->query("UPDATE `$eblex_categories` SET `parent`='$e_c_parent', `title`='$e_c_title', `urltitle`='$e_c_urltitle', `description`='$e_c_description', `keywords`='$e_c_keywords', `nicename`='$e_c_nicename', `visible`='$e_c_visibilityvalue', `zindex`='$e_c_priorityindex' WHERE `id`='$e_c_id'");

                        //$wpdb->query("UPDATE `$eblex_categories` SET `parent`='$e_c_parent', `title`='$e_c_title', `description`='$e_c_description', `keywords`='$e_c_keywords', `nicename`='$e_c_nicename', `visible`='$e_c_visibilityvalue', `zindex`='$e_c_priorityindex' WHERE `id`='$e_c_id'");

                        ///	end of change

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

    } 

} 

// ------------------------------------DELETE------------------------------------

if ($_GET['action'] == "delete" && $_POST['confirm'] == "yes") {

    $catid = $_GET['id'];

    $catidconfirmed = $wpdb->get_var("SELECT `id` FROM `$eblex_categories` WHERE `id`='$catid' LIMIT 1");



    if ($catidconfirmed != '') {

        $wpdb->query("DELETE FROM `$eblex_links` WHERE `category`='" . $catidconfirmed . "'");

        $wpdb->query("DELETE FROM `$eblex_categories` WHERE `parent`='" . $catidconfirmed . "'");

        $wpdb->query("DELETE FROM `$eblex_categories` WHERE `id`='" . $catidconfirmed . "' LIMIT 1");

        $noticemsg = "Category successfully deleted!";

    } 

} 

// ------------------------------------------------------------------------------

// -------------------------PAGING---------------------------

$categorycount = $wpdb->get_var("SELECT count(*) FROM `$eblex_categories` WHERE `parent`='' AND `id`!='0'");

$number_of_pages = ceil($categorycount / 20);

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



if (!is_numeric($page)) {

    $page = 1;

} 



$page--;

$limit1 = $page * 20;

$limit2 = 20;

$cat = $wpdb->get_results("SELECT * FROM `$eblex_categories` WHERE `parent`='' AND `id`!='0' ORDER BY `zindex` DESC,`title` ASC LIMIT $limit1,$limit2");

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

<div id="message1" class="updated fade"><p><?php _e("New category successfully added!"); ?></p></div>

<?php 

}

if ($e_success == 1)

{

?>

<div id="message1" class="updated fade"><p><?php _e("Category successfully updated!"); ?></p></div>

<?php 

}

?>

<div class="wrap" style="clear:left">



<?php

//*******************************************VIEW********************************************

if ($_GET['action'] == "view")

{

	$catid = $_GET['id'];

	$details = $wpdb->get_results("SELECT * FROM `$eblex_categories` WHERE `id`='$catid' ORDER BY `zindex` DESC,`title` ASC");

	$numberoflinks = $wpdb->get_var("SELECT count(*) FROM `$eblex_links` WHERE `category`='".$catid."' AND `active`='1'");

	$numberofsubcategories = $wpdb->get_var("SELECT count(*) FROM `$eblex_categories` WHERE `parent`='".$catid."'");

	

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

	<h2><?php _e('Category details'); ?></h2>

	<p>

	<div><strong>Title:</strong> <?php echo($details[0] -> title); ?></div>

	<!-- /// Addition	17/06/2008	mVicenik	Foliovision -->

	<div><strong>URL-Title:</strong> <?php echo($details[0] -> urltitle); ?></div>

	<!-- /// end of addition -->

	<div><strong>Description:</strong> <?php echo($details[0] -> description); ?></div>

	<div><strong>Keywords:</strong> <?php if ($details[0] -> keywords != "") {echo($details[0] -> keywords);} else {echo("N/A");} ?></div>

	<div><strong>Time added:</strong> <?php echo(date("H:i:s j.n.Y.",$details[0] -> time)); ?></div>

	<div><strong>Number of links:</strong> <?php echo($numberoflinks); ?> </div>

	<div><strong>Number of subcategories:</strong> <?php echo($numberofsubcategories); ?> </div>

	<div><strong>Priority index:</strong> <?php echo($details[0] -> zindex); ?></div>

	<div><strong>Status:</strong> <?php if ($details[0] -> visible == "1") {echo("Visible");} else {echo("Hidden");} ?></div>

    <p class="submit">

	<input type="button" value="<?php _e('OK') ?>" name="ok" style="float:left;" onclick="hideview()"/>

	</p>

	<br /><br />

	</p>

	</div>

	<?php

}

}

//**********************************************************************************************

//*******************************************EDIT********************************************

if ($_GET['action'] == "edit" && $e_success != 1)

{

	$catid = $_GET['id'];

	$details = $wpdb->get_results("SELECT * FROM `$eblex_categories` WHERE `id`='$catid' ORDER BY `zindex` DESC,`title` ASC");

	//$e_cat=$wpdb->get_results("SELECT * FROM `$eblex_categories` WHERE `parent`='' ORDER BY `zindex` DESC,`title` ASC");

	

	if (isset($details[0] -> id))

	{

	

	$e_validated = 1;

	$e_c_title = $details[0] -> title;

	///	Addition	17/06/2008	mVicenik	Foliovision

	$e_c_urltitle = $details[0] -> urltitle;

	///	end of addition

	$e_c_description = $details[0] -> description;

	$e_c_parent = $details[0] -> parent;

	$e_c_keywords = $details[0] -> keywords;

	$e_c_priorityindex = $details[0] -> zindex;

	$e_c_visibility = $details[0] -> visible;

	$e_c_id = $details[0] -> id;

	

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

<th scope="row"  class="right"><?php _e('Title:') ?></th> 

<td><input name="e_title" type="text" id="e_title"<?php if ($e_validated != -1 && $e_validated != 0) {echo(" value=\"$e_c_title\"");} ?> size="52" /></td> 

</tr>

<!-- /// Addition	17/06/2008	mVicenik	Foliovision -->

<th scope="row" class="right"><?php _e('URL-Title:') ?></th> 

<td><input name="e_urltitle" type="text" id="e_urltitle"<?php if ($e_validated != -1 && $e_validated != 0) {echo(" value=\"$e_c_urltitle\"");} ?> size="52" /></td> 

</tr>

<!-- /// end of addition	-->

<tr valign="top"> 

<th scope="row" class="right"><?php _e('Description:') ?></th> 

<td><textarea name="e_description" cols="42" rows="4" id="e_description"><?php if ($e_validated != -1 && $e_validated != 0) {echo(stripslashes($e_c_description));} ?></textarea> <br />

        <?php _e('In a few words, explain what this category is about and what it should contain.') ?></td> 

</tr>

<tr valign="top">

  <th scope="row" class="right"><?php _e('Keywords:') ?></th>

  <td><input name="e_keywords" type="text" id="e_keywords"<?php if ($e_validated != -1 && $e_validated != 0) {echo(" value=\"$e_c_keywords\"");} ?> size="52" />

      <br />

    <?php _e("Enter some comma separated keywords for the &quot;keywords&quot; meta tag."); ?></td>

</tr>

<tr valign="top">

  <th scope="row" class="right"><?php _e('Visibility:') ?></th>

  <td><input name="e_visibility" type="checkbox" id="e_visibility" value="yes"<?php if ($e_c_visibility=="1") {echo(" checked");} ?>/>

      

        <?php _e('If unchecked, this category will not be visible to the public.') ?></td>

</tr> 

<tr valign="top"> 

<th scope="row" class="right"><?php _e('Priority index:') ?></th> 

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

	<br /><br />

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

	$numberofsubcategories = $wpdb->get_var("SELECT count(*) FROM `$eblex_categories` WHERE `parent`='".$catid."'");

	

	$deletesubcategories = $wpdb->get_results("SELECT * FROM `$eblex_categories` WHERE `parent`='".$catid."'");

	

	foreach ($deletesubcategories as $row)

	{

		$numberoflinks += $wpdb->get_var("SELECT count(*) FROM `$eblex_links` WHERE `category`='".$row->id."' AND `active`='1'");

	}

	

	?>

	<script language="JavaScript" type="text/javascript">

 	function hidedelete()

	{

		document.getElementById('deletecategory').innerHTML = "";

	}

    </script>

	<div id="deletecategory">

	<h2><?php _e('Category deletion'); ?></h2>

	<p>

	<div><strong>Are you sure you want to delete "<?php echo($details[0] -> title); ?>"?</strong></div>

	

	<?php

	if ($numberofsubcategories != 0 && $numberoflinks != 0)

	{

	?>

	<div>By doing so, you will also delete <strong><?php echo($numberofsubcategories); ?></strong> subcategories and <strong><?php echo($numberoflinks); ?></strong> links(s)!</div>

	<?php

	}

	?>

	

	<?php

	if ($numberofsubcategories == 0 && $numberoflinks != 0)

	{

	?>

	<div>By doing so, you will also delete <strong><?php echo($numberoflinks); ?></strong> links(s)!</div>

	<?php

	}

	?>

	

	<?php

	if ($numberofsubcategories != 0 && $numberoflinks == 0)

	{

	?>

	<div>By doing so, you will also delete <strong><?php echo($numberofsubcategories); ?></strong> subcategories!</div>

	<?php

	}

	?>

	

	<form action="" method="post">

    <p class="submit">

	<input name="confirm" type="hidden" value="yes" />

	<input type="submit" value="<?php _e('Yes') ?>" name="ok"/>

	<input type="button" value="<?php _e('No') ?>" name="ok" onclick="hidedelete()"/>

	</p>

	</form>

	<br /><br />

	</p>

	</div>

	<?php

}

}

//**********************************************************************************************

?>



<?php

if ($checkcount != 0)

{

?>

<h2><?php _e('Categories'); ?></h2>

<style type="text/css">

<!--

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

<table id="the-list-x" width="100%" cellpadding="3" cellspacing="3" class="widefat">

<thead>  <tr>

    <th width="47%">Category name</th>

    <th width="11%">Subcategories</th>

    <th width="9%">Visibility</th>

    <th width="8%">Priority index</th>

    <th width="8%">&nbsp;</th>

    <th width="8%">&nbsp;</th>

    <th width="9%">&nbsp;</th>

  </tr>

  </thead>

  <?php

foreach ($cat as $row)

{

  if ($row -> id != '0')

  {

  $class = ('alternate' == $class) ? '' : 'alternate';

  $l_count = $wpdb->get_var("SELECT count(*) FROM `$eblex_categories` WHERE `parent`='".$row->id."'");

  $l_linkcount = $wpdb->get_var("SELECT count(*) FROM `$eblex_links` WHERE `category`='".$row->id."' AND `active`='1'");

  $l_subcat = $wpdb->get_results("SELECT * FROM `$eblex_categories` WHERE `parent`='".$row->id."'");

 

  foreach ($l_subcat as $subrow)

  {

  $l_sublinkcount = $wpdb->get_var("SELECT count(*) FROM `$eblex_links` WHERE `category`='".$subrow->id."' AND `active`='1'");

  $l_linkcount += $l_sublinkcount;

  }

  ?>

  <tr class="<?php echo $class;?>">

    <td><a href="<?php echo getPartBeforeRequest("sub") . "&sub=subcategories&rp="; if ($_GET['p'] == "") {echo("1");} else {echo($_GET['p']);} ?>&c=<?php echo $row->nicename;?>"><?php echo $row->title;?> <?php if ($l_linkcount == "0") {echo("(No links)");} else {echo("(".$l_linkcount.")");} ?></a> </td>

    <td><div align="center">

      <?php if ($l_count == "0") {_e('None');} else {_e($l_count);} ?>

    </div></td>

    <td><div align="center"><?php if ($row->visible==1) {_e('yes');} else {_e('no');} ?></div></td>

    <td><div align="center">

      <?php echo $row->zindex;?>

    </div></td>

    <td><div align="center"><a href="<?php echo (getPartRequest("sub") . "&id=" . $row->id);?>&action=view&p=<?php echo ($page+1);?>" class="edit"> <?php _e("View"); ?> </a></div></td>

    <td><div align="center"><a href="<?php echo (getPartRequest("sub") . "&id=" . $row->id);?>&action=edit&p=<?php echo ($page+1);?>" class="edit"> <?php _e("Edit"); ?> </a></div></td>

    <td><div align="center"><a href="<?php echo (getPartRequest("sub") . "&id=" . $row->id);?>&action=delete&p=<?php echo ($page+1);?>" class="delete"> <?php _e("Delete"); ?> </a></div></td>

  </tr>

  <?php

  }

}

  ?>



<tr><td colspan="7">

<div style="float:right">Go to page: 

<?php

$realpage = $page+1;

//_e("Page <strong>$realpage</strong> of <strong>$number_of_pages</strong> &nbsp;");

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

   	echo('<a href="tools.php?page='.$_GET['page'].'&sub='. $_GET['sub'] .'&p='.$i.'" >'.$i.'</a>&nbsp;');

	}

	$x++;

}

?>

</div>

</td></tr>

</table>

<?php

}

?>

<form action="" method="post" name="categoryadd">

<p>  <br />

  <h2><?php _e('Create new category'); ?></h2>&nbsp;</p>

<table class="optiontable">

  <tr valign="top">

    <th scope="row" class="right"><?php _e('Title:') ?></th>

    <td><input name="title" type="text" id="title" size="52" value="<?php if ($noticemsg!="") {echo($l_title);} ?>"/></td>

  </tr>

  <!-- /// Addition	17/06/2008	mVicenik	Foliovision -->

  <tr valign="top">

    <th scope="row" class="right"><?php _e('URL-Title:') ?></th>

    <td><input name="urltitle" type="text" id="urltitle" size="52" value="<?php if ($noticemsg!="") {echo($l_urltitle);} ?>"/></td>

  </tr>

  <!-- /// end of addition	-->

  <tr valign="top">

    <th scope="row" class="right"><?php _e('Description:') ?></th>

    <td><textarea name="description" cols="42" rows="3" id="description"><?php if ($noticemsg!="") {echo($l_description);} ?></textarea>

      <br />

        <?php _e('In a few words, explain what this category is about and what it should contain.') ?></td>

  </tr>

  <tr valign="top">

    <th scope="row" class="right"><?php _e('Keywords:') ?></th>

    <td><input name="keywords" type="text" id="keywords"<?php if ($e_validated != -1 && $e_validated != 0) {echo(" value=\"$e_keywords\"");} ?> size="52" />

        <br />

        <?php _e("Enter some comma separated keywords for the &quot;keywords&quot; meta tag."); ?></td>

  </tr>

  <tr valign="top">

    <th scope="row" class="right"><?php _e('Visibility:') ?>    </th>

    <td><input name="visibility" type="checkbox" id="visibility" value="yes"<?php if ($noticemsg!="" && $l_visibility=="yes") {echo(" checked");} else {if ($noticemsg=="") {echo(" checked");}} ?>/>

     

        <?php _e('If unchecked, this category will not be visible to the public.') ?></td>

  </tr>

  <tr valign="top">

    <th scope="row" class="right"><?php _e('Priority index:') ?></th>

    <td><label for="users_can_register">

      <input name="priorityindex" type="text" class="code" id="priorityindex" size="5" value="<?php if ($noticemsg!="") {echo($l_priorityindex);} else {echo("0");} ?>"/>

    </label></td>

  </tr>

</table>

<p class="submit">

<input type="submit" value="<?php _e('Create category &raquo;') ?>" name="submit" />

</p>

</form>

<br/>

<br/>

</div>