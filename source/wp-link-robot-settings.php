<?php
require_once(dirname( __FILE__ ) . '/../../../../wp-admin/admin.php');
$title = __('Link Exchange - Settings');
//$parent_file = 'wp-link-robot.php';
$today = current_time('mysql', 1);
require_once(dirname( __FILE__ ) . '/../../../../wp-admin/admin-header.php');
require_once('functions.php');

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
// -------------------------- UPDATE SETTINGS ----------------------------
$sp_linksperpage = $_POST['lpp'];
$sp_maxdescsize = $_POST['maxdescsize'];
if (!is_numeric($sp_linksperpage) || $sp_linksperpage < 2 || $sp_linksperpage > 3000) {
    if ($sp_linksperpage != "") {
        $noticemsg = "Invalid number of links per page!";
    } 
} else {
    if (!is_numeric($sp_maxdescsize) || $sp_maxdescsize < 0 || $sp_maxdescsize > 100000) {
        $noticemsg = "Invalid number of characters for description!";
    } else {
        if ($_POST['seo'] == "yes") {
            $sp_seofriendly = "1";
        } else {
            $sp_seofriendly = "0";
        } 
        if ($_POST['linkdescription'] == "yes") {
            $sp_description = "1";
        } else {
            $sp_description = "0";
        } 
        if ($_POST['linkurl'] == "yes") {
            $sp_url = "1";
        } else {
            $sp_url = "0";
        } 
        if ($_POST['linksapproval'] == "yes") {
            $sp_approval = "1";
        } else {
            $sp_approval = "0";
        } 
        if ($_POST['nonreciprocal'] == "yes") {
            $sp_nonreciprocal = "1";
        } else {
            $sp_nonreciprocal = "0";
        } 
        if ($_POST['validate'] == "yes") {
            $sp_validate = "1";
        } else {
            $sp_validate = "0";
        } 
        if ($_POST['deactivate'] == "yes") {
            $sp_deactivate = "1";
        } else {
            $sp_deactivate = "0";
        } 
        if ($_POST['email'] == "yes") {
            $sp_email = "1";
        } else {
            $sp_email = "0";
        } 
        if ($_POST['spoof'] == "yes") {
            $sp_spoof = "1";
        } else {
            $sp_spoof = "0";
        } 
        if ($_POST['email2'] == "yes") {
            $sp_email2 = "1";
        } else {
            $sp_email2 = "0";
        } 
        if ($_POST['email3'] == "yes") {
            $sp_email3 = "1";
        } else {
            $sp_email3 = "0";
        } 
        if ($_POST['showcategorydescription'] == "yes") {
            $sp_showcategorydescription = "1";
        } else {
            $sp_showcategorydescription = "0";
        } 
        if ($_POST['captcha'] == "yes") {
            $sp_captcha = "1";
        } else {
            $sp_captcha = "0";
        }
     
        $sp_is_active = "1";
        $sp_emailto = eblex_fixinput($_POST['emailto']);
        $sp_reciprocalurl = eblex_fixinput($_POST['reciprocalurl']);
        $sp_emailt1 = eblex_fixinput($_POST['emailt1']);
        $sp_emailt2 = eblex_fixinput($_POST['emailt2']);
        $sp_keywords = eblex_fixinput($_POST['keywords']);
        $sp_pagedescription = eblex_fixinput($_POST['pagedescription']);
        $sp_name = eblex_fixinput($_POST['mname']);
        $sp_pageslug = eblex_fixinput($_POST['pageslug']);
        $sp_emailfrom = eblex_fixinput($_POST['emailfrom']);


        update_option('wp_link_robot_seofriendly', $sp_seofriendly);    
        update_option('wp_link_robot_description', $sp_description);    
        update_option('wp_link_robot_url', $sp_url);    
        update_option('wp_link_robot_linksperpage', $sp_linksperpage);    
        update_option('wp_link_robot_approval', $sp_approval);    
        update_option('wp_link_robot_nonreciprocal', $sp_nonreciprocal);    
        update_option('wp_link_robot_validate', $sp_validate);    
        update_option('wp_link_robot_deactivate', $sp_deactivate);    
        update_option('wp_link_robot_is_active', $sp_is_active);    
        update_option('wp_link_robot_maxdescsize', $sp_maxdescsize);    
        update_option('wp_link_robot_reciprocalurl', $sp_reciprocalurl);    
        update_option('wp_link_robot_spoof', $sp_spoof);    
        update_option('wp_link_robot_email', $sp_email);    
        update_option('wp_link_robot_emailfrom', $sp_emailfrom);    
        update_option('wp_link_robot_emailto', $sp_emailto);    
        update_option('wp_link_robot_email2', $sp_email2);    
        update_option('wp_link_robot_email3', $sp_email3);    
        update_option('wp_link_robot_emailt1', $sp_emailt1);    
        update_option('wp_link_robot_emailt2', $sp_emailt2);    
        update_option('wp_link_robot_showcategorydescription', $sp_showcategorydescription);    
        update_option('wp_link_robot_pagedescription', $sp_pagedescription);    
        update_option('wp_link_robot_captcha', $sp_captcha);    
        update_option('wp_link_robot_name', $sp_name);    
        update_option('wp_link_robot_pageslug', $sp_pageslug);  
        update_option('wp_link_robot_keywords', $sp_keywords);  

        $noticemsg = "Settings were updated!";
    } 
} 
// --------------------------- GET SETTINGS -------------------------------


