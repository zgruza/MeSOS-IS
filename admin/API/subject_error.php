<?php
$PUSH = str_replace("+"," ",$_GET["subject"]);
$subjects_py_config_load = fopen("../errors_subj.json", "r") or die("Unable to open file!");
$s_read = fread($subjects_py_config_load,filesize("../errors_subj.json"));
fclose($subjects_py_config_load);
if(strpos($s_read, '"'.$PUSH.'"') !== false){
    exit();
} else{
	$to = '","'.$PUSH.'"]';
	$what = '"]';
	$s_read = str_replace($what, $to, $s_read);
	file_put_contents("../errors_subj.json", $s_read);
}
?>