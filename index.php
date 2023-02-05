<?php 
//shell_exec("sudo php cron.php"); 
//sleep(10); 
shell_exec("sudo chmod 777 classes_config.py");
shell_exec("sudo chmod 777 classes.json");
shell_exec("sudo chmod 777 teachers_config.py");
shell_exec("sudo chmod 777 subjects_config.py");
shell_exec("sudo chmod 777 bakalari_actual_week_config.py");
shell_exec("sudo chmod 777 bakalari_next_week_config.py");
shell_exec("sudo chmod 777 forecast.json");
?>
<html>
<head><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css"><script src="./assets/jquery-3.6.3.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"></head>
<body onload="BootUp()" onmousemove="mouseMove(event)" id="element" style="cursor: none;margin: 0px;height: 100%;">
	<div id="app">
		<nav class="navigation" style="padding-left: 96%;padding-top: 15%;z-index: 9999999">
		  <ul class="mainmenu">
		  	<!--<li id="fs" style="margin-bottom: 0.3rem;"><a id="Event__Clicker" name="Event__Clicker" href="#" onclick='GoFullScreen();' class="btn btn-warning"><img src="/img/full_screen.png?2" width="24" height="24" /></a></li>-->
		    <li style="margin-bottom: 0.3rem;"><a href="#" onclick="openPage('rozvrh');" id="rozvrh_btn" class="btn btn-warning"><img src="/img/calendar.png?3" width="24" height="24" /></a></li>
		    <li style="margin-bottom: 0.3rem;"><a href="#" onclick="openPage('jidelnicek');" id="lunch_btn" class="btn btn-warning"><img src="/img/lunch.png?3" width="24" height="24" /></a></li>
		    <li style="margin-bottom: 0.3rem;"><a href="#" onclick="openPage('busy');" id="busy_btn" class="btn btn-warning"><img src="/img/bus.png?3" width="24" height="24" /></a></li>
		    <li style="margin-bottom: 0.3rem;"><a href="#" onclick="openPage('weather');" id="weather_btn" class="btn btn-warning"><img src="/img/bus.png?3" width="24" height="24" /></a></li>
		  </ul>
		</nav>

		<!-- BUS -->
		<div id="busy" style="display:none">
			<?php include('bus_include.php'); ?>
		</div>

		<!-- ROZVRH -->
		<div class="ima_image" id="rozvrh" style="display:block">
			<div class="header_top"><div class="header"><img src="/img/logo.png" style="padding-top: 1%;float: left;" /><div id="headertime1">Načítám..</div></div>
			<!--<div id="hugeCountdown" style="display: none;">%always_hidden%</div>-->
				<!--<img class="centerblock" id="rozvrhid" src="rozvrh.jpg?<?=time();?>" /> -->
					<div id="rozvrh_content" class="rozvrh_content"><?php include("rozvrh_template.php");?></div>
			</div>
		</div>

		<!-- LUNCH class="ima_image"-->
		<div id="jidelnicek" style="display:none">
			<div class="header_top"><div class="header"><img src="/img/logo.png" style="padding-top: 1%;float: left;" /><div id="headertime2">Načítám..</div></div>
				<div class="jidelna_group">
					<div class="jid_sep">
						<img class="centerblock_jidelna" id="jidelnicekid0" src="jidelnicek-0.jpg?<?=time();?>" style="border: 1px solid;"/>
					</div>
					<div class="jid_sep">
						<img class="centerblock_jidelna" id="jidelnicekid1" src="jidelnicek-1.jpg?<?=time();?>" style="border: 1px solid;" />
					</div>
				</div>
			</div>
		</div>

		<div id="weather" style="display:none">
			<div class="header_top"><div class="header"><img src="/img/logo.png" style="padding-top: 1%;float: left;" /><div id="headertime3">Načítám..</div></div>
				<div>
					<div id="status"><p></p><button class="close"><i class="fa fa-times" aria-hidden="true"></i></button></div>
					<div class="wea_con" id="wea_con" style="background:#86B9E0;"></div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
<script>
	var globalY = 0; // For Mouse Move Y position
	var globalX = 0; // For Mouse Move X position
	var AutoModeTimer;
	var AutoMode_seconds = 00;
	var AutoMode_minutes = 1; // Waiting 1min/2 to start switch pages automatically.
	document.addEventListener('contextmenu', event => event.preventDefault());
	//document.addEventListener('keydown', function (e) {if (e.keyCode == 122){if (document.getElementById('fs').style.display == "none"){document.getElementById('fs').style.display = "block";document.getElementById('element').style.cursor = "default";} else {document.getElementById('fs').style.display = "none";}} else if(e.keyCode == 27){document.getElementById('fs').style.display = "block";document.getElementById('element').style.cursor = "default"; }}, false); // F11 Press
	var globalpage = 0; // 0 - timetable; 1 - bus; 2 - lunch

	// ------------------------------ AUTO MODE -------------------------------
	function AutoModeTimerFunction(){
		clearInterval(AutoModeTimer);
		AutoModeTimer = setInterval(function(){
			//console.log("timer is running brah...");
			var timer = (AutoMode_minutes + ":" + AutoMode_seconds).split(":");
			var minutes = timer[0];
			var seconds = timer[1];
			seconds -= 1;
			if (minutes < 0) {return;}
			else if (seconds < 0 && minutes != 0) {minutes -= 1;seconds = 59;}
			else if (seconds < 10 && length.seconds != 2) {seconds = '0' + seconds;}
			AutoMode_minutes = minutes;
			AutoMode_seconds = seconds;
			//document.getElementById('hugeCountdown').innerHTML = minutes + ':' + seconds;
			if (minutes == 0 && seconds == 0) { _autoSwitch(); }
		}, 500);
	}
	function _autoSwitch(){
		AutoMode_minutes = 0;
		AutoMode_seconds = 30;
		switchPage();
	}
	// ------------------------------------------------------------------------
	// ------------------------------ BACKGROUND ------------------------------
	// ------------------------------------------------------------------------
	const update_rozvrh = setInterval(function() {httpGetRozvrh();}, 50000); // Every 50 secs.
	const update_jidelnicek = setInterval(function() {update_jidelnicekf();}, 50000); // Every 50 secs.
	const update_weather = setInterval(function() {weatherUpdate();}, 50000); // Every 50 secs.
	//function update_rozvrhf() {setTimeout(() => {httpGetRozvrh();}, 100);}
	//function httpGetRozvrh(){xmlhttp=new XMLHttpRequest();xmlhttp.onreadystatechange=function(){if (xmlhttp.readyState==4 && xmlhttp.status==200){document.getElementById('rozvrh_content').innerHTML = xmlhttp.responseText;}}xmlhttp.open("GET", "http://192.168.1.101/rozvrh_template.php", false);xmlhttp.send();}
	function url_content(url){return $.get(url);}
	function httpGetRozvrh(){url_content(window.location.origin+"/rozvrh_template.php").then(function(data){ document.getElementById('rozvrh_content').innerHTML = data;});}
	function update_jidelnicekf() {document.getElementById("jidelnicekid0").src = "jidelnicek-0.jpg?" + Date.now();document.getElementById("jidelnicekid1").src = "jidelnicek-1.jpg?" + Date.now();}
	function weatherUpdate() {url_content(window.location.origin+"/forecast.php").then(function(data){ document.getElementById('wea_con').innerHTML = data;});}
	// ------------------------------------------------------------------------
