<?php 

/***Useing*Google*Map**/
/*you can give address*/
$address = 'Weefhuispad 1, Zaandijk';

/*you can also postcode*/
//$address = '5582AB';
$latlong = getLocationLatLan($address);

$fields = array(
	'action' => 'store_search',
	'lat' => $latlong['lat'],
	'lng' => $latlong['lng'],
	'max_results' => 10,
	'radius' => 10
);

/*
*action - store_search (default - for get nearest stores)
*lat - latitude of given address
*lng - longitude of given address
*max_results - maximum results we want
*radius - results fetch arround this value
*/

//Function call to get nearest stores
$nearestStores = getNeatestStore($fields);

echo "<pre>";
print_r($nearestStores);
echo "</pre>";

function getNeatestStore($fields) {
	$fields_string = http_build_query($fields);
	$url = "http://www.supermarkt.mobi/wp-admin/admin-ajax.php?$fields_string";
	$responce = file_get_contents($url);
	$responce = json_decode($responce,true);
	if(!empty($responce)) {
		$return = array();
		foreach($responce as $key => $value) {
			$return[$key]['store'] = $value['store'];
			$return[$key]['distance'] = $value['distance'];
			$return[$key]['address'] = $value['address'];
			$return[$key]['address2'] = $value['address2'];
			$return[$key]['city'] = $value['city'];
			$return[$key]['zip'] = $value['zip'];
			$return[$key]['lat'] = $value['lat'];
			$return[$key]['lng'] = $value['lng'];
		}
	}
	return $return;
}

/***Using*Google*Map**/

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
?>
