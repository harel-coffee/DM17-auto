<?php
function file_get_contents_utf8($fn) { 
	$content = file_get_contents($fn); 
	return mb_convert_encoding($content, 'UTF-8', 
		mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true)); 
}
$j=file_get_contents_utf8("DataFromForms.json");
$j=json_decode($j,true);
$indexs=['field','apDegree','bachelorsAvg','bachelorsUni','masterAvg','masterUni','engExamResult','engExamType','engExamQuan','engExamAnlt','engExamVrbl','papersGLOB','papersIRAN','year','accUni','rejUni','apUni','country','fund','fundType','extra'];
$new=[];
foreach ($j as $key => $value) {
	foreach ($indexs as $v) {
		if(!isset($value[$v]))$value[$v]="";
	}
	$new[$key]=$value;
}
$result=json_encode($new,JSON_UNESCAPED_UNICODE);
$address='Data.json';
$handle = fopen($address, "w");
fwrite($handle, $result);
fclose($handle);