function openPage(page_id){
	//document.getElementById(page_id).style.display = "block";
	if(page_id==='rozvrh'){
		document.getElementById("busy_btn").className = "btn btn-warning";
		document.getElementById("lunch_btn").className = "btn btn-warning";
		document.getElementById("weather_btn").className = "btn btn-warning";
		document.getElementById("rozvrh_btn").className = "btn btn-warning active";
		HideAll();
		document.getElementById(page_id).style.display = "block";
	} else if(page_id==='busy'){
		HideAll();
		document.getElementById(page_id).style.display = "block";
		document.getElementById("rozvrh_btn").className = "btn btn-warning";
		document.getElementById("lunch_btn").className = "btn btn-warning";
		document.getElementById("weather_btn").className = "btn btn-warning";
		document.getElementById("busy_btn").className = "btn btn-warning active";
		fontSize(); // Try Resize font to Fit screen size
	} else if(page_id==='jidelnicek'){
		document.getElementById("rozvrh_btn").className = "btn btn-warning";
		document.getElementById("busy_btn").className = "btn btn-warning";
		document.getElementById("weather_btn").className = "btn btn-warning";
		document.getElementById("lunch_btn").className = "btn btn-warning active";
		HideAll();
		document.getElementById(page_id).style.display = "block";
	} else if(page_id==='weather'){
		document.getElementById("rozvrh_btn").className = "btn btn-warning";
		document.getElementById("busy_btn").className = "btn btn-warning";
		document.getElementById("lunch_btn").className = "btn btn-warning";
		document.getElementById("weather_btn").className = "btn btn-warning active";
		HideAll();
		document.getElementById(page_id).style.display = "block";
	}
	// AutoModeTimerFunction();
	pass();
}
function switchPage(){
	//console.log("Swtich called..");
	if(globalpage === 0){
		openPage("busy");
		window.globalpage=1;
	} else if(globalpage === 1){
		openPage("rozvrh");
		window.globalpage=2;
	} else if(globalpage === 2){
		openPage("jidelnicek");
		window.globalpage=3;
	} else if(globalpage === 3){
		openPage("weather");
		window.globalpage=0;
	}
	pass();
}
function HideAll(){
	document.getElementById('rozvrh').style.display = "none";
	document.getElementById('busy').style.display = "none";
	document.getElementById('jidelnicek').style.display = "none";
	document.getElementById('weather').style.display = "none";
}
//function GoFullScreen(){
	//if(element.requestFullscreen){element.requestFullscreen();}
	//else if(element.mozRequestFullScreen){element.mozRequestFullScreen();}
	//else if(element.webkitRequestFullscreen){element.webkitRequestFullscreen();}
	//else if(element.msRequestFullscreen){element.msRequestFullscreen();}

	//document.getElementById('element').style.cursor = "none"; 
	//document.documentElement.requestFullscreen();
	//document.getElementById('fs').style.display = "none"; 
