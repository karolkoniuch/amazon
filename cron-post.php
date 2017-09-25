<?php
define('INCLUDE_CHECK',true);
require 'class-IXR.php';
include 'connect.php';

$SQL = "SELECT href, id FROM `book` WHERE active = '0' LIMIT 1";
$result = mysql_query($SQL);
$num_rows = mysql_num_rows($result);
while ($num_rows = mysql_fetch_array($result) ) {
		$link = $num_rows['href'];
		$id = $num_rows['id'];
	}

		$www = $link;
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $www);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$tresc = curl_exec($ch);
		$html = $tresc;
		
		$dom = new DOMDocument;
		@$dom->loadHTML($html);
		foreach ($dom->getElementsByTagName('h1') as $node) {
			$title = $node->nodeValue;
			$title = explode("[", $title);
			$title = $title[0];
		}
		
        $xml = new DOMDocument();
		@$xml->loadHTML($html);
		foreach($xml->getElementsByTagName('img') as $image) {
			if(strstr($image->getAttribute('id'),'original-main-image')==true){
				$images[] = $image->getAttribute('src');
			}
		}
		$img = $images[0];
		$img = "<center><img src=".$img."></center>";
		
		$pattern = '#<div id="postBodyPS">(.*?)</div>#';
		$wynik = preg_match_all($pattern, $html, $matches, PREG_PATTERN_ORDER);
		$opis = $matches[0][0];
		
		$pattern = '#<li>(.*?)</li>#';
		$wynik = preg_match_all($pattern, $html, $matches, PREG_PATTERN_ORDER);
		
		$stron = $matches[0][2];
		$stron = explode(":", $stron);
		$stron = $stron[1];
		$stron = preg_replace('/[^0-9]/', '', $stron);
		if (is_numeric($stron) && ($stron > 20)){
		$stronn = $stron;
		}
		
		$stron = $matches[0][4];
		$stron = explode(":", $stron);
		$stron = $stron[1];
		$stron = preg_replace('/[^0-9]/', '', $stron);
		if (is_numeric($stron) && ($stron > 20)){
		$stronn = $stron;
		}
		
		$stron = $matches[0][3];
		$stron = explode(":", $stron);
		$stron = $stron[1];
		$stron = preg_replace('/[^0-9]/', '', $stron);
		if (is_numeric($stron) && ($stron > 20)){
		$stronn = $stron;
		}
		
		$pattern = '#<meta name="keywords" content="(.*?)" />#';
		$wynik = preg_match_all($pattern, $html, $matches, PREG_PATTERN_ORDER);
		$matches[1][0];
		$keywords = $matches[1][0];
		
		$size = rand(500, 2999).KB;
		$foter = "<br><br><center><h2>Product Details</h2><br><b>Pages</b> : ".$stronn."<br><b>Language</b> : English<br><b>File Size</b> : ".$size."<br></center>";
		$opis = $img .= $opis .= $foter;
		
		
 
		$q = new IXR_Client('http://'.$domena.'/xmlrpc.php');
		 
		$note = array(
			'title'             => $title,   //tytu³
			'description'        => '',  //zajawka
			'mt_text_more'      => $opis,    //treœæ
			'categories'        => array('Uncategorized'),   //nazwy kategorii jako elementy tablicy
			'mt_keywords'       => array($keywords), // tagi
			//'dateCreated'       => date(DATE_RFC822, mktime(0, 0, 0, 1, 1, 2000)),   //data publikacji wpisu
			//'wp_password'     => 'hasuo',    //has³o wpisu
			//'mt_allow_pings'    => true,  //zezwalaæ na pingbacki?
			//'mt_allow_comments' => true,  //a na komentarze?
		);
		 
		if(!$q->query('metaWeblog.newPost', 1, $admin_nick, $admin_pass, $note, true)){
			echo $q->getErrorCode().': '.$q->getErrorMessage();
		}
		
		mysql_query("UPDATE book SET active = '1' WHERE id = '$id'");
		 
		var_dump($q->getResponse());
	  
?>