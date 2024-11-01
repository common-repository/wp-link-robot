<?php
require_once( 'browse-functions.php');

 ?>
 <style type="text/css">
<!--
.plink {
border:0px;
font-size:12px;
font-weight:bold;
color:#000000;
}
.robotnewlink {
  padding:0px;
  border:0px;
}
.robotnewlink td{
  padding:0px;
  border:0px;
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
-->
</style>
	<div class="wrap" style="clear:left">
  <h2>Links</h2>
	<p>
  <?php
  $sSelValue = isset($_POST['category']) ? $_POST['category'] : "-1";
  ?> 
  <form method="post" name="filter" action="">
  Category filter: <select name="category">
   
	<option value="-1" <?php if ($sSelValue == "-1"){ echo 'selected="selected"';}?>>All</option>
	<?php
	global $wpdb;
  $aaCategories = $wpdb->get_results( "SELECT id,title FROM `{$wpdb->prefix}eblex_categories` WHERE parent = \"\" ORDER BY `zindex` DESC,`title` ASC" );
  $aSubCats = array();
  foreach($aaCategories as $aCategory){
    $sAppender1 = ($aCategory->id == $sSelValue) ?  'selected="selected"' : "";
    echo "<option value=\"{$aCategory->id}\" $sAppender1>{$aCategory->title}</option>\n";
    if (!empty($aCategory->id)){
      $aaSubCategories = $wpdb->get_results( "SELECT id,title FROM `{$wpdb->prefix}eblex_categories` WHERE parent = \"{$aCategory->id}\" ORDER BY `zindex` DESC,`title` ASC" );
      foreach($aaSubCategories as $aSubcategory){
        if (!empty ($sAppender1)) $aSubCats[] = $aSubcategory->id; 
        $sAppender2 = ($aSubcategory->id == $sSelValue) ?  'selected="selected"' : "";
        echo "<option value=\"{$aSubcategory->id}\" $sAppender2>&nbsp;&nbsp;&nbsp;{$aSubcategory->title}</option>\n";
      }
    }    
  }?>
  <option value="2" <?php if ($sSelValue == "2"){ echo 'selected="selected"';}?>>TRASH</option>
	</select>
	<input value="Filter" class="button-secondary" type="submit" />
	</form>
	</p>
	
			
<?php
  //$sWhere = (isset($_POST['category']) AND $_POST['category']!="-1")? "WHERE category = \"{$_POST['category']}\" AND status != '2'":"";
  $sWhere = "WHERE status != '2'";
  if (isset($_POST['category']) AND $_POST['category'] != "-1" )
    {
      if ($_POST['category'] == "2"){
        $sWhere = "WHERE status = '2'";
      }else {
      $sSubCats = "";
      if (!empty($aSubCats)) $sSubCats = "IN (\"" . implode("\", \"",$aSubCats) . "\")";
      $sWhere .= (empty($sSubCats)) ? "AND category = \"{$_POST['category']}\"" : "AND (category = \"{$_POST['category']}\" OR category $sSubCats )";
      }
      
    }
   //pagination
	$iLinkscount = $wpdb->get_var( "SELECT count(*) FROM `{$wpdb->prefix}eblex_links`" . $sWhere . " ORDER BY `zindex` DESC,`title` ASC" );
   $iLinks_per_page = 20;
	$iNumber_of_pages = ceil($iLinkscount / $iLinks_per_page);
	$iPage = 1;
	if(isset($_GET['p'])) $iPage = $_GET['p'];
	$iFrom_link = ($iPage-1)*$iLinks_per_page;
   if($iLinkscount < $iFrom_link) {$iPage = 1;$iFrom_link=1;}
   $next_page = $iPage+1;
   $prev_page = $iPage-1;
   
   $pagination_output =  '<div style="float:right">Page: ';
   // previous page arrow
  	if (!$_GET['sub']) $_GET['sub'] = 'browse';

   if($prev_page > 0) $pagination_output .=  '<a href="tools.php?page='.$_GET['page'].'&sub='. $_GET['sub'] .'&p='.$prev_page.'">&laquo;</a>';
   $pagination_output .=  "&nbsp;&nbsp;";
   // showing the page numbers 
   for($k=0; $k < $iLinkscount/$iLinks_per_page; $k++){
      $page = $k+1;
      $pagenumber = '<a href="tools.php?page='.$_GET['page'].'&sub='. $_GET['sub'];
      if ($page > 1) $pagenumber .= '&p='.$page;    // don't use number if poiting to the first page
      $pagenumber .= '"';
      if ($page==$iPage) 
         $pagenumber .= ' style="font-weight:bold"';  // use bold on the actual page
      $pagenumber .= '>'.$page.'</a>';
      
      $pagination_output .=  $pagenumber . "&nbsp;&nbsp;";
   } 
   
   // next page arrow
   if (($next_page-1)*$iLinks_per_page < $iLinkscount)
      $pagination_output .=  '<a href="tools.php?page='.$_GET['page'].'&sub='. $_GET['sub'] .'&p='.$next_page.'">&raquo;</a>';
   $pagination_output .=  '</div>';
   //end of pagination

	$links = $wpdb->get_results( "SELECT * FROM `{$wpdb->prefix}eblex_links`" . $sWhere . " ORDER BY `zindex` DESC,`title` ASC LIMIT ".$iFrom_link.",".$iLinks_per_page );

	if ($wpdb->num_rows){ ?>
	 <table class="widefat">
    <thead>
	   <tr><th>Title</th><th>Category</th><th>Description</th><th>Notes</th><th>Reciprocal URL</th><th>Priority Index</th><th>Found</th><th>Actions</th></tr>
    </thead>
    <tbody>
	<?php 
	$checkalllinks = "";
    foreach( $links AS $link ) {
			//if ($link->status == '2') continue;
      LinkHTML( $link );
      $checkalllinks .= "lnkrob_checkLink('{$link->id}');";
		}
	?>
	<tr id="AddNewButton"><td colspan="8">  <a id="AddNewLink" href="javascript:void(0);" onclick="lnkrob_AddNewLink('AddNewLink',<?php echo "'$sSelValue'";?>)">[Add New]</a>
	<?php echo($pagination_output); ?>
</td></tr>
  </tbody>
  </table>
  
  <?php 
  

  ?>
  
  <p><a id="CheckAllLinks" href="javascript:void(0);" onclick="<?php echo $checkalllinks;?>">Check all links</a></p>
<?php
  } 
	else{
  ?>
	<p>There aren't any links in the selected category...</p>
	<?php 
  }?>
	<br />
  
  </div>