//}
function BootUp(){
	updateTimeHeader();
	HideAll();
	AutoModeTimerFunction();
	document.getElementById('rozvrh').style.display = "block";
	document.getElementById("rozvrh_btn").className = "btn btn-warning active";
	httpGetRozvrh();
	update_jidelnicekf();
	weatherUpdate();
}
$('#element').mousedown(function(event) {
	switch (event.which) {
		case 1:
			switchPage();
			break;
		case 3:
			switchPage();
			break;
		default:
		 console.log('Fuckdup mouse');
	}
}).mouseup(function(event) {pass();});
window.addEventListener("wheel", event => {switchPage();}); // For Mouse Scroll (We dont need Scroll, should be Disabled)
function relDiff(a, b) {return  100 * Math.abs( ( a - b ) / ( (a+b)/2 ) );} // For Percentage difference
function mouseMove(e) {
	if (relDiff(globalY, e.clientY) > 25 ||  relDiff(globalX, e.clientX) > 25){ // Number 25 = 25% difference in mouse movement. Needs to be Calibrated!
		if(globalpage === 0){openPage("busy");window.globalpage=1;} else if(globalpage === 1){openPage("rozvrh");window.globalpage=2;} else if(globalpage === 2){openPage("jidelnicek");window.globalpage=3;} else if(globalpage === 3){openPage("weather");window.globalpage=0;}
		globalY = e.clientY;
		globalX = e.clientX;
		AutoMode_seconds = 00;
		AutoMode_minutes = 2;
		pass();
	}
}
function pass(){return true;} // This function is for Handling Multiple Control Requests (For instance: Multiple clicks/Mouse movements)
function timeNowHeader() {
	var d = new Date(),
	h = (d.getHours()<10?'0':'') + d.getHours(),
	m = (d.getMinutes()<10?'0':'') + d.getMinutes();
	s = (d.getSeconds()<10?'0':'') + d.getSeconds();
	return(h + ':' + m + ':' + s);
}
function updateTimeHeader() {
	var d = new Date();
	document.getElementById('headertime1').innerHTML = document.getElementById('headertime2').innerHTML = document.getElementById('headertime3').innerHTML = timeNowHeader();
	setTimeout(updateTimeHeader, 1000);
}
</script>
<style type="text/css">
* {box-sizing: border-box;}
body {overflow: hidden;}
::selection,::-moz-selection {background: transparent;}
#headertime1, #headertime2, #headertime3 {box-sizing: border-box;width: 25%;float: right;padding-right: 20px;padding-top: 10px;text-align: right;overflow: hidden;font-size: 60px;font-weight: 600;line-height: 67px;color:#202020;}
.navigation {position:absolute;width: 100px;padding-top: 15px;padding-right: -1%;}
.mainmenu {list-style: none;margin: 0;padding-left: 1rem;}
.mainmenu > .btn {width: 24px; height: 24px}
.ima_image {display: block;height: 100%;}
.center-fitimg {max-width: 100%;max-height: 100vh;margin: auto;}
.centerblock {display: block;margin-left: auto;margin-right: auto;width: 40%;margin-top: 0px;}
.centerblock_jidelna {display: block;margin-left: auto;margin-right: auto;width: 70%;margin-top: 0px;}
.header_top {position: absolute;top: 0px;bottom: 0px;left: 0px;right: 0px;}
.header {box-sizing: border-box;width: 100%;height: 10%;text-align: left;padding-left: 20px;background: #ffca2c;color: white;}
.active{padding: 1rem !important;transform: scale(1);transition: 0.15s all ease;/*width: calc(100% + 25px); height: calc(100% + 25px);*/}
.jidelna_group {display: flex;padding-top: 0.5%;}
.jid_sep {flex: 50%;padding: 0px;}
#cursor{pointer-events: none;position: absolute;}
.subject{font-size: 25px;}
.classroom{padding-left: 6px;font-size: 25px;} /* Teacher == Classroom */
.teacher{font-size: 11px;color:grey;} /* Class room == Teacher */
.rh-timetable-time,.rh-timetable-time-sep{color:grey;}
.future_subject{background-color: #FFF380!important;}
/*.future_subject > .subject {color:#ffca2c!important;}*/
.removed{background-color: rgba(194, 14, 26, 0.55)!important;}
.changed{background-color: rgba(255, 99, 109, 0.5) !important;}
</style>

<style type="text/css">
/* TIMETABLE */
.table {width:100%;border-spacing:0;border-top: 1px solid #e5e5e5;}
.table th,.table td{border: 1px solid #e5e5e5;border-width: 0 1px 1px 0;padding: 4.4px 15px;text-align:center;vertical-align:middle;}
.table th:last-child,.table td:last-child,.table-no-border .table th,.table-no-border .table td,.table th.table-no-border,.table td.table-no-border{border-right:none;}
.table th.table-cell-border,.table td.table-cell-border{border-right: 1px solid #e5e5e5;}
.table tbody tr:nth-child(odd){background:#f2f2f2;}
.table tbody tr:nth-child(odd) td.table-no-background,.table tbody tr:nth-child(odd) th.table-no-background{background:#fff;}
.table thead th,.table tfoot th,.table thead td,.table tfoot td{text-align:center;font-size:87.5%;color:#606060;}
.table thead th a,.table tfoot th a,.table thead td a,.table tfoot td a{color:inherit;}
.table thead th.active,.table tfoot th.active,.table thead td.active,.table tfoot td.active{color:#000;}
.table thead th.table-normal-padding,.table tbody th.table-normal-padding,.table tfoot th.table-normal-padding,.table thead td.table-normal-padding,.table tbody td.table-normal-padding,.table tfoot td.table-normal-padding{padding-top:8px;padding-bottom:8px;}
.table tbody th.table-normal-padding,.table tbody td.table-normal-padding{padding-left:8px;padding-right:8px;}
.table tfoot th,.table tfoot td {text-align:left;}
.table:last-child {margin-bottom:1em;}
/*.table:first-child {
  margin-top: 0em;
}*/
.table.table--without-margin-top{margin-top:0;}
.table.table--in-text{border-left: 1px solid #e5e5e5;border-right: 1px solid #e5e5e5;}
.table--error,.table--error th,.table--error td{border-color:#ed0841;}
.table--error thead th,.table--error tfoot th,.table--error thead td,.table--error tfoot td{color:#ed0841;}
.table--error tbody tr:nth-child(odd){background:#fee9ee;}
</style>
<style type="text/css">
/* IDSJMK Stylesheet */
#content {display: block;width: 100%;height: 100%;font-family: Arial;}
.blink {animation: blink-animation 1s steps(2, start) infinite;-webkit-animation: blink-animation 1s steps(2, start) infinite;}
@keyframes blink-animation {to {visibility: hidden;}}
.stop.num-1 {width: 100%;height: 100%;}
.departures {position: absolute;top: 0px;bottom: 0px;left: 0px;right: 0px;}
.depheader {box-sizing: border-box;width: 100%;height: 12.5%;text-align: left;padding-left: 20px;background: #ffca2c;color: #202020;}
#logo_ids {display: block;float: right;margin-right: 20px;height: 100%;}
.depcontent {width: 100%;height: 75%;}
.depcontentline, .depcontentstop, .depcontentplatform, .depcontentlf, .depcontenttime {float: left;height: 100%;box-sizing: border-box;}
.depcontentline {width: 15%;color: white;text-align: center;}
.depcontentstop {width: 45%;padding-left: 10px;}
.depcontentplatform {text-align: center;width: 10%;}
.depcontentlf {text-align: center;width: 10%;}
.depcontenttime {width: 20%;text-align: right;padding-right: 20px;}
.tline, .tstop, .tlowfloor, .ttime {overflow: hidden;white-space: nowrap;}
.tstop {overflow: hidden;}
.train {color: white;background-color: black;}
.w-count-4 {height: 24.9%;}
.w-count-5 {height: 19.9%;}
.w-count-6 {height: 16.6%;}
.w-count-7 {height: 14.2%;}
.w-count-8 {height: 12.4%;}
.w-count-10 {height: 9.9%;}
.w-count-12 {height: 8.3%;}
.tline {box-sizing: border-box;border-top: 1px solid white;border-left: 1px solid white;}
.tplatform {box-sizing: border-box;border-top: 1px solid white;border-left: 1px solid white;}
.depfooter {overflow: hidden;width: 100%;height: 12.5%;background: #ffca2c;color: #202020;}
#depmessage {width: 75%;float: left;}
#depcurrenttime {box-sizing: border-box;width: 25%;float: right;padding-right: 20px;font-weight: 600;text-align: right;overflow: hidden;}
.marquee {overflow: hidden;}
/* Make it move */
@keyframes marquee {
    0%   { text-indent: 19em }
    100% { text-indent: -49em }
}
p {margin: 0 0 10px;}
</style>

<style type="text/css">
	/* POCASI */
h1.title {color: rgba(255,255,255,0.8);font-family: Helvetica, Arial, sans-serif;font-size: 1.2em;font-weight: 100;letter-spacing: 1px;margin-bottom: 30px;text-transform: uppercase;}
.container_w {margin: 12% auto;text-align: center;width: 80%;}
.weatherIcon {display: inline-block;font-family: Helvetica, sans-serif;font-size: 1em;height: 100px;line-height: 1em;margin: 2%;position: relative;width: 100px;}
.weatherIcon:before, .weatherIcon:after {content: "";height: inherit;left: 0;top: 0;width: inherit;}
.weatherIcon > div:before, .weatherIcon > div:after,
.weatherIcon .inner:before, .weatherIcon .inner:after {content: "";letter-spacing: 0;position: absolute;}
.clear, .sunny, .mostlysunny, .partlycloudy, .mostlycloudy, .partlysunny, .cloudy, .fog, .hazy, .chancerain, .rain, .chancetstorms, .tstorms, .chancesleet, .sleet, .chanceflurries, .flurries, .chancesnow, .snow, .inner {height: inherit;width: inherit;}
/* MAIN PART */
/*----------------
	General
-----------------*/
/* Remove outline > Apply other styles for accessibility */
.wea_con:focus {
	outline: none;
}
.wea_con {
	color: #fff;
	font-family: 'Alegreya Sans', sans-serif;
	font-weight: 300;
	position: relative;
	letter-spacing: 0.05rem;
	line-height: 1.5;
	text-align: center;

	box-sizing: border-box;
	margin: 0;
	padding: 0;
}
#wea_con a {
	color: #6D8CA0;
	font-weight: 700;
	text-decoration: none;
}
#wea_con a:hover {
	text-decoration: underline;
}
#wea_con footer {
	color: rgba(255,255,255,0.6);
	font-size: 0.8rem;
	letter-spacing: 0.07em;
	line-height: 2;
	padding: 30px 0;
	width: 100%;
}
#lastUpdated_w {
	color: #fff;
	padding: 5% 0;
}
#lastUpdated_w:before {
	content: '-- ';
}
#lastUpdated_w:after {
	content: ' --';
}

/*----------------
	Containers
-----------------*/
.wrapper_w {
	color: #fff;
	overflow: auto;
	width: 100%;
}

/*----------------
	Status Bar
-----------------*/
#status {
	background-color: #FFECAE;
	color: rgba(0,0,0,0.5);
	display: none;
	font-size: 1rem;
	width: 100%;
	z-index: 100;
}
#status p {
	display: inline-block;
	padding: 10px 40px 6px;
}
#status a {
	color: #fff;
}
#status .close {
	background: none;
	color: rgba(0,0,0,0.5);
	float: right;
	height: 40px;
	position: absolute;
	right: 0;
	top: 0;
	width: 40px;
}
/*** Error & Success Messages ***/
#status.error {
	background-color: #EE9797;
}
#status.success {
	background-color: #82C886;
}
#status.error, #status.error .close,
#status.success, #status.success .close {
	color: #fff;
}
#status.error .fa-exclamation-triangle,
#status.success .fa-check-circle {
	margin-right: 10px;
}

/*----------------
	Buttons
-----------------*/
#wea_con button {
	border: none;
	cursor: pointer;
}
#wea_con nav {
	font-size: 1rem;
	margin: 0 auto;
	padding: 5% 0 10%;
}
#locateBtn, #unitBtn {
	background: none;
	border: 1px solid rgba(255,255,255,0.25);
	border-radius: 50%;
	color: #fff;
	height: 40px;
	transition: background 1s ease-in-out, border 0.2s ease-in-out;
	width: 40px;
}
#locateBtn.on, #unitBtn.on {
	background: rgba(255,255,255,0.25);
}
#locateBtn:focus, #locateBtn:hover, #unitBtn:focus, #unitBtn:hover {
	border: 1px solid rgba(255,255,255,0.75);
}
#locateBtn {
	margin-right: 10px;
}
#unitBtn {
	font-weight: 300;
	padding-right: 3px;
	padding-top: 2px;
	text-transform: uppercase;
}
#unitBtn:before {
	content: '\00b0'; /* Degree symbol */
	padding: 1px;
}
/* locateBtn Pulse Animation */
#locateBtn.pulse {
	animation: pulse 2s infinite;
}
@keyframes pulse {
	20% { background: rgba(255,255,255,0.25); }
}