$s_seofriendly = get_option('wp_link_robot_seofriendly');
$s_description = get_option('wp_link_robot_description');
$s_url = get_option('wp_link_robot_url');
$s_linksperpage = get_option('wp_link_robot_linksperpage');
$s_approval = get_option('wp_link_robot_approval');
$s_nonreciprocal = get_option('wp_link_robot_nonreciprocal');
$s_validate = get_option('wp_link_robot_validate');
$s_is_active = get_option('wp_link_robot_is_active');
$s_deactivate = get_option('wp_link_robot_deactivate');
$s_maxdescsize = get_option('wp_link_robot_maxdescsize');
$s_email = get_option('wp_link_robot_email');
$s_emailto = get_option('wp_link_robot_emailto');
$s_spoof = get_option('wp_link_robot_spoof');
$s_reciprocalurl = get_option('wp_link_robot_reciprocalurl');
$s_email2 = get_option('wp_link_robot_email2');
$s_email3 = get_option('wp_link_robot_email3');
$s_emailt1 = get_option('wp_link_robot_emailt1');
$s_emailt2 = get_option('wp_link_robot_emailt2');
$s_showcategorydescription = get_option('wp_link_robot_showcategorydescription');
$s_keywords = get_option('wp_link_robot_keywords');
$s_pagedescription = get_option('wp_link_robot_pagedescription');
$s_captcha = get_option('wp_link_robot_captcha');
$s_name = get_option('wp_link_robot_name');
$s_pageslug = get_option('wp_link_robot_pageslug');
$s_emailfrom = get_option('wp_link_robot_emailfrom');


?>
<?php
if ($noticemsg!="")
{
?>
<div id="message1" class="updated fade"><p><?php _e($noticemsg); ?></p></div>
<?php 
}
?>
<div class="wrap" style="clear:left"><br />
  <form id="form1" name="form1" method="post" action="">
  <h2><?php _e('Main directory options'); ?></h2>
  <label>My reciprocal url:</label>
  <input name="reciprocalurl" type="text" id="reciprocalurl" value="<?php echo($s_reciprocalurl); ?>" size="45" /> e.g. &quot;http://www.google.com&quot;; separate multiple urls with spaces
  <br />
  <label>Directory's name:</label>
  <input name="mname" type="text" id="mname" value="<?php echo($s_name); ?>" size="45" /> e.g. &quot;SEO Directory&quot;
  <br />
  <label>Directory slug name:</label>
  <input name="pageslug" type="text" id="mname" value="<?php echo($s_pageslug); ?>" size="45" /> e.g. &quot;links&quot;, if using permalinks, <strong>update your permalink structure after changing this</strong>!
  <br /><br />
  <!--h2><?php _e('SEO options'); ?></h2>
  <label>
  <input name="seo" type="checkbox" id="seo" value="yes" <?php if ($s_seofriendly == "1") {echo("checked");} ?>/>
  Use SEO friendly mode (requires mod_rewrite)</label>
  <br /><br />
  Default directory keywords (if left blank, or if viewing the main page of the directory):<br />
  <input name="keywords" type="text" id="keywords" value="<?php echo($s_keywords); ?>" size="60" />
