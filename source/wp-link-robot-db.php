<?php
require_once(dirname( __FILE__ ) . '/../../../../wp-admin/admin.php');

global $table_prefix, $wpdb;
//$eblex_settings = $table_prefix . "eblex_settings";
$eblex_categories = $table_prefix . "eblex_categories";
$eblex_links = $table_prefix . "eblex_links";

if(isset($_GET['id'])) {
    $id = $_GET['id'];

    if(isset($_GET['rank'])) {
        $rank = $_GET['rank'];
        $wpdb->query("UPDATE `$eblex_links` SET `seo_pr`='$rank' WHERE `id`='$id'");
        echo 'rank ok';
    }
    if(isset($_GET['gcache'])) {
        $gcache = $_GET['gcache'];
        $wpdb->query("UPDATE `$eblex_links` SET `seo_gcache`='$gcache' WHERE `id`='$id'");
        echo 'gcache ok';
    }
    if(isset($_GET['ylinks'])) {
        $ylinks = $_GET['ylinks'];
    	$wpdb->query("UPDATE `$eblex_links` SET `seo_ylinks`='$ylinks' WHERE `id`='$id'");
    	echo 'ylinks ok';
    }
    if(isset($_GET['alexa'])) {
        $alexa = $_GET['alexa'];
        $wpdb->query("UPDATE `$eblex_links` SET `seo_alexa`='$alexa' WHERE `id`='$id'");
        echo 'alexa ok';
    }
    if(isset($_GET['gcached'])) {
        $gcached = $_GET['gcached'];
        $wpdb->query("UPDATE `$eblex_links` SET `seo_cached`='$gcached' WHERE `id`='$id'");
        echo 'gcached ok';
    }
    if(isset($_GET['comment'])) {
        $comment = $_GET['comment'];
        $wpdb->query("UPDATE `$eblex_links` SET `administratorcomment`='$comment' WHERE `id`='$id'");
        echo 'comment ok';
    } 
    if(isset($_GET['getcomment'])) {
        $result = $wpdb->get_var("SELECT `administratorcomment` FROM `$eblex_links` WHERE `id`='$id'");
        echo $result;
    }       
}
else {
  die("error");
}

?>