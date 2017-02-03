<?php 
	/********************************************************
	*	Auther: Getmyteam4u									*
	*	Contact: getmyteam4u@gmail.com						*
	*	Freelauncher: Getmyteam4u							*
	*********************************************************/
	error_reporting( E_ALL & ~E_WARNING); 
?>
<?php
/***Useing*Google*Map**/
/*you can give address*/
//$address = 'Weefhuispad 1, Zaandijk';

/*you can also postcode*/
//$address = '600018';
$latlong = getLocationLatLan($address);

$fields = array(
	'location' => $latlong['lat'].','.$latlong['lng'],
	'radius' => 500,
	'type' => 'grocery_or_supermarket',
	'sensor' => true,
	'key' => 'AIzaSyASP02W3Qwb75Ruep3isiGstqA7Y2HXjGw'
);
/*
*location - latitute and longitude of given address
*radius - results fetch arround this value
*type - type of searching place
*key - API key
*/

//Function call to get nearest stores
$nearestStores = getNeatestStore($fields);

echo "<pre>";
print_r($nearestStores);
echo "</pre>";

function getNeatestStore($fields) {
	$fields_string = http_build_query($fields);
	$url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?$fields_string";
	$responce = file_get_contents($url);
	$responce = json_decode($responce,true);
	if($responce['status'] != '' && $responce['status'] == 'OK') {
		$return = array();
		foreach($responce['results'] as $key => $value) {
			$return[$key]['name'] = $value['name'];
			$return[$key]['address'] = $value['vicinity'];
			$return[$key]['location'] = $value['geometry']['location'];
		}
	}
	return $return;
}

/***Useing*Google*Map**/

function getLocationLatLan($address) {
	$details_url = "http://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($address)."&sensor=false";
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
