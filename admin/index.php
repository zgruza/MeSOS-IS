<?php
include("config.php");
header('Content-Type: text/html; charset=UTF-8');
session_start();
//$user = check_login();

$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
	$ok = true;
		if(!isset($_POST['login']) || empty($_POST['login'])){
			$msg.='<h2 style="color:red;">Špatné heslo</h2>'; // EMPTY USERNAME
			$ok=false;
		}else{
			if ($_POST['login']==$admin_user && $_POST['password']==$admin_passwd){
				$_SESSION['username']=$admin_user;
				session_write_close();
				header('Location: admin.php');
				exit;
			}else{
				$msg.='<h2 style="color:red;">Špatné heslo</h2>'; // EMPTY USERNAME
				$ok=false;
			}
		}
	}
?>
<html class=" js">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="styles.css">
	<script src="jquery.min.js" type="text/javascript"></script>
	<script src="jquery-ui.min.js" type="text/javascript"></script>
	<title>MěSOŠ - IS</title>
</head>
<body class="etk-page-login">
	<script>document.documentElement.className+=' js'</script>
	<div id="wrapper">
		<div id="header">
			<div class="header-block header-logo" id="etk-logo">
				<a><span class="header-block-helper"></span><img src="../img/logo.png" width="300" height="72"></a>
			</div>
		</div>

		<div>
			<div id="content">
				<div id="content-inner">
					<div class="content-title">
						<h1>Admin - Přihlášení</h1>
					</div>
					<div class="content-block">
						<div class="content-block-inner">
							<?php 
								if ($msg !== ''){
									echo $msg;
								}
							?>
	<div>
		<div>
			<form action="" method="POST" class="form">
				<table>
				<tr><th>Škola:</th>
					<td><select name="school"><option value="34">Městská střední odborná škola, příspěvková organizace, Klobouky u Brna</option></select></td>
				</tr>
				<tr><th>Login:</th>
					<td><input type="text" autofocus name="login"  /></td>
				</tr>
				<tr>
					<th>Heslo:</th>
					<td><input type="password" name="password" /></td>
				</tr>
				<tr>
					<td><input type="hidden" name="submit_login" value="1" /></td>
					<td class="form-buttons">
						<input type="submit" name="" value="Přihlásit" class="button-success" />
					</td>
				</tr>
				</table>
			</form>

			<div id="footer">
				<div id="footer-inner">
					<p class="footer-muted">
						Developed by <a href="">Robotika Gang</a>,
						MěSOŠ Informační systém <span><?=$__VERSION__;?></span>.
					</p>
				</div>
			</div>
		</div>
	</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
