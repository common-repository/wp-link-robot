<?php
/*
Plugin Name: Link Exchange Manager
Plugin URI: http://www.ebrandmarketing.com.au/wordpress-link-directory/
Description: This script prints the data from some web pages that are used in SEO 
Author: Foliovision
Version: 0.1
Author URI: http://www.foliovision.com/

This is a add-on to existing Link Exchange Manager, version 1.4, author: eBrandMarketing
*/ 
//

$google = "www.google.com";

if(isset($_GET['ch']) && isset($_GET['site'])) {
	$url = "http://www.google.com/search?client=navclient-auto&ch=".$_GET['ch']."&features=Rank&q=info:".$_GET['site'];
	$site = fopen($url,"r");
	echo fread($site,255);
}
if(isset($_GET['gcache'])) {
	//die("not working now");
	$query = explode("://",$_GET['gcache']);
	if(sizeof($query)==1)
		$query = $query[0];
	else
		$query = $query[1];
	
	$fp = fsockopen($google, 80, $errno, $errstr, 30);
	if (!$fp) {
		die('error');
    	//echo "$errstr ($errno)<br />\n";
	}
	else {
    	$out = "GET /search?q=cache%3A".$query." HTTP/1.1\r\n";
    	$out .= "Host: ".$google."\r\n";
    	$out .= "User-Agent: Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9) Gecko/2008052912 Firefox/3.0\r\n";
    	$out .= "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n";
    	$out .= "Referer: http://".$google."\r\n";
    	$out .= "Connection: Close\r\n\r\n";
    }

    fwrite($fp, $out);
    $content = '';
    while (!feof($fp)) {
        $content .= fgets($fp, 128);
    }
    fclose($fp);
    
    if(isset($_GET['debug'])) echo $content;
    $explosion = explode("Location: ",$content);
    $explosion = explode("Content-Type: ",$explosion[1]);
    if($explosion[0]=='')
    	die("no cache");
    if(isset($_GET['debug'])) echo 'url: '.$explosion[0].'<br />';
    $url_to_open = $explosion[0];

	$host = explode("://",$explosion[0]);
	$host = explode("/",$host[1],2);
	$host[1] = rtrim($host[1]);
	if(isset($_GET['debug'])) echo 'host: '.$host[0].'<br />';
	if(isset($_GET['debug'])) var_dump($host);
	if(isset($_GET['debug'])) echo '<br />path: '.$host[1].'^^<br />';

	$fp = fsockopen($host[0], 80, $errno, $errstr, 30);
	if (!$fp) {
		die('error');
    }
    else {
    	$out = "GET /".$host[1]." HTTP/1.1\r\n";
    	$out .= "Host: ".$host[0]."\r\n";
    	$out .= "User-Agent: Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9) Gecko/2008052912 Firefox/3.0\r\n";
    	//$out .= "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n";
    	//$out .= "Referer: http://".$google."/search?q=cache%3A".$_GET['gcache']."\r\n";
    	$out .= "Connection: Close\r\n\r\n";
    }
    
    fwrite($fp, $out);
    $content = '';
    while (!feof($fp)) {
        $content .= fgets($fp, 128);
    }
    fclose($fp);
	
	if(isset($_GET['debug'])) 
    echo $content;  	
  	
    $explosion = explode("as it appeared on ",$content);
    $explosion = explode("<",$explosion[1]);
    if($explosion[0]=='')
    	echo 'no cache';
    else
    	echo $explosion[0];
}
if(isset($_GET['ylinks'])) {
	$url = "http://search.yahoo.com/search?p=linkdomain:".$_GET['ylinks']."%20-site:".$_GET['ylinks'];
	echo $url;
	//$site = fopen($url,"r");
	if(($site = fopen($url,"r"))==false)
		die("error");
		
	$magic = '<div id="info"><p><span id="infotext"><p>1';
	$i = 0;
	
	do {
		$tmp = fgetc($site);
		if($tmp==$magic[$i])
			$i++;
		else
			$i = 0;
		if($tmp===FALSE)
			die("error");
	} while ($i < strlen($magic));	
	
	$magic2 = 'of';
	$i=0;
	
	do {
		$tmp = fgetc($site);
		if($tmp==$magic2[$i])
			$i++;	
		else
			$i = 0;
		if($tmp===FALSE)
			die("error");
	} while ($i < strlen($magic2));	

	do {
		$tmp = fgetc($site);
		if($tmp=='f')
			break;	
		else
			echo $tmp;
		if($tmp===FALSE)
			die("error");
	} while (1>0);	
	
}

