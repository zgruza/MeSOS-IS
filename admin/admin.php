<?php
include('config.php');
session_start();
$user=check_login();
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
			$active_menu = "admin";
			include("sidebar.php");
			?>
			<div id="content">
				<div id="content-inner">
					<div class="content-title">
						<img src="images/zapis.png" alt="">
						<h1>MěSOŠ IS</h1>
					</div>
					<div class="content-block">
						<div class="content-block-inner">
							<h2>Vítejte v Administraci MěSOŠ Informačním systému</h2>
							<?php
							// Load errors
							// Subjects
							$obj = json_decode(file_get_contents("http://127.0.0.1/admin/errors_subj.json"));
							foreach($obj as $key) {
								if ($key !== "==DO NOT DELETE=="){
									echo '<div class="div_warning error">Předmět "'.$key . '" chybí! <a href="subject_add.php?subj='.$key.'">Přijte ho!</a></div>';
								}
								
							}
							// Teachers
							$obj = json_decode(file_get_contents("http://127.0.0.1/admin/errors_teach.json"));
							foreach($obj as $key) {
								if ($key !== "==DO NOT DELETE=="){
									if(strpos(substr($key, -2), 'á') !== false){
										echo '<div class="div_warning error">Učitelka "'.$key . '" chybí! <a href="teacher_add.php?teach='.$key.'">Přijte ji!</a></div>';
									} else {
										echo '<div class="div_warning error">Učitel "'.$key . '" chybí! <a href="teacher_add.php?teach='.$key.'">Přijte ho!</a></div>';
									}
								}
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="main.js"></script>
</body></html>