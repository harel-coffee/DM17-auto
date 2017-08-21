<?php
function file_get_contents_utf8($fn) { 
	$content = file_get_contents($fn); 
	return mb_convert_encoding($content, 'UTF-8', 
		mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true)); 
}
$merged=[];
foreach (range(0, 4300,500) as $name) {
	$jTable=file_get_contents_utf8("DataFromForms/$name.json");
	$merged=array_merge($merged,json_decode($jTable,true));
}
$result=json_encode($merged,JSON_UNESCAPED_UNICODE);
$address='DataFromForms.json';
$handle = fopen($address, "w");
fwrite($handle, $result);
fclose($handle);