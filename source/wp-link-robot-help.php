<?php
//require_once('admin.php'); 
$title = __('Link Exchange - Help');
//$parent_file = 'wp-link-robot.php';
$today = current_time('mysql', 1); 
//require_once('admin-header.php');
//require_once('magpierss/rss_fetch.inc');
//************************************************************************
?>



<div class="wrap" style="clear:left; width:600px">
<br />
  <div id="editlink">
	<h2>WP Link Robot Help</h2>
  </div>
  <p>Thank you for using WP Link Robot. For any problem, please visit our <a href="http://foliovision.com/seo-tools/wordpress/plugins/wp-link-robot">website</a> to read the how-to's or to ask questions. </p>

<h2>Setting up your Link Robot</h2>

<h3>Creating your public directory</h3>
<p>Create new page where you wish the links to appear. Switch to the source code view, and place the following code into the page content:</p><code>[wp_link_robot_genlinks]</code>
<p>Make sure you fill the slug of this make in WP Link Robot Setting, in the 'Directory slug name'.</p>
<h3>Configuration</h3>
<p>Go to setting page and fill out the important data about your site:</p>
<ul>
<li><i>My reciprocal url</i> is the url that appears in your partners directories, e.g. "http://www.example.com"</li>
<li><i>Directory's name</i> - name of your directory where the your partners links will be displayed - e.g. SEO Directory</li>
<li><i>Directory slug name</i> - once you create a page where the links will be displayed, type here the slug name</li>
</ul>
<h3>Adding new links</h3>
<p>There are two ways how you can add new backlinks:</p>
<p><i>First option</i> is from the backend of your Wordpress, in Tools > WP Link Robot > Quick add. Just fill out the form and anc click Submit link.</p>
<p><i>Second option</i>, if you like to allow your vistors to submit new links, just create a page where the form for submitting links will be placed. Switch to the source view, and place the following code where you wish the form to appear:</p><code>[wp_link_robot_addlinks]</code>

<h2>Using Link Robot</h2>
<h3>Categories</h3>
<p>Use categories to keep your links organized. There is a possibility to have categories that are not public - just uncheck the visibility checkbox when creating the category.</p>
<h3>Quick Add</h3>
<p>If you have new links to add, do it here. Just fill out the form.</p>
<h3>Browse Links, Search, Statistics</h3>
<p>Overview of your links database.</p>
<h3>Baclink Cleansing</h3>
<p>Keep your links up to date and check if your partners still reference your site.</p>
<p>Just select the category you wish to check, and 'Start cleaning'. The Link Robot will check all links in this category, this might take some time. Highlighting is used to pop up links that were not found:</p>

<ul>
<li> <i>Green links</i> - page found, the backlink was found as well. </li>
<li> <i>Yellow links</i> - the backlink was not found, or the page could not be accessed. </li>
<li> <i>Red links</i> - page not found. </li>
</ul>
<p>Check status column for details.</p>

</div>