/*----------------
	Current Weather
-----------------*/
#current_w {
	/*padding: 10% 5% 20%;*/
	padding-bottom: 2%;
	position: relative;
}
.location_w {
	font-size: 3em;
	font-weight: 700;
	margin: 0;
}
.date_w {
	font-size: 1em;
	font-weight: 300;
	text-transform: uppercase;
}
#current_w .weatherIcon {
	height: 100px;
	margin: 10% auto 7%;
	width: 100px;
}
.temp {
	font-size: 3em;
	font-weight: 700;
	line-height: 1.3;
}
.temp:after {
	content: '\00b0';
	margin-right: -0.3em;
}
#current_w .conditions {
	font-size: 1.2em;
	text-transform: uppercase;
}

/*----------------
	Future Forecast
-----------------*/
#future {
	margin-bottom: 10%;
	padding: 0 10%;
	margin-top: -150px;
}
#future > .container {
	border-bottom: 1px solid rgba(255,255,255,0.25);
	margin: 0;
	padding: 5% 10%;
	width: 100%;
}
#future > .container:first-child {
	border-top: 1px solid rgba(255,255,255,0.25);
}
#future .day {
	padding: 0;
	text-align: left;
	text-transform: uppercase;
}
#future .weatherIcon {
	float: right;
	font-size: 0.5em;
	height: 50px;
	margin-left: 10%;
	margin-top: -5%;
	width: 50px;
}
#future p { text-align: left; }
.high:after, .low:after {
	content: '\00b0';
	padding: 1px;
}

