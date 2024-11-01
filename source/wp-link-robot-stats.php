<?php
require_once(dirname( __FILE__ ) . '/../../../../wp-admin/admin.php');
$title = __('Link Exchange - Statistics');
//$parent_file = 'wp-link-robot.php';
$today = current_time('mysql', 1);
require_once(dirname( __FILE__ ) . '/../../../../wp-admin/admin-header.php');

$eblex_categories = $table_prefix . "eblex_categories";
$eblex_links = $table_prefix . "eblex_links";

$l_categorycount = $wpdb->get_var("SELECT count(*) FROM `$eblex_categories` WHERE `parent`=''");
$l_subcategorycount = $wpdb->get_var("SELECT count(*) FROM `$eblex_categories` WHERE `parent`!=''");
$l_linkcount = $wpdb->get_var("SELECT count(*) FROM `$eblex_links`");
$l_activelinkcount = $wpdb->get_var("SELECT count(*) FROM `$eblex_links` WHERE `status`='1'");
$l_inactivelinkcount = $wpdb->get_var("SELECT count(*) FROM `$eblex_links` WHERE `status`=0");
$l_approvedlinkcount = $wpdb->get_var("SELECT count(*) FROM `$eblex_links` WHERE `active`='1'");
$l_dissaprovedlinkcount = $wpdb->get_var("SELECT count(*) FROM `$eblex_links` WHERE `active`=0");
$l_reciprocallinkcount = $wpdb->get_var("SELECT count(*) FROM `$eblex_links` WHERE `reciprocalurl`!=''");
$l_linkcount = $wpdb->get_var("SELECT count(*) FROM `$eblex_links`");


//----------------------------------- Search Engine -------------------------------------

$url=$_POST['url'];

function eblex_getsestats($url)
{
	$url=urlencode(str_replace("http://","",$url));
    
    //GOOGLE
    $urlgoogle="http://www.google.com/search?hl=en&q=link%3A$url";
    $file=fopen($urlgoogle, "r");
    if ($file!=NULL) {
        while (!feof($file)) {
            $data .= fread($file, 8192);
        }
        fclose($file);
        
//        preg_match('/About (\d+) results/',$data, $matches );  Why not use only this simple regex?
//        echo($matches[1]);
        if (strpos($data, "did not match any documents")===false){
           $pos=strpos($data,"resultStats")+18;
           $google="";
           $i=$pos;
           while (1) {
               if ($data[$i]=="<") {
                   break;
               }
               $google.=$data[$i];
               $i++;
               if ($i>$pos+100) {
                   break;
               }
           }
           if (strlen($google)>21) {
               $google="No backlinks found!";
           }
           if ($google!="No backlinks found!") {
               $one=str_replace(",","",$google);
               $one=intval(str_replace(" ","",$one));
           } else {
               $one=0;
           }
         }
         else{
           $one = 0;
           $google="No backlinks found!";
         }
      } else {
        $one=0;
        $google="Error!";
    }
    $data="";
    
    //MSN
    $urlmsn="http://search.msn.com/results.aspx?q=site%3A$url&FORM=QBHP";
    $file=@fopen($urlmsn, "r");
    $msn="";
    if ($file!=NULL) {
      while (!feof($file)) {
          $data .= fread($file, 8192);
      }
      fclose($file);
      if (strpos($data, "did not find any results")===false){
        $pos=strpos($data,'class="sb_count" id="count"')+36;
        $i=$pos;
        while (1) {
            if ($data[$i]==" ") {
                break;
            }
            $msn.=$data[$i];
            $i++;
            if ($i>$pos+100) {
                break;
            }
        }
        if (($msn=="html")||($msn=="")) {
            $msn="No backlinks found!";
        }
        
        if ($msn!="No backlinks found!") {
            $two=str_replace(",","",$msn);
            $two=intval(str_replace(" ","",$two));
        } else {
            $two=0;
            $msn="No backlinks found!";
        }
      }
      else{
        $two=0;
        $msn="No backlinks found!";
       }
    } else {
        $two=0;
        $msn="Error!";
    }
    $data="";
    //YAHOO!
    //$urlyahoo="http://search.yahoo.com/search?p=site%3A$url&prssweb=Search&ei=UTF-8&fr=sfp&x=wrt";
    $urlyahoo="http://siteexplorer.search.yahoo.com/siteexplorer/search?p=$url&bwm=p&bwms=p&fr=yfp-t-702&fr2=seo-rd-se";
    $file=@fopen($urlyahoo, "r");
    if ($file!=NULL) {
        while (!feof($file)) {
            $data .= fread($file, 8192);
        }
        fclose($file);
        if (strpos($data, "No results found.")===false){
        
//        $pos=strpos($data,"resultCount")+13;
          $pos=strpos($data,"Pages (")+7;
          $yahoo="";
          $i=$pos;
          while (1) {
              if ($data[$i]==")") {
                  break;
              }
              $yahoo.=$data[$i];
              $i++;
              if ($i>$pos+100) {
                  break;
              }
          }
          if ($yahoo=="html") {
              $yahoo="No backlinks found!";
          }
          if ($yahoo!="No backlinks found!") {
              $three=str_replace(",","",$yahoo);
              $three=str_replace(" ","",$three);
              $three=str_replace("<strong>","",$three);
              $three=intval(str_replace("</strong>","",$three));
          } else {
              $three=0;
          }
      }
      else{
        $three=0;
        $yahoo="No backlinks found!";
      
      }
    } else {
        $three=0;
        $yahoo="Error!";
    }
    $total=$one+$two+$three;
    $point=0;
    $ntotal="";
    $xtotal=$total."";
    for ($i=strlen($xtotal); $i>=0; $i--) {
        $ntotal=$xtotal[$i].$ntotal;
        if ($point==3 && $xtotal[$i-1]!="") {
            $ntotal=",".$ntotal;
            $point=0;
        }
        $point++;
    }
    $total=$ntotal;
	
	return array("google" => $google, "yahoo" => $yahoo, "msn" => $msn, "total" => $total);
}

if ($url != "")
{
	$stats = eblex_getsestats($url);
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
<h2><?php _e('Statistics'); ?></h2>
Number of links in database: <strong><?=$l_linkcount;?></strong><br />
Number of approved links: <strong><?=$l_approvedlinkcount;?></strong><br />
Number of links waiting for approval: <strong><?=$l_dissaprovedlinkcount;?></strong><br />
Number of active links: <strong><?=$l_activelinkcount;?></strong><br />
Number of inactive links: <strong><?=$l_inactivelinkcount;?></strong><br />
Number of reciprocal links: <strong><?=$l_reciprocallinkcount;?></strong><br />
Number of categories: <strong><?=$l_categorycount;?></strong><br />
Number of subcategories: <strong><?=$l_subcategorycount;?></strong><br />
<br />
<h2><?php _e('Search engine backlink statistics'); ?></h2>
<?php
	if ($_POST['url'] != "")
	{
?>
Google: <strong><?=$stats["google"];?></strong><br />
MSN: <strong><?=$stats["msn"];?></strong><br />
Yahoo: <strong><?=$stats["yahoo"];?></strong><br /><br />
Total: <strong><?=$stats["total"];?></strong><br /><br />
<?php
	}
?>
<form id="form1" name="form1" method="post" action="">
<label>URL to check:
<input name="url" type="text" id="title" value="<?php echo $_SERVER['HTTP_HOST'];?>" size="40" />
</label>
<br />
<p class="submit">
<input type="submit" value="<?php _e('Query search engines') ?>" name="Submit" />
</p>
</form>
</div>
<?php
//require('./admin-footer.php');
?>