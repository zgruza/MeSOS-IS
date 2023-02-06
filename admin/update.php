<?php
include('config.php');
session_start();
$user=check_login();


if($_SERVER['REQUEST_METHOD']==='POST'){
	if(isset($_POST['check_update'])){
		$NEWEST = file_get_contents("https://raw.githubusercontent.com/zgruza/MeSOS-IS/main/.VERSION?".time());
		$changelog = file_get_contents("https://raw.githubusercontent.com/zgruza/MeSOS-IS/main/.changelog?".time());
		$NEWEST = (float)$NEWEST;
		$INSTALLED = (float)$__VERSION__;
		if ($NEWEST > $INSTALLED){
			$inj = '
			<form method="post" id="frm-form" novalidate="">
				<p style="color:red;">Systém se po instalaci sám restartuje!</p>
				<input type="submit" name="install_update" class="button-success button" id="frm-save" value="Aktualizovat">
			</form>';
			$inj_cl = '
			<textarea name="changelog" id="changelog" style="min-height: 20em" readonly="">'.$changelog.'</textarea>';
			$msg='<div class="div_warning">Je k dispozici aktualizace systému na verzi '.$NEWEST.' (Aktuální: '.$INSTALLED.')<br></div>';
		} else {
			$inj = '<form method="post" id="frm-form" novalidate=""><input type="submit" name="check_update" class="button-success button" id="frm-save" value="Zkontrolovat znovu"></form>';
			$msg='<div class="div_warning">Používáš nejnovější verzi '.$INSTALLED.'<br></div>';
		}
	}

	if(isset($_POST['install_update'])){
		$NEWEST = file_get_contents("https://raw.githubusercontent.com/zgruza/MeSOS-IS/main/.VERSION?".time());
		$NEWEST = (float)$NEWEST;
		shell_exec("sudo wget -P /var/www/ https://github.com/zgruza/MeSOS-IS/raw/main/update_".$NEWEST.".zip");
		shell_exec("sudo unzip -o /var/www/update_".$NEWEST.".zip -d /var/www/");
		shell_exec("sudo rm /var/www/update_".$NEWEST.".zip");
		shell_exec("sudo chmod +x /var/www/html/update_".$NEWEST.".sh");
		shell_exec("sudo /var/www/html/update_".$NEWEST.".sh");
		exit();
	}

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
			$active_menu = "update";
			include("sidebar.php");
			?>
		<div id="content" bis_skin_checked="1">
					<div id="content-inner" bis_skin_checked="1">
						<?php if(isset($msg)){echo $msg;}?>
						<div class="content-title" bis_skin_checked="1">
							<img src="imgs/zapis.png" alt="">
							<h1>Systém » Update</h1>
						</div>

						<div class="content-block" bis_skin_checked="1">
							
		<div class="content-block-inner" bis_skin_checked="1">
			<div class="form form-normal" bis_skin_checked="1">
		

		<table class="wide-table">
		<tbody>
		<tr>
			<th class="form--w15"><label for="frm-form-form-text">Aktualizace:</label></th>
			<td class="form--w85">
				<?php if(isset($_POST['check_update'])){ echo $inj; } else {?>
					<form method="post" id="frm-form" novalidate=""><input type="submit" name="check_update" class="button-success button" id="frm-save" value="Zkontrolovat"></form>
				<?php } ?>
			</td>
		</tr>
		</tbody></table>
		<?php if(isset($_POST['check_update'])){ echo $inj_cl; }?>
		<div bis_skin_checked="1"><input type="hidden" name="do" value="form-submit"></div>
			</div>
		</div>
						</div>
					</div>
				</div>
		</div>
	</div>
	<script src="main.js"></script>
</body></html>