(comma separated) <br />
  <br />
  Default directory description (if left blank, or if viewing the main page of the directory): <br />
  <textarea name="pagedescription" cols="70" rows="6" id="pagedescription"><?php echo($s_pagedescription); ?></textarea>
  <br /><br /-->
  <h2><?php _e('Public link and category display options'); ?></h2>
  <label>
  <input name="linkdescription" type="checkbox" id="linkdescription" value="yes" <?php if ($s_description == "1") {echo("checked");} ?>/>
  Display link description</label>
  <br />
  <label>
  <input name="showcategorydescription" type="checkbox" id="showcategorydescription" value="yes" <?php if ($s_showcategorydescription == "1") {echo("checked");} ?>/>
  Display category description</label>
  <br />
  <label>
  <input name="linkurl" type="checkbox" id="linkurl" value="yes" <?php if ($s_url == "1") {echo("checked");} ?>/>
  Display link URL</label>
  <br /><br />
  Links per page: 
  <input name="lpp" type="text" class="code" id="lpp" size="5" value="<?php echo($s_linksperpage); ?>"/>
  <br />
  <br />
  <h2><?php _e('Public link submission'); ?></h2>
  <label>
  <input name="nonreciprocal" type="checkbox" id="nonreciprocal" value="yes" <?php if ($s_nonreciprocal == "1") {echo("checked");} ?>/>
  Allow submission of non-reciprocal links</label>
  <br />
  <label></label>
  <label>
  <input name="linksapproval" type="checkbox" id="linksapproval" value="yes" <?php if ($s_approval == "1") {echo("checked");} ?>/>
  Links have to be approved</label><br />
  <!--label><input name="captcha" type="checkbox" id="captcha" value="yes" <?php if ($s_captcha == "1") {echo("checked");} ?>/> Use captcha protection for link submissions</label><br /><br /-->
  Maximum description size for a link (in characters):
  <input name="maxdescsize" type="text" class="code" id="maxdescsize" size="5" value="<?php echo($s_maxdescsize); ?>"/>
<br />
  <h2><?php _e('Backlink cleansing settings'); ?></h2>
    <label><input name="spoof" type="checkbox" id="spoof" value="yes" <?php if ($s_spoof == "1") {echo("checked");} ?>/>
  When checking for backlinks, pretend to be a visitor using a web browser </label>
    (recommended)<br /><br />
	  <h2><?php _e('E-mail options'); ?></h2>
  <label><input name="email" type="checkbox" id="email" value="yes" <?php if ($s_email == "1") {echo("checked");} ?>/>
  Send me a notification e-mail when a new link arrives in my inbox</label>
  <br />
  <label><input name="email2" type="checkbox" id="email2" value="yes" <?php if ($s_email2 == "1") {echo("checked");} ?>/>
  Send a notification e-mail to my new link partners upon link approval</label>
  <br />
  <label><input name="email3" type="checkbox" id="email3" value="yes" <?php if ($s_email3 == "1") {echo("checked");} ?>/>
  Send a notification e-mail to any potential link partner upon link rejection</label>
  <br />
  <br />
  <label>Link administrators e-mail:</label>
  <input name="emailfrom" type="text" id="emailfrom" value="<?php echo($s_emailfrom); ?>" size="45" /><br /><br />
  <label>My e-mail:</label>
  <input name="emailto" type="text" id="emailto" value="<?php echo($s_emailto); ?>" size="45" /><br /><br />
  <label>Link approval e-mail template:<br />
  <textarea name="emailt1" cols="70" rows="6" id="emailt1"><?php echo(stripslashes($s_emailt1)); ?></textarea>
  </label>
  <br />
  <em>Use {LINK} as a tag for displaying your link partner's link. </em><br /><br />
  <label>Link rejection e-mail template:<br />
  <textarea name="emailt2" cols="70" rows="6" id="emailt2"><?php echo(stripslashes($s_emailt2)); ?></textarea>
  </label>
  <br />
  <em>Use {LINK} as a tag for displaying your link partner's link.  </em><br />
  <br />
  <h2><?php _e('Link Robot uninstallation options'); ?></h2>
  <label>
  <input name="deactivate" type="checkbox" id="deactivate" value="yes" <?php if ($s_deactivate == "1") {echo("checked");} ?>/>
  When deactivating this plugin, remove all data related to it from the database (including links and categories)</label>
    <p class="submit">
	<input type="submit" value="<?php _e('Save settings') ?>" name="ok" style="float:left;"/>
	</p>
  </form>
</div>
<?php
//require('./admin-footer.php');
?>