/*----------------
	Media Queries
-----------------*/
@media (min-width: 375px) {
	#wea_con nav {
		left: 5%;
		position: absolute;
		top: 10%;
	}
	#locateBtn, #unitBtn {
		display: block;
		margin-bottom: 10px;
	}
	#current { 
		/*padding-bottom: 15%;*/
		/*padding-top: 15%;*/
	}
}
@media (min-width: 550px) {
	#wea_con nav { top: 8%; }
	/* Current Weather */
	#current { 
		font-size: 1.3rem;
		/*padding-bottom: 2%;*/
		padding-top: 2%;
	}
	.date, #current .conditions {font-size: 0.9em;font-weight: 700;}
	#current .weatherIcon {
		font-size: 1.2em;
		height: 120px;
		margin: 7% auto 3%;
		width: 120px;
	}
	/* Future Forecast */
	#future {
		font-size: 1.1rem;
		padding: 0;
		margin-bottom: 3%;
		margin-top: -150px;
	}
	#future > .container_w {
		border-bottom: none;
		float: left;
		padding: 20px;
		width: 33.33%; 
	}
	#future > .container_w:first-child { border-top: none; }
	#future > .container_w:not(:last-child) { border-right: 1px solid rgba(255,255,255,0.25); }
	#future .day, #future p { text-align: center;font-weight: 700;font-size: 30px; }
	#future .weatherIcon {
		font-size: 0.56em;
		float: none;
		height: 60px;
		margin: 10% auto 5%;
		width: 60px;
	}
	#wea_con footer { font-size: 0.9rem; }
}
@media (min-width: 880px) {
	#wea_con nav { top: 5%; }
	#current {
		font-size: 1.5rem;
		/*padding-bottom: 7%;*/
		padding-top: 2%;
	}
	#current .weatherIcon {
		font-size: 0.82em;
		margin: 5% auto 2%;
	}
	#lastUpdated_w {
		padding: 3%;
	}
}

/* SUNNY
.sunny { 
	animation: sunny 15s linear infinite;
	background: linear-gradient(top, rgba(255,255,255,0) 0%, rgba(255,255,255,0.8) 50%, rgba(255,255,255,0) 100%); 
	height: 140px;
	width: 20px; 
	margin-left: -30px;
	position: absolute;
	left: 90px;  
	top: 20px;
}
.sunny:before {
	background: linear-gradient(top, rgba(255,255,255,0) 0%, rgba(255,255,255,0.8) 50%, rgba(255,255,255,0) 100%);
	content: ''; 
	height: 140px; 
	width: 20px;
	opacity: 1; 
	position: absolute;
	bottom: 0px;
	left: 0px; 
	transform: rotate(90deg);
}
.sunny:after {
	background: #FFEE44; 
	border-radius: 50%; 
	box-shadow: rgba(255,255,0,0.2) 0 0 0 15px;
	content: '';  
	height: 80px;
	width: 80px;  
	position: absolute; 
	left: -30px; 
	top: 30px;
}
@keyframes sunny { 
	0% { transform: rotate(0deg); }
	100% { transform: rotate(360deg); }
} */
.sunny {
	height: 145px;
	margin-top: -48px;
	margin-left: -39px;
	margin-bottom: -20px;
}
.sunny_main {
	height: 230px;
	margin-top: -48px;
	margin-left: -35px;
}
/* CLOUDY */
.cloudy {
	animation: cloudy 5s ease-in-out infinite;
	background: #FFFFFF;
	border-radius: 50%;
	box-shadow: 
		#FFFFFF 65px -15px 0 -5px, 
		#FFFFFF 25px -25px, 
		#FFFFFF 30px 10px, 
		#FFFFFF 60px 15px 0 -10px, 
		#FFFFFF 85px 5px 0 -5px;
	left: 255px;
	top: 70px; 
	height: 60px;
	width: 60px;
	margin-left: -30px;
}
.cloudy:after {
	animation: cloudy_shadow 5s ease-in-out infinite;
	background: #000000;
	border-radius: 50%;
	content: '';
	height: 15px;
	width: 120px;
	opacity: 0.2;
	position: absolute;
	left: 5px; 
	bottom: -35px;
  transform: scale(1);
}
.cloudy_main {
	animation: cloudy 5s ease-in-out infinite;
	background: #FFFFFF;
	border-radius: 50%;
	box-shadow: 
		#FFFFFF 130px -25px 0 -0px,
		#FFFFFF 50px -50px, 
		#FFFFFF 35px 30px, 
		#FFFFFF 105px 22px 0 -7px, 
		#FFFFFF 170px 1px 0 -5px;
	left: 255px;
	top: 70px; 
	height: 100px;
	width: 100px;
	margin-left: -30px;
}
.cloudy_main:after {
	animation: cloudy_shadow 5s ease-in-out infinite;
	background: #000000;
	border-radius: 50%;
	content: '';
	height: 15px;
	width: 230px;
	opacity: 0.2;
	position: absolute;
	left: 5px; 
	bottom: -54px;
  transform: scale(1);
}
@keyframes cloudy {
	50% { transform: translateY(-20px); }
}
@keyframes cloudy_shadow {
	50% { transform: translateY(20px) scale(.7); opacity:.05; }
}

