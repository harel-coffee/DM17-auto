<?php
function cleanText($t)
{
	$t=str_replace('&nbsp;', ' ', $t);
	$t=preg_replace('/\s+/', ' ', $t);
	$t=trim($t);
	$t=htmlentities($t);
	if ($t=='---' || $t=='N/A'|| $t=='n/a') {
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
		foreach ($li as $r) {
			$r=$r->plaintext;
			if(strpos($r, 'BachelorsMastersDoctorate') !== false)throw new Exception();
			$exp=NULL;
			if (preg_match('/مقطع پذیرش:/', $r)) {
				$exp = preg_split( '/(مقطع پذیرش:|رشته:)/', $r);
				if(isset($exp[1]))$rows['field']=cleanText($exp[1]);
				if(isset($exp[2]))$rows['apDegree']=cleanText($exp[2]);
			}elseif (preg_match('/کارشناسی:/', $r)) {
				$r=trim(str_replace('کارشناسی:', '', $r));
				$exp = preg_split( '/(رشته:|دانشگاه:|معدل:)/', $r);
				if(isset($exp[1]))$rows['bachelorsAvg']=cleanText($exp[1]);
				if(isset($exp[2]))$rows['bachelorsUni']=cleanText($exp[2]);
			}elseif (preg_match('/کارشناسی ارشد:/', $r)) {
				$r=trim(str_replace('کارشناسی:', '', $r));
				$exp = preg_split( '/(رشته:|دانشگاه:|معدل:)/', $r );
				if(isset($exp[1]))$rows['masterAvg']=cleanText($exp[1]);
				if(isset($exp[2]))$rows['masterUni']=cleanText($exp[2]);
			}elseif (preg_match('/نمره امتحان زبان:/', $r)) {
				$exp = preg_split( '/(نمره امتحان زبان:|نوع امتحان زبان:)/', $r);
				if(isset($exp[1]))$rows['engExamResult']=cleanText($exp[1]);
				if(isset($exp[2]))$rows['engExamType']=cleanText($exp[2]);
			}elseif (preg_match('/نمره امتحان GRE:/', $r)) {
				$exp = preg_split( '/(:Vrbl.|:Anlt.|:Qunt.|نمره امتحان GRE:)/', $r);
				if(isset($exp[1]))$rows['engExamQuan']=cleanText($exp[1]);
				if(isset($exp[2]))$rows['engExamAnlt']=cleanText($exp[2]);
				if(isset($exp[3]))$rows['engExamVrbl']=cleanText($exp[3]);
			}elseif (preg_match('/تعداد مقالات:/', $r)) {
				$exp = preg_split( '/(خارجی:|داخلی:|تعداد مقالات:)/', $r);
				if(isset($exp[2])){
					$rows['papersGLOB']=cleanText($exp[2]);
					if($rows['papersGLOB']=='')$rows['papersGLOB']='0';
				}
				if(isset($exp[3])){
					$rows['papersIRAN']=cleanText($exp[3]);
					if($rows['papersIRAN']=='')$rows['papersIRAN']='0';
				}
			}elseif (preg_match('/سال شروع تحصیل:/', $r)) {
				$rows['year']=cleanText(str_replace('سال شروع تحصیل:', '', $r));
			}elseif (preg_match('/دانشگاه‌های پذیرفته شده:/', $r)) {
				$rows['accUni']=cleanText(str_replace('دانشگاه‌های پذیرفته شده:', '', $r));
			}elseif (preg_match('/دانشگاه‌های رد شده:/', $r)) {
				$rows['rejUni']=cleanText(str_replace('دانشگاه‌های رد شده:', '', $r));
			}elseif (preg_match('/دانشگاه انتخاب شده:/', $r)) {
				$exp = preg_split( '/(کشور:|دانشگاه انتخاب شده:)/', $r);
				if(isset($exp[1]))$rows['apUni']=cleanText($exp[1]);
				if(isset($exp[2]))$rows['country']=cleanText($exp[2]);
			}elseif (preg_match('/ساپورت مالی در سال:/', $r)) {
				$exp = preg_split( '/(نوع فاند:|ساپورت مالی در سال:)/', $r);
				if(isset($exp[1]))$rows['fund']=cleanText($exp[1]);	
				if(isset($exp[2]))$rows['fundType']=cleanText($exp[2]);
			}elseif (preg_match('/محل دریافت روادید:/', $r)) {
				$rows['extra']=cleanText(str_replace('سایر نکات:', '', $r));
			}elseif (preg_match('/سایر نکات:/', $r)) {
				$rows['extra']=cleanText(str_replace('سایر نکات:', '', $r));
			}
		}
		$rows['id']=$num;
		$result[]=$rows;
	} catch (Exception $e) {}
}
$address="$start.json";
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