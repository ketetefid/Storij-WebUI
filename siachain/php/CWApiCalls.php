<?php
$apicurl = 'http://localhost:9980/consensus';
$chc = curl_init();
curl_setopt($chc, CURLOPT_USERAGENT, "Sia-Agent");
curl_setopt($chc, CURLOPT_URL, $apicurl);
curl_setopt($chc, CURLOPT_HEADER, false);
curl_setopt($chc, CURLOPT_RETURNTRANSFER, true);
curl_setopt($chc, CURLOPT_BINARYTRANSFER, true);
curl_setopt($chc, CURLOPT_CONNECTTIMEOUT, 5);
$response = curl_exec($chc);
$httpCode = curl_getinfo($chc, CURLINFO_HTTP_CODE);
curl_close($chc);

if ($httpCode == 200) {
  $siastatus=1;
  $datac = json_decode($response);
  $syncedstat=$datac->synced;
  $height=$datac->height;
  $difficulty=$datac->difficulty;
} else {
  $siastatus=0;
}

$apiwurl = 'http://localhost:9980/wallet';
$chw = curl_init();
curl_setopt($chw, CURLOPT_USERAGENT, "Sia-Agent");
curl_setopt($chw, CURLOPT_URL, $apiwurl);
curl_setopt($chw, CURLOPT_HEADER, false);
curl_setopt($chw, CURLOPT_RETURNTRANSFER, true);
curl_setopt($chw, CURLOPT_BINARYTRANSFER, true);
curl_setopt($chw, CURLOPT_CONNECTTIMEOUT, 5);
$response = curl_exec($chw);
$httpCode = curl_getinfo($chw, CURLINFO_HTTP_CODE);
curl_close($chw);

if ($httpCode == 200) {
  $dataw = json_decode($response);
  $isInitialized=$dataw->encrypted;
  $wUnlocked = $dataw-> unlocked;
  $IsScanning = $dataw-> rescanning;
  $hastings=$dataw->confirmedsiacoinbalance;
  $scoins=floatval($hastings)/pow(10,24);
  $scoinsout=floatval($dataw->unconfirmedoutgoingsiacoins)/pow(10,24);
  $scoinsin=floatval($dataw->unconfirmedincomingsiacoins)/pow(10,24);
  
  $sfunds=$dataw->siafundbalance;
}

$apiwaurl = 'http://localhost:9980/wallet/addresses';
$chwa = curl_init();
curl_setopt($chwa, CURLOPT_USERAGENT, "Sia-Agent");
curl_setopt($chwa, CURLOPT_URL, $apiwaurl);
curl_setopt($chwa, CURLOPT_HEADER, false);
curl_setopt($chwa, CURLOPT_RETURNTRANSFER, true);
curl_setopt($chwa, CURLOPT_BINARYTRANSFER, true);
curl_setopt($chwa, CURLOPT_CONNECTTIMEOUT, 5);
$response = curl_exec($chwa);
$httpCode = curl_getinfo($chwa, CURLINFO_HTTP_CODE);
curl_close($chwa);

if ($httpCode == 200) {
  $addresses=json_decode($response)->addresses;
}

?>
