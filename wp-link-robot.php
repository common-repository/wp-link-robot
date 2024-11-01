<?php
/*
Plugin Name: FV WP Link Robot
Plugin URI: http://foliovision.com/seo-tools/wordpress/plugins/wp-link-robot
Description: This plugin enables you to embed a link exchange directory right in the comfort of your own blog. Use this plugin to gain new partner sites and to improve your overall backlink count. New version with recycling bin, redirections and SEO info.
Author: Foliovision
Version: 0.6.11
Author URI: http://www.foliovision.com/
*/
/* install functions*/
add_filter('rewrite_rules_array', 'wp_link_robot_permalinks'); 
add_filter('query_vars','wp_link_robot_wp_insertMyRewriteQueryVars');
require_once(dirname( __FILE__ ) . '/source/functions.php');
require_once(dirname( __FILE__ ) . '/source/browse-functions.php');
add_action('admin_menu', 'wp_link_robot_options_page');
add_action('admin_init', 'wp_link_robot_init');
add_action('activate_wp-link-robot/wp-link-robot.php', 'eblex_install');
add_action('deactivate_wp-link-robot/wp-link-robot.php', 'eblex_uninstall');
add_filter('the_content', 'wp_link_robot_generate_linkpage');
add_action( 'wp_head', 'wp_link_robot_wp_head', 0 );
add_action( 'page_link', 'wp_link_robot_page_link', 10, 3 );
add_shortcode( 'wp_link_robot_genlinks', 'wp_link_robot_create_linkpage' );
add_shortcode( 'wp_link_robot_addlinks', 'wp_link_robot_add_new_link' );
function wp_link_robot_init()
{
  wp_register_script('wp-link-robot_Script', WP_PLUGIN_URL . '/wp-link-robot/source/functions.js');
  add_action( 'wp_ajax_lnkrob_ajax_action_edit', 'lnkrob_ajax_action_edit' );
  add_action( 'wp_ajax_lnkrob_ajax_action_approve', 'lnkrob_ajax_action_approve' );
  add_action( 'wp_ajax_lnkrob_ajax_action_reject', 'lnkrob_ajax_action_reject' );
  add_action( 'wp_ajax_lnkrob_ajax_action_editinbox', 'lnkrob_ajax_action_editinbox' );
  add_action( 'wp_ajax_lnkrob_ajax_action_edit_clean', 'lnkrob_ajax_action_edit_clean' );
  add_action( 'wp_ajax_lnkrob_ajax_action_save', 'lnkrob_ajax_action_save' );
  add_action( 'wp_ajax_lnkrob_ajax_action_save_inbox', 'lnkrob_ajax_action_save_inbox' );
  add_action( 'wp_ajax_lnkrob_ajax_action_clean_save', 'lnkrob_ajax_action_clean_save' );
  add_action( 'wp_ajax_lnkrob_ajax_action_remove', 'lnkrob_ajax_action_remove' );
  add_action( 'wp_ajax_lnkrob_ajax_action_check', 'lnkrob_ajax_action_check' );
  add_action( 'wp_ajax_lnkrob_ajax_action_backcheck', 'lnkrob_ajax_action_backcheck' );
  add_action( 'wp_ajax_lnkrob_ajax_action_addnew', 'lnkrob_ajax_action_addnew' );
  add_action( 'wp_ajax_lnkrob_ajax_action_new', 'lnkrob_ajax_action_new' );
  add_action( 'wp_ajax_lnkrob_ajax_action_new_input', 'lnkrob_ajax_action_new_input' );
  add_action( 'wp_ajax_lnkrob_ajax_action_show_flags', 'lnkrob_ajax_action_show_flags' );
  add_action( 'wp_ajax_lnkrob_ajax_action_add_flags', 'lnkrob_ajax_action_add_flags' );
  add_action( 'wp_ajax_lnkrob_ajax_action_update_url', 'lnkrob_ajax_action_update_url' );
//  global $wp_rewrite;
// 	$wp_rewrite->flush_rules();
}
function wp_link_robot_scripts()
{
  wp_enqueue_script('wp-link-robot_Script');
} 
function wp_link_robot_options_page()
{
	if (function_exists('add_options_page'))
	{	   
		$page = add_management_page('Link Robot Options', 'Link Robot', 'edit_pages', 'wp_robot_link_manage', 'wp_robot_link_manage');
		add_action('admin_print_scripts-' . $page, 'wp_link_robot_scripts');
	}
}
function wp_link_robot_wp_insertMyRewriteQueryVars($vars){
    array_push($vars, 'link_robot_category');
    array_push($vars, 'link_robot_subcategory');
    array_push($vars, 'link_robot_page');
    return $vars;
}
/* 
 * Create rewrite rules for link-robot pages
 */
