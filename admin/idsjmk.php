<?php
include('config.php');
session_start();
$user=check_login();

$bus = fopen("../bus_include.php", "r");
 while(!feof($bus)) {
     $line = fgets($bus);
     if (str_contains($line, 'cookie:')) { 
		 $cookie_data = substr($line, 0, -3);
		 break;
	}
}
if($_SERVER['REQUEST_METHOD']==='POST'){
// ========== GET FAKER COOKIE DATA ==========
$faker = fopen("../api_faker.php", "r");
 while(!feof($faker)) {
     $line = fgets($faker);
     if (str_contains($line, 'cookie:')) { 
		 $faker = substr($line, 0, -3);
		 break;
	}
}
// ===========================================
	$cookie = $_POST['cookie'];
	$bus_config_load = fopen("../bus_include.php", "r") or die("Unable to open file!");
	$s_read = fread($bus_config_load,filesize("../bus_include.php"));
	fclose($bus_config_load);
	$to = $_POST['cookie'];
	$what = $cookie_data;
	$s_read = str_replace($what, $to, $s_read);
	file_put_contents("../bus_include.php", $s_read);

	// API FAKER
	$fak_config_load = fopen("../api_faker.php", "r") or die("Unable to open file!");
	$s_read = fread($fak_config_load,filesize("../api_faker.php"));
	fclose($fak_config_load);
	$to = $_POST['cookie'];
	$what = $faker;
	$s_read = str_replace($what, $to, $s_read);
	file_put_contents("../api_faker.php", $s_read);

	$msg='<div class="div_warning">Úspěšně uloženo.<br></div>';
	header("refresh:2;url=idsjmk.php");
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
			$active_menu = "idsjmk";
			include("sidebar.php");
			?>
		<div id="content" bis_skin_checked="1">
					<div id="content-inner" bis_skin_checked="1">
						<?php if(isset($msg)){echo $msg;}?>
						<div class="content-title" bis_skin_checked="1">
							<img src="imgs/zapis.png" alt="">
							<h1>Autobusy » IDSJMK</h1>
						</div>

						<div class="content-block" bis_skin_checked="1">
							
		<div class="content-block-inner" bis_skin_checked="1">
			<div class="form form-normal" bis_skin_checked="1">
		<form method="post" id="frm-form" novalidate="">

		<table class="wide-table">
		<tbody>
		<tr>
			<th class="form--w15"><label for="frm-form-form-text">Cookie:</label></th>

			<td class="form--w85"><textarea name="cookie" id="cookie" style="min-height: 20em" id="frm-form-form-text" ><?php if(isset($_POST['cookie'])){echo $_POST['cookie'];}else{echo $cookie_data;}?></textarea></td>
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