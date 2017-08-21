<?php
function file_get_contents_utf8($fn) { 
	$content = file_get_contents($fn); 
	return mb_convert_encoding($content, 'UTF-8', 
		mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true)); 
}
/////////////////////
$jForm=file_get_contents_utf8("DataFromForms.json");
$jTable=file_get_contents_utf8("DataFromTable.json");
$jTable=json_decode($jTable,true);
$jForm=json_decode($jForm,true);
$new=[];
echo "<pre>";
foreach ($jForm as $k=>$v) {
	unset($jTable[$k]);
}
print_r($jTable);