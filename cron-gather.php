<?php
define('INCLUDE_CHECK',true);
include 'connect.php';

$SQL = "SELECT id FROM `book`";
$result = mysql_query($SQL);
$num_rows = mysql_num_rows($result);
if ($num_rows == 0) {
$from = 1;
}
else
{
$from = ($num_rows / 12);
$from = ($from + 1);
}
$to = ($from + 2);

for ($i=$from; $i < $to ; $i++)
    {	
		$www = "http://www.amazon.com/s/ref=sr_pg_2?rh=n%3A283155%2Cp_n_publication_date%3A1250226011&page=".$i."&bbn=283155&ie=UTF8&qid=1352718226/";
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $www);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$tresc = curl_exec($ch);
		$pattern = '#<a class="title" href="(.*?)">(.*?)</a>#';
		$wynik = preg_match_all($pattern, $tresc, $matches, PREG_PATTERN_ORDER);
		unset($matches[1][13]);
		unset($matches[1][14]);
		unset($matches[1][12]);
		print_r($matches);
		
			foreach($matches[1] as $id)

			{
					
					$link = skroc($id,4);
					mysql_query("INSERT INTO `book` (`id`, `href`, `active`) VALUES ('', '$link', '0')");  

			}

	}
	
function skroc($link,$slashs){
	$array = explode('/',$link);
	$nowyLink = '';
	for ($i = 0;$i <= $slashs + 1;$i++)
	 $nowyLink .= $array[$i].'/';
return $nowyLink;
}
?>