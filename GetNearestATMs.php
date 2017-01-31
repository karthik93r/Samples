<?php 
	/********************************************************
	*	Auther: Getmyteam4u									*
	*	Contact: getmyteam4u@gmail.com						*
	*	Freelauncher: Getmyteam4u							*
	*********************************************************/
	error_reporting( E_ALL & ~E_WARNING); 
?>
<?php
#step 1
$address = 'Weefhuispad+1,zaandijk';

#step 2
$latlang = getLocationLatLan($address);

#step 3
$lat = $latlang['lat'];
$lng = $latlang['lng'];

#step 4
$nearestATMs = getNearestATM($lat,$lng);

echo "<pre>";
print_r($nearestATMs);
echo "</pre>";



function getNearestATM($latitude,$longitude) {
	$params=array(
		"latitude"=>$latitude,
		"longitude"=>$longitude,
		"radius"=>5,
		"distanceUnit"=>'',
		"locationType"=>'atm',
		"maxLocations"=>'100',
		"instName"=>'',
		"supportEMV"=>'',
		"customAttr1"=>'',
		"locationTypeId"=>'',
	);
	$query=http_build_query($params);
	$url="https://www.mastercard.nl/locator/NearestLocationsService/?$query";
	$result = file_get_contents($url);
	
	$xml = simplexml_load_string($result);

	$responceArray = array();
	foreach($xml->location as $key=>$atmObj) {
		$temp = (array)$atmObj;
		$temp2 = (array)$temp['attributes']->attribute;
		$address = (array)$temp['address'];
		$responceArray[] = array(
			'bankName'=>$temp['name'],
			'distance'=> round(1.6 * $temp['@attributes']['distance'],2),
			'locationName'=>$temp2[16],
			'address' => array(
				'street' => $address['street'],
				'city' => $address['city'],
				'countrySubDivision' => $address['countrySubDivision'],
				'postalCode' => $address['postalCode'],
			),
		);
	}

	return $responceArray;
}

#Curl function to fetch lattitude and longitude
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
?>