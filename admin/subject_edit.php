<?php
include('config.php');
session_start();
$user=check_login();

if(isset($_GET['prop'])){
	$predmet = explode(":", $_GET['prop'])[0];
	$zkratka = explode(":", $_GET['prop'])[1];
} else {
	exit();
}

if($_SERVER['REQUEST_METHOD']==='POST'){
	$predmet = $_POST['pr'];
	$zkratka = $_POST['rv'];
	$subjects_py_config_load = fopen("../subjects_config.py", "r") or die("Unable to open file!");
	$s_read = fread($subjects_py_config_load,filesize("../subjects_config.py"));
	fclose($subjects_py_config_load);
	$to = '"'.$predmet.'": "'.$zkratka.'",';
	$what = '"'.explode(":", $_GET['prop'])[0].'": "'.explode(":", $_GET['prop'])[1].'",';
	$s_read = str_replace($what, $to, $s_read);
	file_put_contents("../subjects_config.py", $s_read);
	$msg='<div class="div_warning">Úspěšně uloženo.<br></div>';
	header("refresh:2;url=subjects.php");
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
			$active_menu = "subjects";
			include("sidebar.php");
			?>
		<div id="content" bis_skin_checked="1">
					<div id="content-inner" bis_skin_checked="1">
						<?php if(isset($msg)){echo $msg;}?>
						<div class="content-title" bis_skin_checked="1">
							<img src="imgs/zapis.png" alt="">
							<h1>Předměty » Upravit předmět</h1>
						</div>

						<div class="content-block" bis_skin_checked="1">
							
		<div class="content-block-inner" bis_skin_checked="1">
			<div class="form form-normal" bis_skin_checked="1">
		<form method="post" id="frm-form" novalidate="">

		<table>
		<tbody>
		<tr>
			<th><label>Předmět</label></th>

			<td><input type="text" name="pr" class="input--text text" id="frm-pr" value="<?=$predmet;?>"></td>
		</tr>

		<tr>
			<th><label>Zkratka</label></th>

			<td><input type="text" name="rv" class="input--text text" id="frm-rv" value="<?=$zkratka;?>"></td>
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