if(isset($_GET['alexa'])) {
	$url = "http://data.alexa.com/data?cli=10&dat=s&url=".$_GET['alexa'];
	$site = fopen($url,"r");
	
	$nodata= '<ALEXA VER=';
	
	$magic = '<POPULARITY URL=';
	$i = 0;
	$j = 0;
	$siteok = 0;
	
	do {
		$tmp = fgetc($site);
		if($tmp==$magic[$i])
			$i++;
		else
			$i = 0;
		if($tmp==$nodata[$j])
			$j++;
		else
			$j = 0;
		if($j==strlen($nodata))
			$siteok = 1;
		if(isset($_GET['debug']))
			echo $tmp;
		if($tmp===FALSE) {
			if($siteok==1)
				die("no data");
			else
				die("error");
		}
	} while ($i < strlen($magic));	
	
	$magic2 = 'TEXT="';
	$i=0;
	
	do {
		$tmp = fgetc($site);
		if($tmp==$magic2[$i])
			$i++;
		else
			$i = 0;
		if(isset($_GET['debug']))
			echo $tmp;
		if($tmp===FALSE) {
			if($siteok==1)
				die("no data");
			else
				die("error");
		}
	} while ($i < strlen($magic2));	
	
	do {
    $tmp = fgetc($site);
    	if($tmp===FALSE)
    		die("error");
    	if(isset($_GET['debug']))
			echo $tmp;
		if($tmp=='"')
			break;
		$result1.=$tmp;
	} while (1>0);
	
	$j=strlen($result1)%3;
	for($i=0;$i<strlen($result1);$i++) {
		if($i==$j & $j!=0)
			echo ',';
		if($i==$j+3)
			echo ',';
		if($i==$j+6)
			echo ',';			
		if($i==$j+9)
			echo ',';			
		echo $result1[$i];
	}	
}

if(isset($_GET['gcached'])) {
	//$url = "http://www.google.com/search?q=site%3A".$_GET['gcached'];
	//$site = fopen($url,"r");
	
	
	$site = @fsockopen($google, 80, $errno, $errstr, 30);
	if (!$site) {
		die('errror');
    	//echo "$errstr ($errno)<br />\n";
	}
	else {
    	$out = "GET /search?q=site%3A".$_GET['gcached']." HTTP/1.1\r\n";
    	$out .= "Host: ".$google."\r\n";
    	$out .= "User-Agent: Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9) Gecko/2008052912 Firefox/3.0\r\n";
    	$out .= "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n";
    	$out .= "Referer: http://".$google."\r\n";
    	$out .= "Connection: Close\r\n\r\n";
    }

    fwrite($site, $out);
   
    $magic = 'Results';
	$i = 0;
	$wrong = 'did not match any documents.';
	$j = 0;
	
	do {
		$tmp = fgetc($site);
		if($tmp==$magic[$i])
			$i++;
		else
			$i = 0;
		if($tmp==$wrong[$j]) {
			$j++;
			}
		else
			$j = 0;
		if($j==strlen($wrong) && !isset($_GET['debug'])
)			die("no cache");
		if(isset($_GET['debug']))
			echo $tmp;
		if($tmp===FALSE)
			die("error");
	} while ($i < strlen($magic));	
	
	$magic2 = '</b> of ';
	$i=0;
	
	do {
		$tmp = fgetc($site);
		if($tmp==$magic2[$i])
			$i++;
		else
			$i = 0;
		if($tmp===FALSE)
			die("error");
	} while ($i < strlen($magic2));	
	
	$magic2 = '<b>';
	$i=0;
	
	do {
		$tmp = fgetc($site);
		if($tmp==$magic2[$i])
			$i++;
		else
			$i = 0;
		if($tmp===FALSE)
			die("error");
	} while ($i < strlen($magic2));		
	
	do {
    $tmp = fgetc($site);
    if($tmp===FALSE)
			die("error");
		if($tmp=='<')
		  break;
		echo $tmp;
    
  } while (1>0);
  
  fclose($site);
	
}


?>