<?php
function cleanText($t)
{
	$t=str_replace('&nbsp;', ' ', $t);
	$t=preg_replace('/\s+/', ' ', $t);
	$t=trim($t);
	$t=htmlentities($t);
	if ($t=='---' || $t=='N/A') {
		$t='';
	}
	return $t;
}
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
echo "<pre>";
require 'html_dom.php';
//while
for ($num=$start; $num <= $end; $num++) { 
	$posturl='http://www.applyabroad.org/forum/admissionreport.php?&pp=100&order=desc&sort=changeTime&page='.$num;
	$html=$tr=$t=$rows=NULL;
	try {
		curl_setopt($ch, CURLOPT_URL, $posturl); 
		$exec = curl_exec($ch);
		$html = str_get_html($exec);
		$tr = $html->find('table#reportlist_table tr');
		foreach ($tr as $v) {
			$rows=$td=NULL;
			$td=$v->find('td');	
			if(!isset($td[0]))continue;
			$rows['id']=$td[0]->find('a')[0]->href;
			$rows['id']=substr($rows['id'], 41);
			$rows['field']=cleanText($td[1]->plaintext);
			$rows['apUni']=cleanText($td[2]->plaintext);
			$rows['apDegree']=cleanText($td[3]->plaintext);
			$rows['fund']=cleanText($td[4]->plaintext);
			$rows['bachelorsAvg']=cleanText($td[5]->plaintext);
			$rows['bachelorsUni']=cleanText($td[6]->plaintext);
			$rows['masterAvg']=cleanText($td[7]->plaintext);
			$rows['masterUni']=cleanText($td[8]->plaintext);
			$rows['engExamType']=cleanText($td[9]->plaintext);
			$rows['engExamResult']=cleanText($td[10]->plaintext);
			$rows['engExamQuan']=cleanText($td[11]->plaintext);
			$rows['engExamAnlt']=cleanText($td[12]->plaintext);
			$rows['engExamVrbl']=cleanText($td[13]->plaintext);
			$rows['papers']=cleanText($td[14]->plaintext);
			$rows['country']=cleanText($td[15]->plaintext);
			$rows['year']=cleanText($td[16]->plaintext);
			$result[]=$rows;
		}
	} catch (Exception $e) {}
}
$address="t$start.json";
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