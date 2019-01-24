<?php

$apihcurl = 'http://localhost:5580/host';
$chh = curl_init();
curl_setopt($chh, CURLOPT_USERAGENT, "Hyperspace-Agent");
curl_setopt($chh, CURLOPT_URL, $apihcurl);
curl_setopt($chh, CURLOPT_HEADER, false);
curl_setopt($chh, CURLOPT_RETURNTRANSFER, true);
curl_setopt($chh, CURLOPT_BINARYTRANSFER, true);
curl_setopt($chh, CURLOPT_CONNECTTIMEOUT, 5);
$response = curl_exec($chh);
$httpCode = curl_getinfo($chh, CURLINFO_HTTP_CODE);
curl_close($chh);

if ($httpCode == 200) {
    $siastatus=true;
    $datah = json_decode($response);
    $acceptcontract=$datah->externalsettings->acceptingcontracts;
    $hostaddress=$datah->externalsettings->netaddress;
    $hostversion=$datah->externalsettings->version;
    $connectability=$datah->connectabilitystatus;
    $workingstatus=$datah->workingstatus;
    $ncontracts=$datah->financialmetrics->contractcount;
    $maxduration=$datah->externalsettings->maxduration;
    $earnedSC=floatval($datah->financialmetrics->contractcompensation)/pow(10,24);
    $tobeEarnedSC=floatval($datah->financialmetrics->potentialcontractcompensation)/pow(10,24);
    $lostSC=floatval($datah->financialmetrics->lostrevenue)/pow(10,24);

    $maxdlbsize=number_format($datah->externalsettings->maxdownloadbatchsize/1024/1024,3);
    $maxrevbsize=number_format($datah->externalsettings->maxrevisebatchsize/1024/1024,3);
    $totalstorage=number_format($datah->externalsettings->totalstorage/pow(1024,3),3);
    $unusedstorage=number_format($datah->externalsettings->remainingstorage/pow(1024,3),3);
    $windowsize=$datah->externalsettings->windowsize;
    $collateral=$datah->externalsettings->collateral;
    $maxcollateral=floatval($datah->externalsettings->maxcollateral)/pow(10,24);
    $contractprice=floatval($datah->externalsettings->contractprice)/pow(10,24);
    $dlprice=$datah->externalsettings->downloadbandwidthprice;
    $upprice=$datah->externalsettings->uploadbandwidthprice;
    $storageprice=$datah->externalsettings->storageprice;
    $lockedstcollat=floatval($datah->financialmetrics->lockedstoragecollateral)/pow(10,24);
    $loststcollat=floatval($datah->financialmetrics->loststoragecollateral)/pow(10,24);
    $dlrevenue=floatval($datah->financialmetrics->downloadbandwidthrevenue)/pow(10,24);
    $uprevenue=floatval($datah->financialmetrics->uploadbandwidthrevenue)/pow(10,24);
    $strevenue=floatval($datah->financialmetrics->storagerevenue)/pow(10,24);
    $dlcalls=$datah->networkmetrics->downloadcalls;
    $errcalls=$datah->networkmetrics->errorcalls;
    $renewcalls=$datah->networkmetrics->renewcalls;
} else {
    $siastatus=false;
}

$apihscurl = 'http://localhost:5580/host/estimatescore';
$chhs = curl_init();
curl_setopt($chhs, CURLOPT_USERAGENT, "Hyperspace-Agent");
curl_setopt($chhs, CURLOPT_URL, $apihscurl);
curl_setopt($chhs, CURLOPT_HEADER, false);
curl_setopt($chhs, CURLOPT_RETURNTRANSFER, true);
curl_setopt($chhs, CURLOPT_BINARYTRANSFER, true);
curl_setopt($chhs, CURLOPT_CONNECTTIMEOUT, 5);
$response = curl_exec($chhs);
$httpCode = curl_getinfo($chhs, CURLINFO_HTTP_CODE);
curl_close($chhs);

if ($httpCode == 200) {
    $datahs = json_decode($response);
    $hostrate=$datahs->conversionrate;
}

$apihstcurl = 'http://localhost:5580/host/storage';
$chhst = curl_init();
curl_setopt($chhst, CURLOPT_USERAGENT, "Hyperspace-Agent");
curl_setopt($chhst, CURLOPT_URL, $apihstcurl);
curl_setopt($chhst, CURLOPT_HEADER, false);
curl_setopt($chhst, CURLOPT_RETURNTRANSFER, true);
curl_setopt($chhst, CURLOPT_BINARYTRANSFER, true);
curl_setopt($chhst, CURLOPT_CONNECTTIMEOUT, 5);
$response = curl_exec($chhst);
$httpCode = curl_getinfo($chhst, CURLINFO_HTTP_CODE);
curl_close($chhst);

if ($httpCode == 200) {
    $datahst = json_decode($response);
    $storage=$datahst->folders;
}

?>
