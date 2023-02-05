<?php
include('config.php');
session_start();
$user=check_login();


if($_SERVER['REQUEST_METHOD']==='POST'){
	if(isset($_POST['reload_infosys'])){
		$msg='<div class="div_warning">Infosys data budou aktualizována. (~3 minuty)<br></div>';
	}
	if(isset($_POST['restart'])){
		$msg='<div class="div_warning">Infosys bude restartován. (~1 minuta)<br></div>';
	}
	//if(isset($_POST['reload_touchscr'])){
	//	$msg='<div class="div_warning">Touchscreen data budou aktualizována. (~3 minuta)<br></div>';
	//}
	//if(isset($_POST['restart_touchscr'])){
	//	$msg='<div class="div_warning">Touchscreen bude restartován. (~1 minuta)<br></div>';
	//}
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
			$active_menu = "system";
			include("sidebar.php");
			?>
		<div id="content" bis_skin_checked="1">
					<div id="content-inner" bis_skin_checked="1">
						<?php if(isset($msg)){echo $msg;}?>
						<div class="content-title" bis_skin_checked="1">
							<img src="imgs/zapis.png" alt="">
							<h1>Systém » Systém</h1>
						</div>

						<div class="content-block" bis_skin_checked="1">
							
		<div class="content-block-inner" bis_skin_checked="1">
			<div class="form form-normal" bis_skin_checked="1">
		

		<table class="wide-table">
		<tbody>
			<tr>
				<th class="form--w15"><label for="frm-form-form-text"></label></th>
				<td><form method="post" id="frm-form" novalidate=""><input type="submit" name="restart" class="button-success button" id="frm-save" value="Restartovat"></form></td>
			</tr>
		<tr>
			<th class="form--w15"><label for="frm-form-form-text">Info sys.:</label></th>
			<td class="form--w85">
				<form method="post" id="frm-form" novalidate=""><input type="submit" name="reload_infosys" class="button-success button" id="frm-save" value="Aktualizovat všechna data"></form>
			</td>
		</tr>
		<tr>
			<th class="form--w15"><label for="frm-form-form-text">Touchscreen.:</label></th>
			<td class="form--w85">
				<form method="post" id="frm-form" novalidate=""><input type="submit" name="reload_touchscr" class="button-success button" id="frm-save" value="Aktualizovat všechna data"></form>
			</td>
		</tr>
		</tbody></table>

		<div bis_skin_checked="1"><input type="hidden" name="do" value="form-submit"></div>
			</div>
		</div>
						</div>
					</div>
				</div>
		</div>
	</div>
	<script src="main.js"></script>
</body></html>)
<?php
if($_SERVER['REQUEST_METHOD']==='POST'){if(isset($_POST['restart'])){shell_exec("sudo reboot");}}
?>