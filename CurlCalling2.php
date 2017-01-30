<?php error_reporting( E_ALL & ~E_WARNING); ?>
<?php
/*
* Call getNearestMail($url) with dynemic Url returns Nearest Mailbox Details with Address as Array
*/
echo "<pre>";
print_r(getNearestMail1("https://www.postnl.nl/services/adreskenmerken/api/GetLocationsSidebar?criterium=0&product=2&swLat=52.47133944221238&swLng=4.807203958543369&neLat=52.47558735022358&neLng=4.819005678208896&apikey=e9b6ea2efe17510f"));
echo "</pre>";

$latitude = '52.47133944221238';
$longitude= '4.807203958543369';
echo "<pre>";
print_r(getNearestMail($latitude,$longitude));
echo "</pre>";

function getNearestMail1($url) {
	$ch = curl_init();
	$curlConfig = array(
		CURLOPT_URL            => $url,
		CURLOPT_RETURNTRANSFER => true,
	);
	curl_setopt_array($ch, $curlConfig);
	$result = curl_exec($ch);
	$error = curl_error($ch);
	curl_close($ch);
	if(!$error) {
		$object = json_decode($result,true);
		return $object['list'];
	} else {
		return $error;
	}
}

function getNearestMail($latitude,$longitude) {
	$url = "https://www.postnl.nl/services/adreskenmerken/api/GetLocationsSidebar?criterium=0&product=2&swLat=".$latitude."&swLng=".$longitude."&neLat=".$latitude."&neLng=".$longitude."&apikey=e9b6ea2efe17510f";
	echo "<pre>";
	print_r($url);
	echo "</pre>";
	$ch = curl_init();
	$curlConfig = array(
		CURLOPT_URL            => $url,
		CURLOPT_RETURNTRANSFER => true,
	);
	curl_setopt_array($ch, $curlConfig);
	$result = curl_exec($ch);
	$error = curl_error($ch);
	curl_close($ch);
	if(!$error) {
		$object = json_decode($result,true);
		return $object['list'];
	} else {
		return $error;
	}
}
?>