/* RAINY */
.rainy {
	animation: rainy 5s ease-in-out infinite 1s;
	background: #CCCCCC; 
	border-radius: 50%;
	box-shadow: 
		#CCCCCC 65px -15px 0 -5px, 
		#CCCCCC 25px -25px, 
		#CCCCCC 30px 10px, 
		#CCCCCC 60px 15px 0 -10px, 
		#CCCCCC 85px 5px 0 -5px;
	display: block;
	height: 50px;
	width: 50px;
	margin-left: -30px;
	left: 427px;
	top: 70px;
}
.rainy:after {
	animation: rainy_shadow 5s ease-in-out infinite 1s;
	background: #000000;
	border-radius: 50%;
	content: '';
	height: 15px;
	width: 120px;
	opacity: 0.2;
	position: absolute;
	left: 5px; 
	bottom: -35px;
	transform: scale(1);
}
.rainy:before {
	animation: rainy_rain .7s infinite linear;
	content: '';
	background: #CCCCCC;
	border-radius: 50%;
	display: block;
	height: 6px;
	width: 3px;
	opacity: 0.3;
	transform: scale(.9);
}
.rainy_main {
	animation: rainy 5s ease-in-out infinite 1s;
	background: #CCCCCC; 
	border-radius: 50%;
	box-shadow: 
		#CCCCCC 130px -25px 0 -0px,
		#CCCCCC 50px -50px, 
		#CCCCCC 35px 30px, 
		#CCCCCC 105px 22px 0 -7px, 
		#CCCCCC 170px 1px 0 -5px;
	display: block;
	height: 100px;
	width: 100px;
	margin-left: -30px;
	left: 427px;
	top: 70px;
}
.rainy_main:after {
	animation: rainy_shadow 5s ease-in-out infinite 1s;
	background: #000000;
	border-radius: 50%;
	content: '';
	height: 15px;
	width: 240px;
	opacity: 0.2;
	position: absolute;
	left: 5px; 
	bottom: -54px;
	transform: scale(1);
}
.rainy_main:before {
	animation: rainy_rain .7s infinite linear;
	content: '';
	background: #CCCCCC;
	border-radius: 50%;
	display: block;
	height: 6px;
	width: 3px;
	opacity: 0.3;
	transform: scale(1.9);
}
@keyframes rainy {
	50% { transform: translateY(-20px); }
}
@keyframes rainy_shadow {
	50% { transform: translateY(20px) scale(.7); opacity: 0.05; }
}
@keyframes rainy_rain {
	0% {  
		box-shadow: 
			rgba(0,0,0,0) 30px 30px, 
			rgba(0,0,0,0) 40px 40px,  
			#000 50px 75px, 
			#000 55px 50px, 
			#000 70px 100px, 
			#000 80px 95px, 
			#000 110px 45px, 
			#000 90px 35px; 
	}
	25% {  
	  	box-shadow: 
			#000 30px 45px,
			#000 40px 60px,
			#000 50px 90px,
			#000 55px 65px,
			rgba(0,0,0,0) 70px 120px,
			rgba(0,0,0,0) 80px 120px,
			#000 110px 70px,
			#000 90px 60px;
	}
	26% {  
		box-shadow:
			#000 30px 45px,
			#000 40px 60px,
			#000 50px 90px,
			#000 55px 65px,
			rgba(0,0,0,0) 70px 40px,
			rgba(0,0,0,0) 80px 20px,
			#000 110px 70px,
			#000 90px 60px; 
	}
	50% { 
		box-shadow:
			#000 30px 70px,
			#000 40px 80px,
			rgba(0,0,0,0) 50px 100px,
			#000 55px 80px,
			#000 70px 60px,
			#000 80px 45px,
			#000 110px 95px,
			#000 90px 85px;
	}
	51% {
		box-shadow:
			#000 30px 70px,
			#000 40px 80px,
			rgba(0,0,0,0) 50px 45px,
			#000 55px 80px,
			#000 70px 60px,
			#000 80px 45px,
			#000 110px 95px,
			#000 90px 85px;
	}
	75% {
		box-shadow:
			#000 30px 95px,
			#000 40px 100px,
			#000 50px 60px,
			rgba(0,0,0,0) 55px 95px,
			#000 70px 80px,
			#000 80px 70px,
			rgba(0,0,0,0) 110px 120px,
			rgba(0,0,0,0) 90px 110px;
	}
	76% {
		box-shadow:
			#000 30px 95px,
			#000 40px 100px,
			#000 50px 60px,
			rgba(0,0,0,0) 55px 35px,
			#000 70px 80px,
			#000 80px 70px,
			rgba(0,0,0,0) 110px 25px,
			rgba(0,0,0,0) 90px 15px;
	}
	100% {
		box-shadow:
			rgba(0,0,0,0) 30px 120px,
			rgba(0,0,0,0) 40px 120px,
			#000 50px 75px,
			#000 55px 50px,
			#000 70px 100px,
			#000 80px 95px,
			#000 110px 45px,
			#000 90px 35px;
	}
}


/* RAINBOW */
.rainbow {
	animation: rainbow 5s ease-in-out infinite;
	border-radius: 170px 0 0 0;
	box-shadow: 
		#FB323C -2px -2px 0 1px,
		#F99716 -4px -4px 0 3px,
		#FEE124 -6px -6px 0 5px,
		#AFDF2E -8px -8px 0 7px,
		#6AD7F8 -10px -10px 0 9px,
		#60B1F5 -12px -12px 0 11px,
		#A3459B -14px -14px 0 13px;
	height: 70px;
	width: 70px;
	margin-left: -40px;
	position: absolute;
	left: 610px;
	top: 71px;
  	transform: rotate(40deg);
}
.rainbow:after {
	animation: rainbow_shadow 5s ease-in-out infinite;
	background: #000000;
	border-radius: 50%;
	content: '';
	opacity: 0.2;
	height: 15px;
	width: 120px;
	position: absolute;
	bottom: -23px; 
	left: 17px;
  	transform: rotate(-40deg);
  	transform-origin: 50% 50%;
}
@keyframes rainbow {
	50% { transform: rotate(50deg); }
}
@keyframes rainbow_shadow {
	50% { transform:  rotate(-50deg) translate(10px) scale(.7); opacity: 0.05; }
}