function wp_link_robot_permalinks($rules) { 
	global $wp_rewrite;
//	$ddsg_sm_name = trim(get_option('ddsg_sm_name')); 
	$pageslugname = trim(get_option('wp_link_robot_pageslug'));
//	$addlinkslugname = trim(get_option('wp_link_robot_addlinkslug'));
	if ($pageslugname != ''){	
//		$newrules[$pageslugname . '/([0-9]{1,})/?$'] = 'index.php?&pagename=' . $pageslugname . '&pg=' . $match_form;
		$newrules[$pageslugname . '/([a-z\-]*)/?$'] = 'index.php?&pagename=' . $pageslugname . '&link_robot_category=$matches[1]&link_robot_page=1';
		$newrules[$pageslugname . '/(\d*)/?$'] = 'index.php?&pagename=' . $pageslugname . '&link_robot_page=$matches[1]';
		$newrules[$pageslugname . '/([a-z\-]*)/(\d*)/?$'] = 'index.php?&pagename=' . $pageslugname . '&link_robot_category=$matches[1]&link_robot_page=$matches[2]';
		$newrules[$pageslugname . '/([a-z\-]*)/([a-z\-]*)/?$'] = 'index.php?&pagename=' . $pageslugname . '&link_robot_category=$matches[1]&link_robot_subcategory=$matches[2]';
		$newrules[$pageslugname . '/([a-z\-]*)/([a-z\-]*)/(\d*)/?$'] = 'index.php?&pagename=' . $pageslugname . '&link_robot_category=$matches[1]&link_robot_subcategory=$matches[2]&link_robot_page=$matches[3]';
		$newrules = array_merge($newrules,$rules);
		return $newrules;
	} else {
		return $rules;
	}
} 
/* 
 * Display sitemap if trigger is found
 */
function wp_link_robot_generate_linkpage($content) {
	/*if (strpos($content, "[wp_link_robot_genlinks]") !== FALSE) {
		$content = preg_replace('/<p>\s*[(.*)]\s*<\/p>/i', "[$1]", $content);
		$link_output = '';
		$content = str_replace('[wp_link_robot_genlinks]', wp_link_robot_create_linkpage(), $content);
	}
	if (strpos($content, "[wp_link_robot_addlinks]") !== FALSE) {
		$content = preg_replace('/<p>\s*[(.*)]\s*<\/p>/i', "[$1]", $content);
		$link_output = '';
		$content = str_replace('[wp_link_robot_addlinks]', wp_link_robot_add_new_link(), $content);
	}*/
	if (strpos($content, "<!-- wp_link_robot_genlinks -->") !== FALSE) {
		$content = preg_replace('/<p>\s*<!--(.*)-->\s*<\/p>/i', "<!--$1-->", $content);
		$link_output = '';
		$content = str_replace('<!-- wp_link_robot_genlinks -->', wp_link_robot_create_linkpage(), $content);
	}
	if (strpos($content, "<!-- wp_link_robot_addlinks -->") !== FALSE) {
		$content = preg_replace('/<p>\s*<!--(.*)-->\s*<\/p>/i', "<!--$1-->", $content);
		$link_output = '';
		$content = str_replace('<!-- wp_link_robot_addlinks -->', wp_link_robot_add_new_link(), $content);
	}
	return $content;
}
/* 
 * Create the link directory
 */
