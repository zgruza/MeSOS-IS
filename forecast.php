<?php
date_default_timezone_set('Europe/Prague');
$days = array("Sunday"=>"Neděle","Monday"=>"Pondělí","Tuesday"=>"Úterý","Wednesday"=>"Středa","Thursday"=>"Čtvrtek","Friday"=>"Pátek","Saturday"=>"Sobota");
$months = array("Jan"=>"leden", "Feb"=>"únor", "Mar"=>"březen", "Apr"=>"duben", "May"=>"květen", "Jun"=>"červen","Jul"=>"červenec", "Aug"=>"srpen", "Sep"=>"září", "Oct"=>"říjen", "Nov"=>"listopad", "Dec"=>"prosinec");
$json = json_decode(file_get_contents("http://127.0.0.1/forecast.json"), true)['forecast']['forecastday'];
function IconPass($item_no){
	global $json;
	$item_no = (int)$item_no;
	if($json[$item_no]['day']['daily_will_it_snow'] == "1" || $json[$item_no]['day']['daily_chance_of_snow'] <> "0"){
		return "<div class=\"snowy\">";
	}
	if($json[$item_no]['day']['daily_will_it_rain'] == "1" || $json[$item_no]['day']['daily_chance_of_rain'] <> "0"){
		return "<div class=\"rainy\">";
	}
	if (strpos((string)$json[$item_no]['day']['condition']['text'], 'Zataženo') !== false || strpos((string)$json[$item_no]['day']['condition']['text'], 'zataženo') !== false) { 
		return "<div class=\"cloudy\">";
	}
	if (strpos((string)$json[$item_no]['day']['condition']['text'], 'Slunečno') !== false || strpos((string)$json[$item_no]['day']['condition']['text'], 'slunečno') !== false) { 
		return '<div class="sun"><img class="sunny" src=\'/assets/clear-day.svg\'/>';
	}
	if (strpos((string)$json[$item_no]['day']['condition']['text'], 'Částečně oblačno') !== false) { 
		return '<div class="sun"><img class="partly_cloudy" src=\'/assets/clear-day.svg\'/><div class="partly_cloud"></div>';
	}
	if (strpos((string)$json[$item_no]['day']['condition']['text'], 'Oblačno') !== false || strpos((string)$json[$item_no]['day']['condition']['text'], 'oblačno') !== false) { 
		return "<div class=\"cloudy\">";
	}
	if (strpos((string)$json[$item_no]['day']['condition']['text'], 'Jasno') !== false || strpos((string)$json[$item_no]['day']['condition']['text'], 'jasno') !== false) { 
		return '<div class="sun"><img class="sunny" src=\'/assets/clear-day.svg\'/>';
	}
}
function BigIconPassHourly(){
	global $json;
	$currentHour = date('G');
	if($json[0]['hour'][$currentHour]['will_it_snow'] == "1" || $json[0]['hour'][$currentHour]['chance_of_snow'] <> "0"){
		return "<div class=\"snowy_main\">";
	}
	if($json[0]['hour'][$currentHour]['will_it_rain'] == "1" || $json[0]['hour'][$currentHour]['chance_of_rain'] <> "0"){
		return "<div class=\"rainy_main\">";
	}
	if (strpos((string)$json[0]['hour'][$currentHour]['condition']['text'], 'Zataženo') !== false || strpos((string)$json[0]['hour'][$currentHour]['condition']['text'], 'zataženo') !== false) { 
		return "<div class=\"cloudy_main\">";
	}
	if (strpos((string)$json[0]['hour'][$currentHour]['condition']['text'], 'Slunečno') !== false || strpos((string)$json[0]['hour'][$currentHour]['condition']['text'], 'slunečno') !== false) { 
		return '<div class="sun"><img class="sunny_main" src=\'/assets/clear-day.svg\'/>';
	}
	if (strpos((string)$json[0]['hour'][$currentHour]['condition']['text'], 'Částečně oblačno') !== false) { 
		return '<div class="sun"><img class="partly_cloudy_main" src=\'/assets/clear-day.svg\'/><div class="partly_cloud_main"></div>';
	}
	if (strpos((string)$json[0]['hour'][$currentHour]['condition']['text'], 'Oblačno') !== false || strpos((string)$json[0]['hour'][$currentHour]['condition']['text'], 'oblačno') !== false) { 
		return "<div class=\"cloudy_main\">";
	}
	if (strpos((string)$json[0]['hour'][$currentHour]['condition']['text'], 'Jasno') !== false || strpos((string)$json[0]['hour'][$currentHour]['condition']['text'], 'jasno') !== false) { 
		return '<div class="sun"><img class="sunny_main" src=\'/assets/clear-day.svg\'/>';
	}
}
$h = date('G');
?>
<!-- Current Weather -->
	<div id="current" class="wrapper_w">
		<h1 class="location_w">Klobouky u Brna</h1>
		<h2 class="date" id="cur_d"><?=$days[date('l')].", ".date('j').". ".$months[date('M')];?></h2>
		<div class="weatherIcon" id="main_icon">
			<?=BigIconPassHourly();?>
			</div>
		</div>
		<p class="temp" id="temp"><?=$json[0]['hour'][$h]['temp_c'];?></p>
		<p class="conditions" id="conditions"><?=$json[0]['hour'][$h]['condition']['text'];?></p>
	</div>
<!-- Future Forecast -->
	<div id="future" class="wrapper_w">
		<div class="container_w">
			<h3 class="day">Zítra</h3>
			<div class="weatherIcon">
				<?=IconPass(1);?>
					<div class="inner"></div>
				</div>
			</div>
			<p class="conditions"><?=$json[1]['day']['condition']['text'];?></p>
			<p class="tempRange"><span class="high"><?=$json[1]['day']['avgtemp_c'];?></span></p>
		</div>
		<div class="container_w">
			<h3 class="day"><?=$days[date('l', strtotime("+2 day"))];?></h3>
			<div class="weatherIcon">
				<?=IconPass(2);?>
					<div class="inner"></div>
				</div>
			</div>
			<p class="conditions"><?=$json[2]['day']['condition']['text'];?></p>
			<p class="tempRange"><span class="high"><?=$json[2]['day']['avgtemp_c'];?></span></p>
		</div>
		<div class="container_w">
			<h3 class="day"><?=$days[date('l', strtotime("+3 day"))];?></h3>
			<div class="weatherIcon">
				<?=IconPass(3);?>
					<div class="inner"></div>
				</div>
			</div>
			<p class="conditions"><?=$json[3]['day']['condition']['text'];?></p>
			<p class="tempRange"><span class="high"><?=$json[3]['day']['avgtemp_c'];?></span></p>
		</div>
	</div>
	<footer><p id="lastUpdated_w">Aktualizováno <?=date('H').":".date('i');?></p></footer>
