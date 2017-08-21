<?php
$url='http://www.applyabroad.org/forum/';
$username='testtesttest';
$password='test';
$start=$_GET['s'];
$end=$_GET['e'];

$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER,false);
curl_setopt($ch, CURLOPT_COOKIEFILE, 'veri.js'); 
curl_setopt($ch, CURLOPT_COOKIEJAR, 'veri.js'); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch, CURLOPT_COOKIESESSION,true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);
curl_setopt($ch, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
curl_setopt($ch, CURLOPT_REFERER, $url.'index.php');
curl_setopt($ch, CURLOPT_URL, $url.'login.php?do=login'); 
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "vb_login_username=$username&vb_login_password&s=&securitytoken=guest&do=login&vb_login_md5password=".md5($password)."&vb_login_md5password_utf=".md5($password));
$exec = curl_exec($ch);

curl_setopt($ch, CURLOPT_POST, 0);
curl_setopt($ch, CURLOPT_REFERER, $url.'login.php?do=login'); 
curl_setopt($ch, CURLOPT_URL, $url.'clientscript/vbulletin_global.js?v=373'); 
$exec = curl_exec($ch);

curl_setopt($ch, CURLOPT_REFERER, $url.'login.php?do=login');
curl_setopt($ch, CURLOPT_URL, $url.'index.php'); 
$exec = curl_exec($ch);

curl_setopt($ch, CURLOPT_REFERER, $url.'index.php');
////

require 'html_dom.php';

//while
for ($num=$start; $num <= $end; $num++) { 
	$posturl='http://www.applyabroad.org/forum/admissionreport.php?do=viewreport&ar='.$num;
	$html=$li=$t=$rows=NULL;
	try {
		curl_setopt($ch, CURLOPT_URL, $posturl); 
		$exec = curl_exec($ch);
		$html = str_get_html($exec);
		$li = $html->find('div.cp_content>div>ol.admissionreult',0)->find('li');
		if(strpos($li[0]->plaintext, 'BachelorsMastersDoctorate') !== false)continue;
		foreach ($li as $v) {
			$t=$v->plaintext;
			$t=str_replace('&nbsp;', ' ', $t);
			$t=preg_replace('/\s+/', ' ', $t);
			$t=trim($t);
			$t=htmlentities($t);
			$rows[]=$t;
		}
		$result[]=$rows;
	} catch (Exception $e) {}
}
$address="json/$start.json";
// print_r($result);
$result=json_encode($result,JSON_UNESCAPED_UNICODE);
$handle = fopen($address, "w");
fwrite($handle, $result);
fclose($handle);
// header('Content-Type: application/octet-stream');
// header('Content-Disposition: attachment; filename='.basename("$address"));
// header('Expires: 0');
// header('Cache-Control: must-revalidate');
// header('Pragma: public');
// header('Content-Length: ' . filesize("$address"));
// readfile("$address");