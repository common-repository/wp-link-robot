<?php

//require_once('admin.php');

$title = __('Link Exchange - Quick add');

//$parent_file = 'wp-link-robot.php';

$today = current_time('mysql', 1);

//require_once('admin-header.php');

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



$cat = $wpdb->get_results("SELECT * FROM `$eblex_categories` WHERE `parent`='' ORDER BY `zindex` DESC,`title` ASC");

function check_email2($str)

{

    if (ereg("^.+@.+\..+$", $str))

        return 1;

    else

        return 0;

} 



$validated = -1;



if (isset($_POST['title']) && $_POST['title'] != "") {

    $validated = 0;

    $l_title = $_POST['title'];

    $l_description = $_POST['description'];

    if ($_POST['nonreciprocal'] == "yes") {

        $l_nonreciprocal = "1";

    } else {

        $l_nonreciprocal = "0";

    } 

    $l_url = $_POST['url'];

    $l_rurl = $_POST['rurl'];

    if(strpos($l_url,'http://')===false) $l_url = 'http://'.$l_url;

    if(strpos($l_rurl,'http://')===false) $l_rurl = 'http://'.$l_rurl;

    $l_email = $_POST['email'];

    $l_priorityindex = $_POST['priorityindex'];

    $l_acomment = $_POST['acomment'];

    $l_status = $_POST['status'];

    $l_category = $_POST['category'];

    $l_id = md5(uniqid(rand(), true) . $l_title);

    $l_time = time();



    if ($l_url != "" && $l_url != "http://") {

        if ($l_priorityindex != "" && is_numeric($l_priorityindex)) {

            if ($l_category != "") {

                foreach ($cat as $row) {

                    if ($row->id != $l_parent) {

                        $validated = 1;

                    } 

                } 



                if ($validated == 1) {

                    if ($l_email != "" && check_email2($l_email) == 1) {

                        if ($l_status == "0" || $l_status == "1") {

                            $wpdb->query("INSERT INTO `$eblex_links` (`title`, `active`, `nonreciprocal`, `url`, `category`, `description`, `email`, `reciprocalurl`, `status`, `time`, `administratorcomment`, `zindex`, `id`) VALUES ('$l_title', '1', '$l_nonreciprocal', '$l_url', '$l_category', '$l_description', '$l_email', '$l_rurl', '$l_status', '$l_time', '$l_acomment', '$l_priorityindex', '$l_id');");

                            $noticemsg = "Link successfully added!";

                            $validated = 2;

                        } else {

                            $noticemsg = "Invalid status value!";

                        } 

                    } else {

                        $noticemsg = "Invalid e-mail address!";

                    } 

                } else {

                    $noticemsg = "Invalid category!";

                } 

            } else {

                $noticemsg = "No category selected!";

            } 

        } else {

            $noticemsg = "Priority index field is invalid!";

        } 

    } else {

        $noticemsg = "URL is invalid!";

    } 

} 



?>

<?php

if ($noticemsg!="")

{

?>

<div id="message1" class="updated fade"><p><?php _e($noticemsg); ?></p></div>

<?php 

}

?>

<div class="wrap" style="clear:left">



<h2><?php _e('Quick add'); ?></h2>



<form action="" method="post" name="form">

<br />

<table class="optiontable"> 

<tr valign="top"> 

<th scope="row" class="right"><?php _e('Title:') ?></th> 

<td><input name="title" type="text" id="title" <?php if ($validated != -1 && $validated != 0) {echo(" value=\"$l_title\"");} ?> size="45" /></td> 

</tr> 

<tr valign="top"> 

<th scope="row" class="right"><?php _e('URL:') ?></th>

<!--	Modification	14/07/2008	mVicenik	Foliovision	--> 

<td><input name="url" type="text" id="url" <?php if ($validated != -1 && $validated != 0) {echo(" value=\"$l_url\"");} else {echo(" value=\"\"");} ?> size="45" onfocus="document.getElementById('url').select()"/></td> 

<!-- <td><input name="url" type="text" id="url" style="width: 95%"<?php if ($validated != -1 && $validated != 0) {echo(" value=\"$l_url\"");} else {echo(" value=\"http://\"");} ?> size="45" onfocus="document.getElementById('url').select()"/></td>

	end of modification	-->

