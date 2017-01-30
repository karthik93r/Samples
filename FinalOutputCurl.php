<?php error_reporting( E_ALL & ~E_WARNING); ?>
<?php

/* dynamic mailbox fetching functionality start */
# step 1
# your searching input example for 'Weefhuispad 1,zaandijk';
$address = 'Weefhuispad+1,zaandijk';
# step 2
# Fetching lattitude and longitude details for the searched address by calling the function getLocationLatLan('****')
$latlang = getLocationLatLan($address);
# step 3
# use the location details $latlang to fetch/get mailbox location
$lat = $latlang['lat'];
$lng = $latlang['lng'];


# step 4
# Finding nearest mail boxes 
#---------------------------

# method 1 elaborated detailed method - start here
#Parameters values
# 0.01 - refers mailboxes from 11 to 12 kms around the search area
$swlatitude=$lat-0.01;
$swlongitude=$lng-0.01;
$nelatitude=$lat+0.01;
$nelongitude=$lng+0.01;
$apikey="e9b6ea2efe17510f";

# needed parameters to fetch result
$params=array(
		"criterium"=>"0",
		"product"=>"2",
		"swLat"=>$swlatitude,
		"swLng"=>$swlongitude,
		"neLat"=>$nelatitude,
		"neLng"=>$nelongitude,
		"apikey"=>$apikey,
);
$query=http_build_query($params);
$url="https://www.postnl.nl/services/adreskenmerken/api/GetLocationsSidebar?$query";

$nearest_mailbox=getNearest_Mail($url);
echo "<pre>";
print_r($nearest_mailbox);
echo "</pre>";

# method 1 end here

# method 2 simple way to fetch details start here
$nearest_mailbox_m2=getNearestMail($lat,$lng);

echo "<pre>";
print_r($nearest_mailbox_m2);
echo "</pre>";
# method 2 end here

#Curl Function to find lattitude and longitude for the searched address
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

#Curl function to fetch nearest mail for method1
function getNearest_Mail($url) {
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

#Curl function to fetch nearest mail for method2
function getNearestMail($latitude,$longitude) {
	$url = "https://www.postnl.nl/services/adreskenmerken/api/GetLocationsSidebar?criterium=0&product=2&swLat=".($latitude-0.02)."&swLng=".($longitude-0.02)."&neLat=".($latitude+0.02)."&neLng=".($longitude+0.02)."&apikey=e9b6ea2efe17510f";
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