<?php error_reporting( E_ALL & ~E_WARNING); ?>
<?php
$address = 'Weefhuispad+1,zaandijk';
$latlang = getLocationLatLan($address);

echo "<pre>";
print_r($latlang);
echo "</pre>";

$lat = $latlang['lat'];
$lng = $latlang['lng'];
echo "<pre>";
print_r(getNearestMail($lat,$lng));
echo "</pre>";


/*echo "<pre>";
print_r(getNearestMail1("https://www.postnl.nl/services/adreskenmerken/api/GetLocationsSidebar?criterium=0&product=2&swLat=52.47133944221238&swLng=4.807203958543369&neLat=52.47558735022358&neLng=4.819005678208896&apikey=e9b6ea2efe17510f"));
echo "</pre>";*/

/*
* Call getNearestMail($url) with dynemic Url returns Nearest Mailbox Details with Address as Array

$latitude = '52.47133944221238';
$longitude= '4.807203958543369';
echo "<pre>";
print_r(getNearestMail($latitude,$longitude));
echo "</pre>";
*/

function getLocationLatLan($address) {
	$details_url = "http://maps.googleapis.com/maps/api/geocode/json?address=".$address."&sensor=false";
	$ch = curl_init();
	$curlConfig = array(
		CURLOPT_URL            => $details_url,
		CURLOPT_RETURNTRANSFER => true,
	);
	curl_setopt_array($ch, $curlConfig);
	$result = curl_exec($ch);
	$error = curl_error($ch);
	curl_close($ch);
	if(!$error) {
		$object = json_decode($result,true);
		if($object['status'] == 'OK') {
			return $object['results'][0]['geometry']['location'];
		} else {
			return $object['error_message'];
		}
		die;
	} else {
		return $error;
	}
}

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

function getNearestMail($latitude,$langitude) {
	$url = "https://www.postnl.nl/services/adreskenmerken/api/GetLocationsSidebar?criterium=0&product=2&swLat=".($latitude-0.02)."&swLng=".($langitude-0.02)."&neLat=".($latitude+0.02)."&neLng=".($langitude+0.02)."&apikey=e9b6ea2efe17510f";
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
