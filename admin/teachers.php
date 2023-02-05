<?php
include('config.php');
session_start();
$user=check_login();

if(isset($_GET['del'])){
		$msg = "";
		$del_d = explode(":", $_GET['del']);
	$subjects_py_config_load = fopen("../teachers_config.py", "r") or die("Unable to open file!");
	$s_read = fread($subjects_py_config_load,filesize("../teachers_config.py"));
	fclose($subjects_py_config_load);
	$to = '';
	$what = '"'.explode(":", $_GET['del'])[0].'": "'.explode(":", $_GET['del'])[1].'",';
	$what_before_comma = ', "'.explode(":", $_GET['del'])[0].'": "'.explode(":", $_GET['del'])[1].'"';
	$what_out_comma = '"'.explode(":", $_GET['del'])[0].'": "'.explode(":", $_GET['del'])[1].'", ';
	$s_read = str_replace($what_before_comma, $to, $s_read);
	$s_read = str_replace($what_out_comma, $to, $s_read);
	$s_read = str_replace($what, $to, $s_read); // POSLEDNI !!
	file_put_contents("../teachers_config.py", $s_read);
		$msg.='<div class="div_warning">Úspěšně vymazáno.<br></div>';
		header("refresh:2;url=teachers.php");
}

$teachers_py_config_load = fopen("../teachers_config.py", "r") or die("Unable to open file!");
$t_read = fread($teachers_py_config_load,filesize("../teachers_config.py"));
$t_read = str_replace("teachers = {", "", $t_read);
$t_read = str_replace("}", "", $t_read);
$t_read = str_replace('"', '', $t_read);
$t_read = str_replace(': ', ':', $t_read);
$t_ex_v = explode(", ", $t_read);
fclose($teachers_py_config_load);
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
			$active_menu = "teachers";
			include("sidebar.php");
			?>
			<div id="content">
				<div id="content-inner" bis_skin_checked="1">
					<?php if(isset($msg)){echo $msg;}?>
				<div class="content-title" bis_skin_checked="1">
					<img src="/_stable/imgs/zapis.png" alt="">
					<h1>Databáze - Učitelé</h1>
				</div>

				<div class="content-block" bis_skin_checked="1">
					
<div class="content-block-inner" bis_skin_checked="1">
	<div class="toolbar" bis_skin_checked="1">
		<a class="toolbar-button" title="Přidat učitele" href="teacher_add.php">
			<img src="imgs/pridat.png" alt="">
			<span>Přidat</span>
		</a>

		<a class="toolbar-button" title="Importovat učitele" href="/_stable/ad/student/import">
			<img src="imgs/import.png" alt="">
			<span>Import</span>
		</a>

		<a class="toolbar-button" title="Exportovat učitele" href="/_stable/ad/student/export">
			<img src="imgs/export.png" alt="">
			<span>Export</span>
		</a>
	</div>
</div>

<form class="datagrid" action="/_stable/ad/student/" method="post" id="frm-grid-bulkAction" novalidate="">
	<table class="table table--without-margin-top">
		<thead>
			<tr>
					<th><a class="icon-button sorting-button"><span>Jméno a příjmení</span> </a></th>
					<th><a class="icon-button sorting-button"><span>Zkratka v RV</span> </a></th>
					<th>Akce</th>
			</tr>
		</thead>

		<tbody>
			<?php
			foreach($t_ex_v as $t_ex) {
				$t_ex = explode(":", $t_ex);
			    echo "<tr><td>".$t_ex[0]."</td><td>".str_replace(" ", "",$t_ex[1])."</td>";
			    echo '<td class="table-actions">
				<a href="teacher_edit.php?prop='.$t_ex[0].':'.str_replace(" ", "",$t_ex[1]).'" class="icon-button">
					<img src="imgs/upravit.png" title="Upravit učitele">
				</a>
				<a href="teachers.php?del='.$t_ex[0].':'.str_replace(" ", "",$t_ex[1]).'" class="icon-button">
						<img src="imgs/smazat.png" title="Smazat učitele">
				</a>
			</td>
			</tr>';
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