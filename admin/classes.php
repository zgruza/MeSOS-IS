<?php
include('config.php');
session_start();
$user=check_login();

if(isset($_GET['del'])){
	$msg = "";
	// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!! not finished BAKALARI missing !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	// Load data
		$CLASSES = json_decode(file_get_contents("http://127.0.0.1/classes.json"));
		$CLASSES_cnt=count($CLASSES);
		$counte_classe = -1; // FAKING Array position
		for($x=0;$x<$CLASSES_cnt;$x++){ // Getting position in list for more details
			if ($CLASSES[$x] == $_GET['del']){
				$counte_classe = $x;
				//die("counte:"." ".$counte_classe);
				//$counte_classe++;
			}
		}
		if ($counte_classe == -1){
			echo "-1";
			exit();
		}
	$CLASSES_py_config_load = fopen("../classes.json", "r") or die("Unable to open file!");
	$s_read = fread($CLASSES_py_config_load,filesize("../classes.json"));
	fclose($CLASSES_py_config_load);
	$to = '';
	$s_read = str_replace('["'.$_GET['del'].'",', '[', $s_read); // Prvni
	$s_read = str_replace(',"'.$_GET['del'].'"]', ']', $s_read); // Konec
	$s_read = str_replace(',"'.$_GET['del'].'"', $to, $s_read); // Mid
	file_put_contents("../classes.json", $s_read);


		// Got Class Index in Array now parse data for Bakalari
		$subjects_py_config_load = fopen("../bakalari_actual_week_config.py", "r") or die("Unable to open file!");
		$s_read = fread($subjects_py_config_load,filesize("../bakalari_actual_week_config.py"));
		fclose($subjects_py_config_load);
		$s_read = str_replace("classes_url = ", "", $s_read);
		$edy = json_decode($s_read);
		$edy_cnt=count($edy);
		for($x=0;$x<$edy_cnt;$x++){ // Getting position in list for more details
			if ($x == $counte_classe){
				$baka_url = $edy[$x];
			}
		}


		$subjects_py_config_load = fopen("../classes_config.py", "r") or die("Unable to open file!");
		$s_read = fread($subjects_py_config_load,filesize("../classes_config.py"));
		fclose($subjects_py_config_load);
		$to = '';
		$temp_counte = (int)$counte_classe+1;
		$what_f = '{'.$temp_counte.': "'.$_GET['del'].'",'; // First
		$what_l = ','.$temp_counte.': "'.$_GET['del'].'"}'; // Last
		$what_m = ','.$temp_counte.': "'.$_GET['del'].'"'; // Mid
		$s_read = str_replace($what_f, "{", $s_read);
		$s_read = str_replace($what_l, "}", $s_read);
		$s_read = str_replace($what_m, $to, $s_read);
		file_put_contents("../classes_config.py", $s_read);

		// Bakalari
		$subjects_py_config_load = fopen("../bakalari_actual_week_config.py", "r") or die("Unable to open file!");
		$s_read = fread($subjects_py_config_load,filesize("../bakalari_actual_week_config.py"));
		fclose($subjects_py_config_load);
		$to = '';
		$what_f = '["'.$baka_url.'", '; // First
		$what_l = ', "'.$baka_url.'"]'; // Last
		$what_m = '"'.$baka_url.'", '; // Last
		$s_read = str_replace($what_f, "[", $s_read);
		$s_read = str_replace($what_l, "]", $s_read);
		$s_read = str_replace($what_m, $to, $s_read);
		file_put_contents("../bakalari_actual_week_config.py", $s_read);

		$subjects_py_config_load = fopen("../bakalari_next_week_config.py", "r") or die("Unable to open file!");
		$s_read = fread($subjects_py_config_load,filesize("../bakalari_next_week_config.py"));
		fclose($subjects_py_config_load);
		$baka_url = str_replace("/Actual/", "/Next/", $baka_url);
		$to = '';
		$what_f = '["'.$baka_url.'", '; // First
		$what_l = ', "'.$baka_url.'"]'; // Last
		$what_m = '"'.$baka_url.'", '; // Last
		$s_read = str_replace($what_f, "[", $s_read);
		$s_read = str_replace($what_l, "]", $s_read);
		$s_read = str_replace($what_m, $to, $s_read);
		file_put_contents("../bakalari_next_week_config.py", $s_read);
	$msg.='<div class="div_warning">Úspěšně vymazáno.<br></div>';
}
$c_preread = file_get_contents("http://127.0.0.1/classes.json");
$t_ex_v = json_decode($c_preread, true);
?>
<html class=" js">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="styles.css">
	<script src="jquery.min.js" type="text/javascript"></script>
	<script src="jquery-ui.min.js" type="text/javascript"></script>
	<title>MěSOŠ - IS</title>
</head>

<body class="etk-menu-loaded">
	<div id="wrapper">
		<?php include('header.php');?>
		<div>		
			<?php
			$active_menu = "classes";
			include("sidebar.php");
			?>
			<div id="content">
				<div id="content-inner" bis_skin_checked="1">
				<div class="content-title" bis_skin_checked="1">
					<img src="/_stable/imgs/zapis.png" alt="">
					<h1>Databáze - Třídy</h1>
				</div>

				<div class="content-block" bis_skin_checked="1">
					
<div class="content-block-inner" bis_skin_checked="1">
	<?php if(isset($msg)){echo $msg;}?>
	<div class="toolbar" bis_skin_checked="1">
		<a class="toolbar-button" title="Přidat učitele" href="class_add.php">
			<img src="imgs/pridat.png" alt="">
			<span>Přidat</span>
		</a>
	</div>
</div>

<form class="datagrid" action="/_stable/ad/student/" method="post" id="frm-grid-bulkAction" novalidate="">
	<table class="table table--without-margin-top">
		<thead>
			<tr>
					<th><a class="icon-button sorting-button"><span>Třída</span> </a></th>
					<th>Akce</th>
			</tr>
		</thead>

		<tbody>
			<?php
			$index = 1;
			foreach($t_ex_v as $t_ex) {
			    echo "<tr><td>".$t_ex."</td>";
			    echo '<td class="table-actions">
				<a href="class_edit.php?prop='.$t_ex.'" class="icon-button">
					<img src="imgs/upravit.png" title="Upravit učitele">
				</a>
				<a href="classes.php?del='.$t_ex.'&in='.$index.'" class="icon-button">
						<img src="imgs/smazat.png" title="Smazat učitele">
				</a>
			</td>
			</tr>';
			$index++;
			}
			?>
	</tbody></table>
<div bis_skin_checked="1"><input type="hidden" name="do" value="grid-bulkAction-submit"><!--[if IE]><input type=IEbug disabled style="display:none"><![endif]--></div>
</form>
				</div>
			</div>
			</div>
		</div>
	</div>
	<script src="main.js"></script>
</body></html>