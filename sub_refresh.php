<?php 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://twitchemotes.com/api_cache/v3/subscriber.json"); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($ch);
curl_close($ch);
$fp = fopen(__DIR__ . '/json/subscriber.json', 'w');
if($fp === FALSE) echo "ERROR";
else {
    fwrite($fp, $output);
    fclose($fp);
    echo "OK";
}