</tr>

<tr valign="top">

  <th scope="row" class="right"><?php _e('Category:') ?></th>

  <td><select name="category"  id="category">

    <option value="0" style="font-weight:bold;" <?php if ($l_category == "0" || $l_category == "") echo(" selected"); ?> >Root</option>

    <?php

    

	foreach ($cat as $row)

  	{

   	if ($row->id != "0")

   	{

      	?>

      	<option value="<?=$row->id;?>" "<?php if ($l_category == $row->id) echo(" selected"); ?>>&nbsp;&nbsp;<?=$row->title;?></option>

      	<?php

      	$subcat=$wpdb->get_results("SELECT * FROM `$eblex_categories` WHERE `parent`='".$row->id."' ORDER BY `zindex` DESC,`title` ASC");

   	

   		foreach ($subcat as $subrow)

     		{

   		?>

   		<option value="<?php if ($subrow->parent!="") {echo($subrow->id);} ?>" style="font-style:italic; font-size:12px;"<?php if ($l_category == $subrow->id) echo(" selected"); ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$subrow->title;?></option>

   		<?php

   		}

   	}

	}

	?>

    </select></td>

</tr> 

<tr valign="top"> 

<th scope="row" class="right"><?php _e('Description:') ?></th> 

<td><textarea name="description" cols="33" rows="4" class="code" id="description"><?php if ($validated != -1 && $validated != 0) {echo("$l_description");} ?></textarea></td> 

</tr> 

<tr valign="top">

<th scope="row" class="right"><?php _e('E-mail:') ?></th>

<td><input name="email" type="text" id="email"<?php if ($validated != -1 && $validated != 0) {echo(" value=\"$l_email\"");} ?> size="45" />

<br />

<?php _e('E-mail of the party you are exchanging links with'); ?></td>

</tr>

<tr valign="top"> 

<th scope="row" class="right"><?php _e('Reciprocal URL:') ?> </th>

<!--	Modification	14/07/2008	mVicenik	Foliovision	--> 

<td><input name="rurl" type="text" id="rurl"<?php if ($validated != -1 && $validated != 0) {echo(" value=\"$l_rurl\"");} else {echo(" value=\"\"");} ?>  size="45" onfocus="document.getElementById('rurl').select()"/><br />

<!--	<td><input name="rurl" type="text" class="code" id="rurl"<?php if ($validated != -1 && $validated != 0) {echo(" value=\"$l_rurl\"");} else {echo(" value=\"http://\"");} ?> size="45" onfocus="document.getElementById('rurl').select()"/><br/>

	end of modification	-->

  <label>

  <input name="nonreciprocal" type="checkbox" id="nonreciprocal" value="yes" />

  Non reciprocal</label></td> 

</tr>

<tr valign="top"> 

<th scope="row" class="right"><?php _e('Administrator<br />comment:') ?></th> 

<td> <label for="users_can_register">

  <textarea name="acomment" cols="33" rows="4" class="code" id="acomment"><?php if ($validated != -1 && $validated != 0) {echo("$l_acomment");} ?></textarea>

</label></td> 

</tr> 

<tr valign="top"> 

<th scope="row" class="right"><?php _e('Priority index:') ?></th> 

<td><label for="default_role">

  <input name="priorityindex" type="text" class="code" id="priorityindex"<?php if ($validated != -1 && $validated != 0) {echo(" value=\"$l_priorityindex\"");} else {echo(" value=\"0\"");} ?> size="5"/>

</label></td> 

</tr>

<tr valign="top">

  <th scope="row" class="right"><?php _e('Status:') ?></th>

  <td><label>

    <input name="status" type="radio" value="1"<?php if ($l_status == "1" || $l_status == "") {echo(" checked=\"checked\"");} ?>/>

    Active </label>

	<label>

    <input name="status" type="radio" value="0"<?php if ($l_status == "0") {echo(" checked=\"checked\"");} ?>/>

    Inactive</label></td>

</tr> 

</table>

<p class="submit">

<!--input type="reset" value="<?php _e('Reset form') ?>" name="reset" style="float:left;"/-->

<input type="submit" value="<?php _e('Submit link &raquo;') ?>" name="submit" />

</p>

</form>

<br />

</div>

<?php

//require('./admin-footer.php');

?>

