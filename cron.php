<?php
// crontab -e -> * * * * * cd /var/www/html && /usr/bin/php cron.php (Every 1 min)
// Timetable
//shell_exec("rm rozvrh.json");
//shell_exec("wget \"http://soubory.sosklobouky.cz/zaci.pdf\" -O rozvrh.pdf");
//shell_exec("rm rozvrh.jpg");
//shell_exec("convert -density 300 rozvrh.pdf rozvrh.jpg");
	// Convert JSON
//	shell_exec("python3.8 py.py");
date_default_timezone_set('Europe/Prague');
$time_str = (string)date("h:i");
$currentTime = strtotime($time_str);
// Cleanup Updates
if ((int)$currentTime >= (int)strtotime('01:00') && (int)$currentTime <= (int)strtotime('02:00')) {
	shell_exec("rm update_*");
}
// Weather update
if ((int)$currentTime >= (int)strtotime('07:00') && (int)$currentTime <= (int)strtotime('09:00')) { // Pokud je čas mezi 7:00 - 9:00 
	$get_weather_data = file_get_contents("http://api.weatherapi.com/v1/forecast.json?key=d32dd77bf071446996992056230402&q=48.99530630,16.85788900&days=5&aqi=no&alerts=yes&lang=cs");
	file_put_contents("forecast.json", $get_weather_data);
}
// Lunch
if ((int)$currentTime >= (int)strtotime('07:00') && (int)$currentTime <= (int)strtotime('09:00')) { // Pokud je čas mezi 7:00 - 9:00 execute [Bereme v uvahu ze cron bezi kazdou hodinu]
		shell_exec("wget \"http://soubory.sosklobouky.cz/jidelnicek.pdf\" -O jidelnicek.pdf");
		shell_exec("rm jidelnicek-0.jpg");
		shell_exec("rm jidelnicek-1.jpg");
		shell_exec("convert -density 300 jidelnicek.pdf jidelnicek.jpg");
		shell_exec("rm jidelnicek.pdf");
}
unset($time_str);
unset($currentTime);
// Timetable
shell_exec("python roz.py");
