<?php
include('config.php');
session_start();
$user=check_login();

if($_SERVER['REQUEST_METHOD']==='POST'){
	$class = $_POST['tr'];

	// INDEX PATH!!
		$CLASSES = json_decode(file_get_contents("http://127.0.0.1/classes.json"));
		$CLASSES_cnt=count($CLASSES);
		$counte_classe = -1; // FAKING Array position
		for($x=1;$x<$CLASSES_cnt;$x++){ // Getting position in list for more details
			$y = $x + 1;
			if (isset($CLASSES[$y])){
				// PASS
				//$counte_classe = $x;
				//$counte_classe++;
			} else {
				$counte_classe = $y + 1; // LAST FUCKIN' INDEX
				//die("LAST NEW FUCKIN INDEX IS ".$y + 1);
			}
		}
		if ($counte_classe == -1){
			echo "-1";
			exit();
		}
	$subjects_py_config_load = fopen("../classes.json", "r") or die("Unable to open file!");
	$s_read = fread($subjects_py_config_load,filesize("../classes.json"));
	fclose($subjects_py_config_load);
	$to = '","'.$class.'"]';
	$what = '"]';
	$s_read = str_replace($what, $to, $s_read);
	file_put_contents("../classes.json", $s_read);

	$subjects_py_config_load = fopen("../classes_config.py", "r") or die("Unable to open file!");
	$s_read = fread($subjects_py_config_load,filesize("../classes_config.py"));
	fclose($subjects_py_config_load);
	$to = '",'.$counte_classe.': "'.$class.'"}';
	$what = '"}';
	$s_read = str_replace($what, $to, $s_read);
	file_put_contents("../classes_config.py", $s_read);

	// Bakaláři
	$subjects_py_config_load = fopen("../bakalari_actual_week_config.py", "r") or die("Unable to open file!");
	$s_read = fread($subjects_py_config_load,filesize("../bakalari_actual_week_config.py"));
	fclose($subjects_py_config_load);
	$to = '", "'.$_POST['baka'].'"]';
	$what = '"]';
	$s_read = str_replace($what, $to, $s_read);
	file_put_contents("../bakalari_actual_week_config.py", $s_read);

	$subjects_py_config_load = fopen("../bakalari_next_week_config.py", "r") or die("Unable to open file!");
	$s_read = fread($subjects_py_config_load,filesize("../bakalari_next_week_config.py"));
	fclose($subjects_py_config_load);
	$n_baka = str_replace("/Actual/", "/Next/", $_POST['baka']);
	$to = '", "'.$n_baka.'"]';
	$what = '"]';
	$s_read = str_replace($what, $to, $s_read);
	file_put_contents("../bakalari_next_week_config.py", $s_read);

	$msg='<div class="div_warning">Úspěšně uloženo.<br></div>';
	header("refresh:2;url=classes.php");
}
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
		<div id="content" bis_skin_checked="1">
					<div id="content-inner" bis_skin_checked="1">
						<?php if(isset($msg)){echo $msg;}?>
						<div class="content-title" bis_skin_checked="1">
							<img src="imgs/zapis.png" alt="">
							<h1>Třídy » Přidat třídu</h1>
						</div>

						<div class="content-block" bis_skin_checked="1">
							
		<div class="content-block-inner" bis_skin_checked="1">
			<div class="form form-normal" bis_skin_checked="1">
		<form method="post" id="frm-form" novalidate="">

		<table>
		<tbody>
		<tr>
			<th><label>Třída</label></th>

			<td><input type="text" name="tr" class="input--text text" id="frm-pr" value="<?php if(isset($class)){$class;}?>"></td>
		</tr>

		<tr>
			<th><label>Bakaláři</label><br><small><font color="red">*</font>Odkaz ve stylu:<br><a>https://sosklobouky.bakalari.cz/Timetable/<br>Public/Actual/Class/R8</a></small></th>
			<td><input type="text" name="baka" class="input--text text" id="frm-pr" value="<?php if(isset($_POST['baka'])){$_POST['baka'];}?>"></td>

		</tr>

		<tr>
			<th></th>

			<td><input type="submit" name="save" class="button-success button" id="frm-save" value="Uložit"></td>
		</tr>
		</tbody></table>

		<div bis_skin_checked="1"><input type="hidden" name="do" value="form-submit"></div>
		</form>
			</div>
		</div>
						</div>
					</div>
				</div>
		</div>
	</div>
	<script src="main.js"></script>
</body></html>