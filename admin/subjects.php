<?php
include('config.php');
session_start();
$user=check_login();

$subjects_py_config_load = fopen("../subjects_config.py", "r") or die("Unable to open file!");
$s_read = fread($subjects_py_config_load,filesize("../subjects_config.py"));
$s_read = str_replace("subject_array = {", "{", $s_read);
//$s_read = str_replace("}", "]", $s_read);
//$s_read = str_replace('"', '', $s_read);
//$s_read = str_replace(': ', ':', $s_read);
//$s_read = str_replace('", "', '","', $s_read);
//$t_ex_v = explode(",", $s_read);
$t_ex_v = json_decode($s_read);
fclose($subjects_py_config_load);
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
			<div id="content">
				<div id="content-inner" bis_skin_checked="1">
				<div class="content-title" bis_skin_checked="1">
					<img src="/_stable/imgs/zapis.png" alt="">
					<h1>Databáze - Předměty</h1>
				</div>

				<div class="content-block" bis_skin_checked="1">
					
<div class="content-block-inner" bis_skin_checked="1">
	<div class="toolbar" bis_skin_checked="1">
		<a class="toolbar-button" title="Přidat učitele" href="subject_add.php">
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
					<th><a class="icon-button sorting-button"><span>Předmět</span> </a></th>
					<th><a class="icon-button sorting-button"><span>Zkratka v RV</span> </a></th>
					<th>Akce</th>
			</tr>
		</thead>

		<tbody>
			<?php
			foreach($t_ex_v as $t_ex => $val) {
				//$t_ex = explode(":", $t_ex);
			    echo "<tr><td>".$t_ex."</td><td>".str_replace(" ", "",$val)."</td>";
			    echo '<td class="table-actions">
				<a href="subject_edit.php?prop='.$t_ex.':'.str_replace(" ", "",$val).'" class="icon-button">
					<img src="imgs/upravit.png" title="Upravit učitele">
				</a>
				<a href="#" class="icon-button">
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