/* STARRY */
.starry {
  	animation: starry_star 5s ease-in-out infinite;
	background: #fff;
	border-radius: 50%;  
	box-shadow:  
      	#FFFFFF 26px 7px 0 -1px, 
      	rgba(255,255,255,0.1) -36px -19px 0 -1px, 
      	rgba(255,255,255,0.1) -51px -34px 0 -1px,
      	#FFFFFF -52px -62px 0 -1px, 
      	#FFFFFF 14px -37px, 
      	rgba(255,255,255,0.1) 41px -19px,  
      	#FFFFFF 34px -50px,
      	rgba(255,255,255,0.1) 14px -71px 0 -1px,
      	#FFFFFF 64px -21px 0 -1px, 
      	rgba(255,255,255,0.1) 32px -85px 0 -1px,
      	#FFFFFF 64px -90px,
      	rgba(255,255,255,0.1) 60px -67px 0 -1px,  
      	#FFFFFF 34px -127px,
      	rgba(255,255,255,0.1) -26px -103px 0 -1px;
	height: 4px;
	width: 4px; 
  	margin-left: -10px;
	opacity: 1;
  	left: 777px; 
  	/*top: 150px;*/
  	top: 70px;
}
.starry:after { 
	animation: starry 5s ease-in-out infinite;
	border-radius: 50%;
	box-shadow: #FFFFFF -25px 0;
	content: '';
	height: 100px;
	width: 100px;
	position: absolute;
	top: -106px; 
	transform: rotate(-5deg);
	transform-origin: 0 50%;
}

@keyframes starry {
	50% { transform: rotate(10deg); }
}
@keyframes starry_star {
  50% { 
	box-shadow:  
		rgba(255,255,255,0.1) 26px 7px 0 -1px, 
      	#FFFFFF -36px -19px 0 -1px, 
      	#FFFFFF -51px -34px 0 -1px,
      	rgba(255,255,255,0.1) -52px -62px 0 -1px, 
      	rgba(255,255,255,0.1) 14px -37px,
      	#FFFFFF 41px -19px,   
      	rgba(255,255,255,0.1) 34px -50px,
      	#FFFFFF 14px -71px 0 -1px,
      	rgba(255,255,255,0.1) 64px -21px 0 -1px, 
      	#FFFFFF 32px -85px 0 -1px,
      	rgba(255,255,255,0.1) 64px -90px,
      	#FFFFFF 60px -67px 0 -1px,  
      	rgba(255,255,255,0.1) 34px -127px,
      	#FFFFFF -26px -103px 0 -1px;
	}
}

/* STORMY */
.stormy {
	animation: stormy 5s ease-in-out infinite;
	background: #222222;
	border-radius: 50%;
	box-shadow: 
		#222222 65px -15px 0 -5px, 
		#222222 25px -25px, 
		#222222 30px 10px, 
		#222222 60px 15px 0 -10px, 
		#222222 85px 5px 0 -5px;
	height: 50px;  
	width: 50px; 
	margin-left: -30px;
	left: 947px; 
	top: 70px; 
}
.stormy:after {
	animation: stormy_shadow 5s ease-in-out infinite;
	background: #000;
	border-radius: 50%;
	content: '';
	height: 15px; 
	width: 120px; 
	opacity: 0.2;
	position: absolute;
	left: 5px; 
	bottom: -35px;
	transform: scale(1);
}
.stormy:before {
	animation: stormy_thunder 2s steps(1, end) infinite; 
	border-left: 0px solid transparent;
	border-right: 7px solid transparent;
	border-top: 43px solid yellow; 
	box-shadow: yellow -7px -32px;
	content: '';
	display: block;
	height: 0;
	width: 0;
	position: absolute;
	left: 57px;
	top: 70px;
	transform: rotate(14deg);
	transform-origin: 50% -60px;
}
.stormy_main {
	animation: stormy 5s ease-in-out infinite;
	background: #222222;
	border-radius: 50%;
	box-shadow: 
		#222222 65px -15px 0 -5px, 
		#222222 25px -25px, 
		#222222 30px 10px, 
		#222222 60px 15px 0 -10px, 
		#222222 85px 5px 0 -5px;
	height: 50px;  
	width: 50px; 
	margin-left: -30px;
	left: 947px; 
	top: 70px; 
}
.stormy_main:after {
	animation: stormy_shadow 5s ease-in-out infinite;
	background: #000;
	border-radius: 50%;
	content: '';
	height: 15px; 
	width: 120px; 
	opacity: 0.2;
	position: absolute;
	left: 5px; 
	bottom: -35px;
	transform: scale(1);
}
.stormy_main:before {
	animation: stormy_thunder 2s steps(1, end) infinite; 
	border-left: 0px solid transparent;
	border-right: 7px solid transparent;
	border-top: 43px solid yellow; 
	box-shadow: yellow -7px -32px;
	content: '';
	display: block;
	height: 0;
	width: 0;
	position: absolute;
	left: 57px;
	top: 70px;
	transform: rotate(14deg);
	transform-origin: 50% -60px;
}
@keyframes stormy {
	50% { transform: translateY(-20px); } 
}  
@keyframes stormy_shadow {
	50% { transform: translateY(20px) scale(.7); opacity: 0.05; }
}
@keyframes stormy_thunder {
	0%  { transform: rotate(20deg); opacity:1; }
	5%  { transform: rotate(-34deg); opacity:1; }
	10% { transform: rotate(0deg); opacity:1; }
	15% { transform: rotate(-34deg); opacity:0; }
}