function wp_link_robot_create_linkpage() {

	global $wpdb;
	global $wp_query;
	global $table_prefix;
	$output = '';
	$pageslugname = trim(get_option('wp_link_robot_pageslug'));
	
	remove_action( 'page_link', 'wp_link_robot_page_link', 10, 3 );
	$directory_page_permalink = get_permalink();
	add_action( 'page_link', 'wp_link_robot_page_link', 10, 3 );	
	
	$aUrl = explode('/', $_SERVER['REQUEST_URI']);
  if(in_array($pageslugname, $aUrl)){
    $show_category = ($wp_query->query_vars['link_robot_category'])?$wp_query->query_vars['link_robot_category']:$_GET['link_robot_category'];
    $show_subcategory = ($wp_query->query_vars['link_robot_subcategory'])?$wp_query->query_vars['link_robot_subcategory']:$_GET['link_robot_subcategory'];
    $show_link_catdesc = get_option('wp_link_robot_showcategorydescription');
    if( !isset( $show_category ) || ( $show_category=='' ) ){
      $all_cats = $wpdb->get_results("SELECT * FROM `".$table_prefix."eblex_categories` WHERE `visible`=1 ORDER BY `zindex` DESC,`title` ASC");
      $counts =  $wpdb->get_results("
                        SELECT 
                          `".$table_prefix."eblex_categories`.`id`,
                          count(`".$table_prefix."eblex_links`.`title`) as `count` 
                        FROM `".$table_prefix."eblex_categories` 
                        LEFT JOIN `".$table_prefix."eblex_links` ON `".$table_prefix."eblex_links`.`category`=`".$table_prefix."eblex_categories`.`id` 
                        WHERE visible='1' AND `active`='1' AND `status`='1' GROUP BY `".$table_prefix."eblex_links`.`category`");
    }
    if(isset( $show_category ) && !isset( $show_subcategory )){
      $all_cats = $wpdb->get_results("SELECT * FROM `".$table_prefix."eblex_categories` WHERE `visible`=1 AND `parent` IN (SELECT `id` FROM `".$table_prefix."eblex_categories` WHERE `nicename`='".$show_category."')ORDER BY `zindex` DESC,`title` ASC");
      $counts =  $wpdb->get_results("
                        SELECT 
                          `".$table_prefix."eblex_categories`.`id`,
                          count(`".$table_prefix."eblex_links`.`title`) as `count` 
                        FROM `".$table_prefix."eblex_categories` 
                        LEFT JOIN `".$table_prefix."eblex_links` ON `".$table_prefix."eblex_links`.`category`=`".$table_prefix."eblex_categories`.`id` 
                        WHERE visible='1' AND `active`='1' AND `status`='1' GROUP BY `".$table_prefix."eblex_links`.`category`");
    }
    if(isset($all_cats) && !empty($all_cats)){
      $catcounts = array();
      foreach($counts as $count)
        $catcounts[$count->id] = $count->count;
      foreach($all_cats as $category){
      if(strlen($category->id) > 1) // we're not showing the root directory
        if((!isset($category->parent)||($category->parent=='')||($category->parent=='0'))||(isset( $show_category ) && !isset( $show_subcategory ))){
          $suboutput = ''; $gotsub = false;
          foreach($all_cats as $subcategory){
            if($subcategory->parent==$category->id){
              $gotsub = true;
              if(isset($catcounts[$subcategory->id])){
                $suboutput .= '<div class="catbox">';
                $suboutput .= '&nbsp;&nbsp;&nbsp;&nbsp;<img src="'.get_option('siteurl') . '/wp-content/plugins/wp-link-robot/images/folder.gif" alt="&gt;" width="16" height="15" border="0"/>&nbsp;';
                if (isset($pageslugname)&&($pageslugname!=''))
                  $suboutput .= '<a href="'.wp_link_robot_construct_link( $directory_page_permalink, $category->nicename, $subcategory->nicename ).'">'.$subcategory->title . '</a>';
                else
                  if (strpos($_SERVER['REQUEST_URI'],'?')===false)
                    $suboutput .= '<a href="'.$_SERVER['REQUEST_URI'].'?link_robot_category='.$category->nicename . '&link_robot_subcategory='.$subcategory->nicename . '">'.$subcategory->title . '</a>';
                  else
                    $suboutput .= '<a href="'.$_SERVER['REQUEST_URI'].'&link_robot_category='.$category->nicename . '&link_robot_subcategory='.$subcategory->nicename . '">'.$subcategory->title . '</a>';
                if (isset($catcounts[$subcategory->id]))  $suboutput .= ' ('.$catcounts[$subcategory->id].')';
                $suboutput .= '<br />';
                $suboutput .= '</div>';
              }
            }
          }
          if(((strlen($suboutput)>0)&&($gotsub===true))||( isset($catcounts[$category->id]) )){ 
            $output .= '<div class="catbox">';
            $output .= '<img src="'.get_option('siteurl') . '/wp-content/plugins/wp-link-robot/images/folder.gif" alt="&gt;" width="16" height="15" border="0"/>&nbsp;';
            if (isset($pageslugname)&&($pageslugname!='')){
              if (isset($catcounts[$category->id])) {
              	$output .= '<a class="catlink" href="'.wp_link_robot_construct_link( $directory_page_permalink, $show_category, $category->nicename ) . '">'.$category->title . '</a>';
              }
              else $output .= $category->title;
            }
            else
              if (strpos($_SERVER['REQUEST_URI'],'?')===false)
              $output .= '<a class="catlink" href="'.$_SERVER['REQUEST_URI'].'?link_robot_category='.$category->nicename . '">'.$category->title . '</a>';
              else
              $output .= '<a class="catlink" href="'.$_SERVER['REQUEST_URI'].'&link_robot_category='.$category->nicename . '">'.$category->title . '</a>';
            if (isset($catcounts[$category->id]))  $output .= ' ('.$catcounts[$category->id].')';
            //else  $output .= '(0)';
            if(($show_link_catdesc=="1")&&(!empty($category->description))) $output .= ' - '.stripslashes($category->description);
            $output .= '<br />';
            $output .= '</div>';
            $output .=$suboutput;
          }
        }
      }
      // place the links from root here:
    }
    //else
    if(!isset($show_category)||($show_category=='')) $show_category = 'root';
    {
      $show_page = 1;
      $links_per_page = get_option('wp_link_robot_linksperpage');
      if(isset($wp_query->query_vars['link_robot_page'])){
        $show_page = $wp_query->query_vars['link_robot_page'];
      } 
      $show_link_desc = get_option('wp_link_robot_description');
      $show_link_url = get_option('wp_link_robot_url');
      $show_which = ($show_subcategory)?$show_subcategory:$show_category;
      $links_from = ($show_page-1)*$links_per_page;
      $links = $wpdb->get_results("SELECT 
                                      `".$table_prefix."eblex_categories`.`title` as `category_title`,
                                      `".$table_prefix."eblex_categories`.`nicename` as `nicename`,
                                      `".$table_prefix."eblex_links`.`title` as `link_title`,
                                      `".$table_prefix."eblex_links`.`description` as `link_description`,
                                      `".$table_prefix."eblex_links`.`url` as `link_url`
                                  FROM `".$table_prefix."eblex_categories` 
                                  LEFT JOIN `".$table_prefix."eblex_links` ON `".$table_prefix."eblex_links`.`category`=`".$table_prefix."eblex_categories`.`id` 
                                  WHERE `".$table_prefix."eblex_categories`.`nicename`='".$show_which."' AND `".$table_prefix."eblex_links`.`active`='1' AND `".$table_prefix."eblex_links`.`status`='1'
                                  ORDER BY `".$table_prefix."eblex_links`.`zindex` DESC,`".$table_prefix."eblex_links`.`title` ASC
                                  LIMIT ".$links_from.",".$links_per_page);
      if($show_category != 'root'){
        if(isset($show_subcategory)){
          if (isset($pageslugname)&&($pageslugname!='')){
						$output .= '<br /><div class="catbox"><a class="catlink" href="'.wp_link_robot_construct_link( $directory_page_permalink, $show_category, $subcategory->nicename ) . '">&laquo; Back</a></div>';
              
            }
            else
              if (strpos($_SERVER['REQUEST_URI'],'?')===false)
                $output .= '<br /><div class="catbox"><a class="catlink" href="'.$_SERVER['REQUEST_URI'].'?link_robot_category='.$show_category . '">&laquo; Back</a></div>';
              else{
                $newurl = str_replace('&link_robot_subcategory='.$show_subcategory, '', $_SERVER['REQUEST_URI']);
                $newurl = str_replace('link_robot_subcategory='.$show_subcategory, '', $newurl);
                $output .= '<br /><div class="catbox"><a class="catlink" href="'.$newurl. '">&laquo; Back</a></div>';
              }
          }
          else{
          if (isset($pageslugname)&&($pageslugname!='')){
              $output .= '<br /><div class="catbox"><a class="catlink" href="'.wp_link_robot_construct_link( $directory_page_permalink ).'">&laquo; Back</a></div>';
            }
            else
              if (strpos($_SERVER['REQUEST_URI'],'?')===false)
              $output .= '<br /><div class="catbox"><a class="catlink" href="'.$_SERVER['REQUEST_URI'].'">&laquo; Back</a></div>';
              else{
                $newurl = str_replace('&link_robot_category='.$show_category, '', $_SERVER['REQUEST_URI']);
                $newurl = str_replace('link_robot_category='.$show_category, '', $newurl);
                $output .= '<br /><div class="catbox"><a class="catlink" href="'.$newurl.'">&laquo; Back</a></div>';
              }
          }
      }
      $output .= '<br />';
 /*     $dir_name = get_option('wp_link_robot_name');
      if($show_category != 'root') $output .= '<h2>Links inside: '.$links[0]->category_title . '</h2>';
      else $output .= '<h2>Links inside: '.$dir_name . '</h2>';
   */   
      $linkbox = 1;
      foreach($links as $link){
         if(strpos($link->link_url,'http://')===false) $linkurl = 'http://'.$link->link_url;
        else $linkurl = $link->link_url;
        $output .= '<div class="linkbox'.$linkbox.'">';
        $output .= '<a class="plink" target="_blank" href="'.$linkurl.'">'.$link->link_title.'</a>';
        $output .= '<br />';
        if ($show_link_url=="1") $output .= '<span class="purl">'.$link->link_url.'</span><br />';
        if ($show_link_desc=="1") $output .= $link->link_description;
        $output .= '</div>';
        $output .= '<br />';
        $linkbox = ($linkbox)%2+1;
      }
      $show_pagination = true;
      $iLinkCount = intval($wpdb->get_var("SELECT count(*) FROM `".$table_prefix."eblex_categories` 
                                  LEFT JOIN `".$table_prefix."eblex_links` ON `".$table_prefix."eblex_links`.`category`=`".$table_prefix."eblex_categories`.`id` 
                                  WHERE `".$table_prefix."eblex_categories`.`nicename`='".$show_which."' AND `".$table_prefix."eblex_links`.`active`='1' AND `".$table_prefix."eblex_links`.`status`='1'"));
      // show pagination at the bottom
      if($iLinkCount <= $links_per_page) $show_pagination = false;
      
			if($show_pagination){			
				//$strPageUrl =  '/'.$pageslugname;
				remove_action( 'page_link', 'wp_link_robot_page_link', 10, 3 );
				$link = get_permalink();
				add_action( 'page_link', 'wp_link_robot_page_link', 10, 3 );
				
				$next_page = $show_page+1;
				$prev_page = $show_page-1;
				if( $trailing_slash ) {
					$next_page .= '/';
					$prev_page .= '/';					
				}
				$output .=  '<p>';
				// previous page arrow
				if($prev_page > 0) {
					$output .=  '<a href="'.wp_link_robot_construct_link( $link, $show_category, $show_subcategory, $prev_page ).'">&laquo;</a>';
				}
				$output .=  "&nbsp;&nbsp;";
				// showing the page numbers 
				for($k=0; $k < $iLinkCount/$links_per_page; $k++) {
					$page = $k+1;

					$pagenumber = '<a href="'.wp_link_robot_construct_link( $link, $show_category, $show_subcategory, $page ).'"';
					if ($page==$show_page) {
						$pagenumber .= ' style="font-weight:bold"';  // use bold on the actual page
					}
					$pagenumber .= '>'.$page.'</a>';
					$output .=  $pagenumber . "&nbsp;&nbsp;";
				} 
				
				// next page arrow
				if (($next_page-1)*$links_per_page < $iLinkCount) {
					$output .=  '<a href="'.wp_link_robot_construct_link( $link, $show_category, $show_subcategory, $next_page ) .'">&raquo;</a>';
				}		
				$output .=  '</p>';
			}
    }
  }
	return $output;
}
/* 
 * Create the sitemap
 */
function wp_link_robot_add_new_link() {
	global $wpdb;
	$output = '';
   $output='<script language="javascript" type="text/javascript">
               function limitText(limitField, limitCount, limitNum) {
               	if (limitField.value.length > limitNum) {
               		limitField.value = limitField.value.substring(0, limitNum);
               	} else {
               		limitCount.value = limitNum - limitField.value.length;
               	}
               }
               </script>';
  $error = false; 
  if($_POST['submit']){
    $approval_option = trim(get_option('	wp_link_robot_approval'));
    if(empty($_POST['link-title-new'])){$error = true; $output .= '<p>Please fill out Title</p>';}else{ $link_title = $_POST['link-title-new'];}
    if(empty($_POST['link-url-new'])){$error = true;  $output .= '<p>Please fill out URL</p>';}else{ $link_url = $_POST['link-url-new'];}
    if(empty($_POST['link_description_new'])){$error = true;  $output .= '<p>Please fill out Description/p>';}else{$link_desc = $_POST['link_description_new'];}
    if(empty($_POST['link-email-new'])){$error = true;  $output .= '<p>Please fill out Email</p>';}else{$link_email = $_POST['link-email-new'];}
    if(empty($_POST['link-reciprocalurl-new'])){$error = true;  $output .= '<p>Please fill out Reciprocal URL</p>';}else{$link_rec = $_POST['link-reciprocalurl-new'];}
    if(!$error){
        $l_id = md5(uniqid(rand(), true) . $_POST['title']);
        $l_time = time();
      //insert into database here
        if ($approval_option =="1") $approved = '0';
        else $approved = '1';
        $wpdb->query("INSERT INTO {$wpdb->prefix}eblex_links (`title`, `active`, `nonreciprocal`, `url`, `category`, `description`, `email`, `reciprocalurl`, `status`, `time`, `administratorcomment`, `zindex`, `id`, found) 
                    VALUES ('{$link_title}', '{$approved}', '0', '{$link_url}', '{$_POST['link-category-new']}', '{$link_desc}', '{$link_email}', '{$link_rec}', '1', '$l_time', '', '0', '$l_id', '');");
        if ($approval_option =="1") $output .= '<p>Thank you. Your link is now waiting for approval. You will be notified by email once it has been approved.</p>';
        else $output .= '<p>Thank you. Your link has been added into our directory.</p>';
        $s_email = get_option('wp_link_robot_email'); // send me the notification
        $s_emailt = 'New link is waiting for your approval.';
        $s_emailfrom = get_option('wp_link_robot_emailfrom');
        $s_emailto = get_option('wp_link_robot_emailto');
        if ($s_email == "1") {
           $headers = 'From: Link Administrator <'.$s_emailfrom.'>' . "\r\n\\";       
           wp_mail($s_emailto, "Link approval notification", $s_emailt, $headers);
        } 
    } 
  }else{
  	$pageslugname = trim(get_option('wp_link_robot_pageslug'));
    $sOptions = '';
    $aaCategories = $wpdb->get_results( "SELECT id,title FROM `{$wpdb->prefix}eblex_categories` WHERE parent = \"\"  AND visible='1' ORDER BY `zindex` DESC,`title` ASC" );
    foreach($aaCategories as $aCategory){
      if(strlen($aCategory->id) > 1){
        $sOptions .= "<option value=\"{$aCategory->id}\">{$aCategory->title}</option>\n";
        //if ($aCategory->id != 0){
          $aaSubCategories = $wpdb->get_results( "SELECT id,title FROM `{$wpdb->prefix}eblex_categories` WHERE parent = \"{$aCategory->id}\" AND visible='1' ORDER BY `zindex` DESC,`title` ASC" );
          if($aaSubCategories)
          foreach($aaSubCategories as $aSubcategory){
          $sOptions .= "<option value=\"{$aSubcategory->id}\">&nbsp;&nbsp;&nbsp;{$aSubcategory->title}</option>\n";
          }
        //}    
      }
    }
    $charlimit = get_option('wp_link_robot_maxdescsize');
    $output .= '<p>Fill the following form to add your link into our directory.</p>';
    $output .= '<form action="" method="post" name="form">';
    $output .= '<table id="wp-link-robot-new-link">';
    $output .= '<tr><td>Title:</td><td><input name="link-title-new" type="text" value="" size="52"/></td></tr>
  				<tr><td>Url:</td><td><input name="link-url-new" type="text" value=""  size="52"/></td></tr>
  				<tr><td>Category:</td><td><select name="link-category-new">'.$sOptions.'</select></td></tr>
  				<tr><td valign="top">Description:</td><td>
              <textarea name="link_description_new"  cols="50" rows="4" onKeyDown="limitText(this.form.link_description_new,this.form.countdown,'.$charlimit.');"onKeyUp="limitText(this.form.link_description_new,this.form.countdown,'.$charlimit.');"></textarea><br />
<font size="1">(Maximum characters: '.$charlimit.'<!--, You have <input readonly type="text" name="countdown" size="3" value="'.$charlimit.'"> characters left.-->)</font>
              </td></tr>
  				<tr><td>E-mail:</td><td><input name="link-email-new" type="text" value="" size="52" /></td></tr>
  				<tr><td>Reciprocal&nbsp;Url:</td><td><input name="link-reciprocalurl-new" type="text" value="" size="52" /></td></tr>
  				</table>';
  	 $output .= '<input type="submit" value="Submit link &raquo;" name="submit" />';
    $output .= '</form>';
  }
	return $output;
}
function wp_robot_link_manage()
{
    global $table_prefix,$wpdb;
      /*  Display */
    $inbox = $wpdb->get_var("SELECT count(*) FROM `" . $table_prefix . "eblex_links` WHERE `active`='0' AND `status`='1'");
    if ($_GET['action'] == "delete") {
        $confirmation = $wpdb->get_var("SELECT `id` FROM `" . $table_prefix . "eblex_links` WHERE `id`='" . $_GET['id'] . "'");
        if ($confirmation != "") {
            $inbox--;
        } 
    } 
    if ($_GET['action'] == "approve") {
        $confirmation = $wpdb->get_var("SELECT `id` FROM `" . $table_prefix . "eblex_links` WHERE `id`='" . $_GET['id'] . "' AND `active`='0'");
        if ($confirmation != "") {
            $inbox--;
        } 
    }
    $submenu = array('inbox' => "Inbox (" . $inbox . ")", 
                     'qa' => "Quick Add",
                     'categories' => "Categories",
                     'browse' => "Browse links",
                     'search' => "Search",
                     'clean' => "Backlink cleansing",
                     'stats' => "Statistics",
                     'settings' => "Settings",
                     'help' => "Help"); 
    $request = getPartBeforeRequest("sub");
    if($request){
       //if (stripos($request,"&sub=") !== false) $request = substr($request, 0, stripos($request,"&sub="));
       //$request = preg_replace( '/&sub=\w+(&)?/', '$1', $request );
        ?>
          <div class="wrap">
            <div id="icon-tools" class="icon32"><br /></div>
              <h2>WP Link Robot</h2>
          <ul class="subsubsub">
          <?php
          $aSubAttribute = 'browse';
          if(isset($_GET['sub'])) $aSubAttribute = $_GET['sub'];
          if ($aSubAttribute == "subcategories") $aSubAttribute = "categories";
          foreach ($submenu as $key => $value){
          ?> 
              <li> <a <?php
                       if($key == $aSubAttribute) echo 'class = "current"'; ?> href = <?php echo '"'.$request . "&sub=" . $key ."\">" . $value; ?></a> |</li>
          <?php }?>
          </ul>
        	</div>
    <?php  }
    if (isset($_GET['sub'])){
      switch($_GET['sub']){
        case "inbox": include (dirname(__FILE__) . "/source/wp-link-robot-inbox.php");
          break;
        case "qa" : include (dirname(__FILE__) . "/source/wp-link-robot-qa.php");
          break;
        case "categories": include (dirname(__FILE__) . "/source/wp-link-robot-categories.php");
          break;
        case "subcategories": include (dirname(__FILE__) . "/source/wp-link-robot-subcategories.php");
          break;
        case "browse": include (dirname(__FILE__) . "/source/wp-link-robot-browse.php");
          break;
        case "search": include (dirname(__FILE__) . "/source/wp-link-robot-search.php");
          break;
        case "clean": include (dirname(__FILE__) . "/source/wp-link-robot-clean.php");
          break;
        case "stats": include (dirname(__FILE__) . "/source/wp-link-robot-stats.php");
          break;  
        case "settings": include (dirname(__FILE__) . "/source/wp-link-robot-settings.php");
          break;
        case "help": include (dirname(__FILE__) . "/source/wp-link-robot-help.php");
          break;
      }
    }
    else   include (dirname(__FILE__) . "/source/wp-link-robot-browse.php");
}
function eblex_install()
{
    global $table_prefix, $wpdb;
    $eblex_settings = $table_prefix . "eblex_settings";
    $eblex_categories = $table_prefix . "eblex_categories";
    $eblex_links = $table_prefix . "eblex_link";
    if (get_option('wp_link_robot_emailt1')){
      //do nothing, the setting are already there
    }
    else{
      //upgrading from older version  - moving settings from table to wp options
      //set the defaults
      add_option('wp_link_robot_seofriendly', '1');    
      add_option('wp_link_robot_description', '1');    
      add_option('wp_link_robot_url', '1');    
      add_option('wp_link_robot_linksperpage', '10');    
      add_option('wp_link_robot_approval', '1');    
      add_option('wp_link_robot_nonreciprocal', '0');    
      add_option('wp_link_robot_validate', '1');    
      add_option('wp_link_robot_deactivate', '0');    
      add_option('wp_link_robot_is_active', '1');    
      add_option('wp_link_robot_maxdescsize', '350');    
      add_option('wp_link_robot_reciprocalurl', 'http://www.example.com');    
      add_option('wp_link_robot_spoof', '1');    
      add_option('wp_link_robot_email', '1');    
      add_option('wp_link_robot_emailto', 'emailto@example.com');    
      add_option('wp_link_robot_email2', '1');    
      add_option('wp_link_robot_email3', '1');    
      add_option('wp_link_robot_emailt1', 'Your link ({LINK}) has been approved to be displayed in our link directory! Thank you for your submission!');    
      add_option('wp_link_robot_emailt2', 'We regret to inform you that your link ({LINK}) has not been approved to be displayed in our link directory due to incompatibility with our policies. You may have submitted an invalid reciprocal URL, placed your link in a wrong category, or maybe you skipped on writing an adequate description for it. If this is the case, you may try and submit your link again. Thank you for your submission.');    
      add_option('wp_link_robot_showcategorydescription', '0');    
      add_option('wp_link_robot_pagedescription', 'Link Directory');    
      add_option('wp_link_robot_captcha', '1');    
      add_option('wp_link_robot_name', 'Link Directory');    
      add_option('wp_link_robot_emailfrom', 'emailfrom@example.com');    
      add_option('wp_link_robot_pageslug', '');  
      add_option('wp_link_robot_keywords', '');  
      //replace with those from databse
      if ($wpdb->get_var("show tables like '$eblex_settings'") != $eblex_settings) {
          $query = 'SELECT * FROM '.$eblex_settings;
          $results = $wpdb->get_results($query);
          if($results)
          foreach($results as $setting){
            add_option('wp_link_robot_'.$setting->option,$setting->value);
          }
       }
       //now delete the old table - don't want to keep it there any longer, or are we?  hmm ok probably yes, it won't be possible to go back if we delete this table
       // eblex_executequery('DROP TABLE `' . $eblex_settings . '`');
    }
    if ($wpdb->get_var("show tables like '$eblex_categories'") != $eblex_categories && $wpdb->get_var("show tables like '$eblex_links'") != $eblex_links) {
        $sql = 'CREATE TABLE `' . $table_prefix . 'eblex_captcha` ('
         . ' `id` VARCHAR(32) NOT NULL, '
         . ' `text` TINYTEXT NOT NULL, '
         . ' `time` BIGINT UNSIGNED NOT NULL,'
         . ' INDEX (`id`)'
         . ' )'
         . ' ENGINE = myisam;';
        $results = $wpdb->query($sql);
        $sql = 'CREATE TABLE `' . $table_prefix . 'eblex_categories` ('
         . ' `id` VARCHAR(32) NOT NULL, '
         . ' `parent` VARCHAR(32) NOT NULL, '
         . ' `title` MEDIUMTEXT NOT NULL, '
         ///	Addition	17/06/2008	mVicenik	Foliovision
         . ' `urltitle` MEDIUMTEXT, '
         ///	end of addition
         . ' `description` MEDIUMTEXT NOT NULL, '
         . ' `keywords` MEDIUMTEXT NOT NULL, '
         . ' `nicename` MEDIUMTEXT NOT NULL, '
         . ' `time` BIGINT UNSIGNED NOT NULL, '
         . ' `visible` BOOL NOT NULL, '
         . ' `zindex` BIGINT NOT NULL,'
         . ' INDEX (`id`)'
         . ' )'
         . ' ENGINE = myisam'
         . ' CHARACTER SET utf8 COLLATE utf8_unicode_ci;';
        $results = $wpdb->query($sql);
        $sql = 'INSERT INTO `' . $table_prefix . 'eblex_categories` (`id`, `parent`, `title`, `description`, `keywords`, `nicename`, `time`, `visible`, `zindex`) VALUES (\'0\', \'\', \'Root\', \'Root directory\', \'\', \'root\', \'1\', \'1\', \'0\');';
        $results = $wpdb->query($sql);
        $sql = 'CREATE TABLE `' . $table_prefix . 'eblex_links` ('
         . ' `title` MEDIUMTEXT NOT NULL, '
         . ' `active` TINYINT NOT NULL, '
         . ' `nonreciprocal` TINYINT NOT NULL, '
         . ' `url` MEDIUMTEXT NOT NULL, '
         . ' `category` VARCHAR(32) NOT NULL, '
         . ' `description` MEDIUMTEXT NOT NULL, '
         . ' `email` MEDIUMTEXT NOT NULL, '
         . ' `reciprocalurl` MEDIUMTEXT NOT NULL, '
         . ' `status` TINYTEXT NOT NULL, '
         . ' `found` TINYTEXT NOT NULL, '
         . ' `time` BIGINT UNSIGNED NOT NULL, '
         . ' `administratorcomment` MEDIUMTEXT NOT NULL, '
         . ' `zindex` BIGINT NOT NULL, '
         ///	Addition	17/06/2008	mVicenik	Foliovision
         . ' `seo_pr` MEDIUMTEXT NOT NULL, '
         . ' `seo_gcache` MEDIUMTEXT NOT NULL, '
         . ' `seo_ylinks` MEDIUMTEXT NOT NULL, '
         . ' `seo_alexa` MEDIUMTEXT NOT NULL, '
         . ' `seo_cached` MEDIUMTEXT NOT NULL, '
         ///	end of addition
         . ' `id` VARCHAR(32) NOT NULL,'
         . ' INDEX (`id`)'
         . ' )'
         . ' ENGINE = myisam'
         . ' CHARACTER SET utf8 COLLATE utf8_unicode_ci;';
        $results = $wpdb->query($sql);
    ///	Addition	30/06/2008	mVicenik	Foliovision 
    } else {
    	$sql = 'ALTER TABLE `' . $table_prefix . 'eblex_links` ADD COLUMN `seo_pr` MEDIUMTEXT NOT NULL';
    	$results = $wpdb->query($sql);
    	$sql = 'ALTER TABLE `' . $table_prefix . 'eblex_links` ADD COLUMN `seo_gcache` MEDIUMTEXT NOT NULL';
    	$results = $wpdb->query($sql);
    	$sql = 'ALTER TABLE `' . $table_prefix . 'eblex_links` ADD COLUMN `seo_ylinks` MEDIUMTEXT NOT NULL';
    	$results = $wpdb->query($sql);
    	$sql = 'ALTER TABLE `' . $table_prefix . 'eblex_links` ADD COLUMN `seo_alexa` MEDIUMTEXT NOT NULL';
    	$results = $wpdb->query($sql);
    	$sql = 'ALTER TABLE `' . $table_prefix . 'eblex_links` ADD COLUMN `seo_cached` MEDIUMTEXT NOT NULL';
    	$results = $wpdb->query($sql);
    	$sql = 'ALTER TABLE `' . $table_prefix . 'eblex_categories` ADD COLUMN `urltitle` MEDIUMTEXT';
    	$results = $wpdb->query($sql);
    ///	Addition	24/11/2010	zUhrikova	Foliovision 
    	$sql = 'ALTER TABLE `' . $table_prefix . 'eblex_links` ADD COLUMN `found` MEDIUMTEXT';
    	$results = $wpdb->query($sql);
    }
    ///	end of addition
} 
// UNINSTALL
function eblex_uninstall()
{
    global $table_prefix, $wpdb;
   // $eblex_settings = $table_prefix . "eblex_settings";
    $eblex_categories = $table_prefix . "eblex_categories";
    $eblex_links = $table_prefix . "eblex_links";
    $eblex_deactivate = get_option('wp_link_robot_deactivate');
    if ($eblex_deactivate == "1") {
        eblex_executequery('DROP TABLE `' . $table_prefix . 'eblex_settings`');
        eblex_executequery('DROP TABLE `' . $table_prefix . 'eblex_categories`');
        eblex_executequery('DROP TABLE `' . $table_prefix . 'eblex_links`');
        eblex_executequery('DROP TABLE `' . $table_prefix . 'eblex_captcha`');
    } else {
    } 
}


function wp_link_robot_construct_link( $link, $cat = null, $subcat = null, $page = null ) {
	$trailing_slash = false;
	if( preg_match( '~/$~', $link ) ) {
		$trailing_slash = true;	
	}
	
	if( isset($cat) ) {
		if( !$trailing_slash ) {
			$link .= '/';
		}
		$link .= $cat;
		if( $trailing_slash ) {
			$link .= '/';
		}
	}
	if( isset($subcat) ) {
		if( !$trailing_slash ) {
			$link .= '/';
		}
		$link .= $subcat;
		if( $trailing_slash ) {
			$link .= '/';
		}
	}
	if( isset($page) && $page > 1 ) {
		if( !$trailing_slash ) {
			$link .= '/';
		}
		$link .= $page;
		if( $trailing_slash ) {
			$link .= '/';
		}
	}	
	return $link;
}


function wp_link_robot_page_link( $link ) {
	global $wp_query;
	
	$page_slug = trim(get_option('wp_link_robot_pageslug'));

	if( $page_slug &&
		$wp_query->query_vars['pagename'] == $page_slug	&& 
		( isset($wp_query->query_vars['link_robot_category']) || 
		isset($wp_query->query_vars['link_robot_subcategory']) || 
		isset($wp_query->query_vars['link_robot_page']) )
	) {	
		if( preg_match( '~'.$page_slug.'/?~', $link ) ) {
			$link = wp_link_robot_construct_link( $link, $wp_query->query_vars['link_robot_category'], $wp_query->query_vars['link_robot_subcategory'], $wp_query->query_vars['link_robot_page'] );
		}
	}
	return $link;
}

function wp_link_robot_wp_head() {
	global $wp_query;
	if( isset($wp_query->query_vars['link_robot_subcategory']) || isset($wp_query->query_vars['link_robot_category']) ) {
		/*remove_action( 'wp_head', 'rel_canonical' );
		global $fvseo;
		if( $fvseo ) {
			remove_action('wp_head', array($fvseo, 'wp_head'));
		}*/
		remove_action( 'wp_head', 'feed_links_extra', 3 );
	}
}
?>