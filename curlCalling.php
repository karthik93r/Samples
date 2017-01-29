<?php error_reporting( E_ALL & ~E_WARNING); ?>
<?php
$ch = curl_init();
$curlConfig = array(
    CURLOPT_URL            => "https://www.postnl.nl/locatiewijzer/#/?centerLat=52.473463447473485&centerLng=4.813104818376132&criterium=&picketLat=52.4736696&picketLng=4.812634800000069&productid=&productpanel=mailbox&query=Weefhuispad%201%2C%20zaandijk&searchVisible&zoom=17",
    CURLOPT_RETURNTRANSFER => true,
);
curl_setopt_array($ch, $curlConfig);
$result = curl_exec($ch);
echo "<pre>";
print_r($result );

$error = curl_error($ch);
echo "result<br>";
$dom = new DOMDocument();
$dom->loadHTML( $result );
print_r($dom);
echo "<br>error<br>";
echo $error;
curl_close($ch);
?>