/* SNOWY */
.snowy {
	animation: snowy 5s ease-in-out infinite 1s;
	background: #FFFFFF; 
	border-radius: 50%;
	box-shadow: 
		#FFFFFF 65px -15px 0 -5px, 
		#FFFFFF 25px -25px, 
		#FFFFFF 30px 10px, 
		#FFFFFF 60px 15px 0 -10px, 
		#FFFFFF 85px 5px 0 -5px;
	display: block;
	height: 50px;
	width: 50px;
	margin-left: -30px;
	left: 1112px;
	top: 70px;
}
.snowy:after {
	animation: snowy_shadow 5s ease-in-out infinite 1s;
	background: #000000;
	border-radius: 50%;
	content: '';
	height: 15px;
	width: 120px;
	opacity: 0.2;
	position: absolute;
	left: 8px;
	bottom: -35px;
	transform: scale(1);
}
.snowy:before {
	animation: snowy_snow 2s infinite linear;
	content: '';
	border-radius: 50%;
	display: block;
	height: 7px;
	width: 7px;
	opacity: 0.8;
	transform: scale(.9);
}
.snowy_main {
	animation: snowy 5s ease-in-out infinite 1s;
	background: #FFFFFF; 
	border-radius: 50%;
	box-shadow: 
		#FFF 130px -25px 0 -0px,
		#FFF 50px -50px, 
		#FFF 35px 30px, 
		#FFF 105px 22px 0 -7px, 
		#FFF 170px 1px 0 -5px;
	display: block;
	height: 100px;
	width: 100px;
	margin-left: -30px;
	left: 1112px;
	top: 70px;
}
.snowy_main:after {
	animation: snowy_shadow 5s ease-in-out infinite 1s;
	background: #000000;
	border-radius: 50%;
	content: '';
	height: 15px;
	width: 230px;
	opacity: 0.2;
	position: absolute;
	left: 8px;
	bottom: -54px;
	transform: scale(1);
}
.snowy_main:before {
	animation: snowy_snow 2s infinite linear;
	content: '';
	border-radius: 50%;
	display: block;
	height: 7px;
	width: 7px;
	opacity: 0.8;
	transform: scale(1.9);
}
@keyframes snowy {
	50% { transform: translateY(-20px); }
}
@keyframes snowy_shadow {
	50% { transform: translateY(20px) scale(.7); opacity: 0.05; }
}
@keyframes snowy_snow {
	0% {  
		box-shadow: 
			rgba(238,238,238,0) 30px 30px, 
			rgba(238,238,238,0) 40px 40px,  
			#EEEEEE 50px 75px, 
			#EEEEEE 55px 50px, 
			#EEEEEE 70px 100px, 
			#EEEEEE 80px 95px, 
			#EEEEEE 110px 45px, 
			#EEEEEE 90px 35px; 
	}
	25% {  
	  	box-shadow: 
			#EEEEEE 30px 45px,
			#EEEEEE 40px 60px,
			#EEEEEE 50px 90px,
			#EEEEEE 55px 65px,
			rgba(238,238,238,0) 70px 120px,
			rgba(238,238,238,0) 80px 120px,
			#EEEEEE 110px 70px,
			#EEEEEE 90px 60px;
	}
	26% {  
		box-shadow:
			#EEEEEE 30px 45px,
			#EEEEEE 40px 60px,
			#EEEEEE 50px 90px,
			#EEEEEE 55px 65px,
			rgba(238,238,238,0) 70px 40px,
			rgba(238,238,238,0) 80px 20px,
			#EEEEEE 110px 70px,
			#EEEEEE 90px 60px; 
	}
	50% { 
		box-shadow:
			#EEEEEE 30px 70px,
			#EEEEEE 40px 80px,
			rgba(238,238,238,0) 50px 100px,
			#EEEEEE 55px 80px,
			#EEEEEE 70px 60px,
			#EEEEEE 80px 45px,
			#EEEEEE 110px 95px,
			#EEEEEE 90px 85px;
	}
	51% {
		box-shadow:
			#EEEEEE 30px 70px,
			#EEEEEE 40px 80px,
			rgba(238,238,238,0) 50px 45px,
			#EEEEEE 55px 80px,
			#EEEEEE 70px 60px,
			#EEEEEE 80px 45px,
			#EEEEEE 110px 95px,
			#EEEEEE 90px 85px;
	}
	75% {
		box-shadow:
			#EEEEEE 30px 95px,
			#EEEEEE 40px 100px,
			#EEEEEE 50px 60px,
			rgba(238,238,238,0) 55px 95px,
			#EEEEEE 70px 80px,
			#EEEEEE 80px 70px,
			rgba(238,238,238,0) 110px 120px,
			rgba(238,238,238,0) 90px 110px;
	}
	76% {
		box-shadow:
			#EEEEEE 30px 95px,
			#EEEEEE 40px 100px,
			#EEEEEE 50px 60px,
			rgba(238,238,238,0) 55px 35px,
			#EEEEEE 70px 80px,
			#EEEEEE 80px 70px,
			rgba(238,238,238,0) 110px 25px,
			rgba(238,238,238,0) 90px 15px;
	}
	100% {
		box-shadow:
			rgba(238,238,238,0) 30px 120px,
			rgba(238,238,238,0) 40px 120px,
			#EEEEEE 50px 75px,
			#EEEEEE 55px 50px,
			#EEEEEE 70px 100px,
			#EEEEEE 80px 95px,
			#EEEEEE 110px 45px,
			#EEEEEE 90px 35px;
	}
}

/* CLEAR */
.partly_cloudy_main {
  height: 145px;
  margin-top: -70px;
  margin-left: -39px;
  margin-bottom: -15px;
}
.partly_cloud_main {
	animation: cloudy 5s ease-in-out infinite;
	background: #FFFFFF;
	border-radius: 50%;
	box-shadow: 
		#FFFFFF 130px -25px 0 -0px,
		#FFFFFF 50px -50px, 
		#FFFFFF 35px 30px, 
		#FFFFFF 105px 22px 0 -7px, 
		#FFFFFF 170px 1px 0 -5px;
	left: 255px;
	top: 70px; 
	height: 100px;
	width: 100px;
	margin-left: -30px;
	margin-top: -35px;
}
.partly_cloudy {
	height: 103px;
	margin-top: -76px;
	margin-left: -39px;
	margin-bottom: -40px;
}
.partly_cloud {
	animation: cloudy 5s ease-in-out infinite;
	background: #FFFFFF;
	border-radius: 50%;
	box-shadow: 
		#FFFFFF 65px -15px 0 -5px, 
		#FFFFFF 25px -25px, 
		#FFFFFF 30px 10px, 
		#FFFFFF 60px 15px 0 -10px, 
		#FFFFFF 85px 5px 0 -5px;
	left: 255px;
	top: 70px; 
	height: 60px;
	width: 60px;
	margin-left: -30px;
}
</style>