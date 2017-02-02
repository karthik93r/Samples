<?php
/**Get Nearest Shoping Market***/

//extract data from the post
//set POST variables
$url = 'http://www.supermarktindebuurt.nl/zoeken/';
$postcode = '5282AB';

$fields = array(
	'zipcode' => $postcode,
	'submit' => '',
);

//url-ify the data for the POST
$fields_string = http_build_query($fields);

//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

//execute post
$result = curl_exec($ch);

//close connection
curl_close($ch);
echo "<pre>";
print_r($result);
echo "</pre>";

?>
