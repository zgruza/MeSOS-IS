<?php
$PUSH = str_replace("+"," ",$_GET["teacher"]);
$subjects_py_config_load = fopen("../errors_teach.json", "r") or die("Unable to open file!");
$s_read = fread($subjects_py_config_load,filesize("../errors_teach.json"));
fclose($subjects_py_config_load);
if(strpos($s_read, '"'.$PUSH.'"') !== false){
    exit();
} else{
	$to = '","'.$PUSH.'"]';
	$what = '"]';
	$s_read = str_replace($what, $to, $s_read);
	file_put_contents("../errors_teach.json", $s_read);
}
?>