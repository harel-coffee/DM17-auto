<?php
function file_get_contents_utf8($fn) { 
	$content = file_get_contents($fn); 
	return mb_convert_encoding($content, 'UTF-8', 
		mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true)); 
}
$new=[];
$address='Data.json';
$jTable=file_get_contents_utf8($address);
$dec=json_decode($jTable,true);
foreach ($dec as $v) {
	$id=$v['id'];
	unset($v['id']);
	$new[$id]=$v;
}
$result=json_encode($new,JSON_UNESCAPED_UNICODE);
$handle = fopen($address, "w");
fwrite($handle, $result);
fclose($handle);