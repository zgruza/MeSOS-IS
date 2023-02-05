<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"https://elp.idsjmk.cz/Panel");
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'cookie: __RequestVerificationToken=dIYRIfRAmZyjSHfMmn1AvRl_3APB7OYDkpvzE1Zh97u9-njbKTS0BJQYhIo9WWGiW5W7Ci2gTZ1gFXZ19822xLSpVsiwP39HtXfeRdh9GG81; .AspNet.ApplicationCookie=9WN66nR6Vz-u0I7VgNoXY6YVz_6EndpIECbDNtQok08O0VObDOXwGKNE-TRycTs_SAMf4x5KVC7YUDv_BM3Di9soKGMtuK6YVJtUzJF69VDl1r3gDsQJz3mB3gfDWldI-DpYPI5IOYgOFjhrk7jiKPfTYkZu0kOPEsCOmQxH_ngEPvkMMfre9_lcD1TQqFYPDiRXaC_Rf-2tHd0EyiEtbjfOfsxEgAnm2esQ3-1VRRHhqOWmEXiDlvfOPXhLTpP2C1Y6tkcFxp_sFG4l4oKxSBV6RzqUzV2nh5HjlFoOxcCPsczYNiV5kUmQc2Shq51rfVRI1Vpqzv2VQ5EpcUzrrHbJbh467YIZolvYNoMYcAGB0RP-8cuCUVjvSvBNuO1xj3cZeAdU2RpbAPOU5g35uLAeYk0s_hwAjcXg7MJ4e390ofWmgTTzEFHei343g7uPQxfXIPirmhDo4AmJOSxaPrLoEPI4grGYpHYr70ti2D7_308wtvYnp1iUJun2ClCM',
	'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
	'Host: elp.idsjmk.cz',
	'Sec-Fetch-Mode: navigate',
	'Sec-Fetch-Dest: document',
	'Sec-Fetch-Site: none',
	'Sec-Fetch-User: ?1"',
	'Upgrade-Insecure-Requests: 1',
	'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:105.0) Gecko/20100101 Firefox/105.0'
));
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
$l_json=curl_exec($ch);
curl_close($ch);
$l_json = str_replace("<link href=\"/Content/panel.css?v=20180820\" rel=\"stylesheet\" type=\"text/css\" />", "", $l_json); // Remove CSS, we don't need
$l_json = str_replace("/Content/wheelchair.png","/img/wheelchair.png",$l_json); // Fix KRYPL image
$l_json = str_replace("/Content/logo_ids.png","/img/logo_ids.png",$l_json); // Load locally
$l_json = str_replace("/Scripts/jquery.keyframes.min.js","/assets/jquery.keyframes.min.js",$l_json); // Load locally
$l_json = str_replace("/Scripts/jquery.marquee.min.js","/assets/jquery.marquee.min.js",$l_json); // Load locally
//$l_json = str_replace("https://code.jquery.com/jquery-3.2.1.min.js","/assets/jquery-3.2.1.min.js",$l_json); // Load Javascript libs locally
$l_json = str_replace('<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>',"",$l_json); // Pull out jQuery, because we already loaded one.
$l_json = str_replace("href=\"/","href=\"/api_faker.php?URL=https://elp.idsjmk.cz/",$l_json); // Pass all Request thru our API
//$l_json = str_replace("src=\"/","src=\"/api_faker.php?URL=https://elp.idsjmk.cz/",$l_json); // Pass all Request thru our API
$l_json = str_replace("url: \"","url: \"/api_faker.php?URL=https://elp.idsjmk.cz/",$l_json); // Pass all Request thru our API
$l_json = str_replace("cLF += '<div class=\"tlowfloor ' + departureClass + '\">' + (dep.IsLowFloor ? '<img style=\"max-height: ' + safeFontSize + 'px;\" src=\"./img/wheelchair.png\" />' : '') + '</div>';", "cLF += '<div class=\"tlowfloor ' + departureClass + '\">' + (dep.IsLowFloor ? '<img style=\"max-height: ' + safeFontSize + 'px;margin-top:15px;\" src=\"./img/wheelchair.png\" />' : '') + '</div>';", $l_json); // Fix KRYPL image margin
//$l_json = str_replace("max-height: 7px;","max-height: 38.92px;margin-top:15px",$l_json); // Fix KRYPL image margin
// Remove Ads
$l_json = str_replace("               updateAds()","",$l_json); // Space IS IMPORTANT!
$l_json = str_replace("timerAdDepartures = setTimeout(startDisplayingAds, adMinDepTime * 1000);","",$l_json);
$l_json = str_replace("startDisplayingAds();", ";", $l_json);
// Remove reloader
$l_json = str_replace("timerReload = setTimeout(function() { location.reload() }, 3600000);", "", $l_json);
// Remove logs (We don't want to overflow the RPi memory with bs.)
$l_json = str_replace("console.log(", "//console.log(", $l_json);

// Zobrazit zprávu když žádný autobus nejede (Tohle idsjmk nema v systemu udelane, takze je potreba pridat)
//$l_json = str_replace("var cTime = '';", "var cTime = '';\n\nvar NOBUS = '0';", $l_json);
$inject = "        var NOBUS = \"0\";\n\n        function NoBusArrive(){
					if (NOBUS == \"1\"){
						$(\".depcontentline\").html(\"\");
                        $(\".depcontentstop\").html(\"V nejbližší době nic nejede.\");
                        $(\".depcontentplatform\").html(\"\");
                        $(\".depcontentlf\").html(\"\");
                        $(\".depcontenttime\").html(\"\");
					}       
            }";
$l_json = str_replace("var safeFontSize;", "var safeFontSize;\n\n".$inject, $l_json);
$l_json = str_replace("cTime += '<div class=\"ttime ' + departureClass + '' + (dep.TimeMark == \"**\" || dep.TimeMark == \"±&nbsp;**\" ? ' blink' : '') + '\">' + dep.TimeMark.replace(\"**\", \"* *&nbsp;&nbsp;\") + '</div>';", "cTime += '<div class=\"ttime ' + departureClass + '' + (dep.TimeMark == \"**\" || dep.TimeMark == \"±&nbsp;**\" ? ' blink' : '') + '\">' + dep.TimeMark.replace(\"**\", \"* *&nbsp;&nbsp;\") + '</div>';" . "\n                            NOBUS = '0'", $l_json);  // Pokud v JSON existuje spoj        
$l_json = str_replace("//console.log(\"response.DepList is null\");", "NOBUS = '1';", $l_json); // Pokud v JSON neni zadny spoj
$l_json = str_replace("timerDep = setTimeout(updateDepartures, updateInterval * 1000);", "timerDep = setTimeout(updateDepartures, updateInterval * 1000);\n            NoBusArrive();",$l_json);
echo $l_json;
unset($inject);
unset($ch);
?>