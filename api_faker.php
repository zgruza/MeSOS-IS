<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$_GET['URL']);
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
die($l_json);
?>