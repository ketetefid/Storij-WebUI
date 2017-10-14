<?php
session_start();
$headers = apache_request_headers();
if ( isset($_SESSION['uname']) and isset($_SESSION['authenticator']) and
     isset($_SESSION['timeout']) and time() - $_SESSION['timeout'] < 1200 and
     isset($headers['auther']) and hash_equals($headers['auther'],$_SESSION['authenticator']) ) {
  
  ///////////////////////////////////////////////////////////////////
  //*********************LoadOSfunc Requests***********************//
  ///////////////////////////////////////////////////////////////////
  
  // Checking for expand
  if (isset($_POST['expandCheckbox'])) {
    $expandval=$_POST['expandCheckbox'];
    if ($expandval==="true") {
      exec("../bin/ChangeExpandYes");
    } else if ($expandval==="false") {
      exec("../bin/ChangeExpandNo");
    }
  }
  // Checking for format_flash
  if (isset($_POST['formatFlash'])) {
    $formatflashval=$_POST['formatFlash'];
    if ($formatflashval==="true") {
      exec("../bin/ChangeFormatFlashYes");
    } else if ($formatflashval==="false") {
      exec("../bin/ChangeFormatFlashNo");
    }
  }
  // Checking for auto_unlock
  if (isset($_POST['autoUnlock'])) {
    $autounlockval=$_POST['autoUnlock'];
    if ($autounlockval==="true") {
      exec("../bin/AutoUnlockYes");
    } else if ($autounlockval==="false") {
      exec("../bin/AutoUnlockNo");
    }
  }
  // Checking for setting wallet password for enabling auto unlock
  if (isset($_POST['autoUnlockpass'])) {
    $autounlockpass=$_POST['autoUnlockpass'];
    exec("sudo ../bin/SetAutoUnlock $autounlockpass");
  }
  // Checking for shutdown request
  if(isset($_POST['shutdown'])){
    exec("sudo /sbin/shutdown -h now");
  }
  // Checking for reboot request 
  if(isset($_POST['reboot'])){
    exec("sudo /sbin/shutdown -r now");
  }
  // Checking for Sia directory
  if(isset($_POST['siadirval'])){
    $siadir = $_POST['siadir'];
    $siadirval = $_POST['siadirval'];
    exec("../bin/SetParams '$siadir' '$siadirval'");
  }
  // Checking for Sia user
  if(isset($_POST['siausrval'])){
    $siausr = $_POST['siausr'];
    $siausrval = $_POST['siausrval'];
    exec("../bin/SetParams '$siausr' '$siausrval'");
    // Creating the user if necessary
    exec("sudo ../bin/CreateUser $siausrval");    
  }
  // Checking for internal IP
  if(isset($_POST['int_ipval'])){
    $int_ip = $_POST['int_ip'];
    $int_ipval = $_POST['int_ipval'];
    exec("../bin/SetParams '$int_ip' '$int_ipval'");
    //exec("sudo ../bin/addipsetter");
  }
  // Checking for starting and stopping requests for Siad
  if (isset($_POST['startSiad'])) {
    $siadrequest=$_POST['startSiad'];
    if ($siadrequest==="true") {
      exec("sudo /etc/init.d/siad start");
    } else if ($siadrequest==="false") {
      exec("sudo /etc/init.d/siad stop");
    }
  }
  // Checking for external IP
  if(isset($_POST['ext_ipval'])){
    $ext_ip = $_POST['ext_ip'];
    $ext_ipval = $_POST['ext_ipval'];
    exec("../bin/SetParams '$ext_ip' '$ext_ipval'");
  }
  // Checking for RPC port
  if(isset($_POST['rpc_portval'])){
    $rpc_port = $_POST['rpc_port'];
    $rpc_portval = $_POST['rpc_portval'];
    exec("../bin/SetParams '$rpc_port' '$rpc_portval'");
  }
  // Checking for host port
  if(isset($_POST['host_portval'])){
    $host_port = $_POST['host_port'];
    $host_portval = $_POST['host_portval'];
    exec("../bin/SetParams '$host_port' '$host_portval'");
  }
  // Checking for domain_name
  if(isset($_POST['domain_nameval'])){
    $domain_name = $_POST['domain_name'];
    $domain_nameval = $_POST['domain_nameval'];
    exec("../bin/SetParams '$domain_name' '$domain_nameval'");
  }
  // Checking for a change to dynamic external IP and submitting FreeDNS values
  if (isset ($_POST['FreeDnsLogin'])) {
    $login=$_POST['FreeDnsLogin'];
    $pass=$_POST['FreeDnsPass'];
    $domain=$_POST['FreeDnsDomain'];
    exec ("sudo ../bin/ConfigFreeDns $login $pass $domain &>/dev/null");
    exec("../bin/SetParams 'ext_ip_type' 'dynamic'");
  }
  // Checking for a change to static external IP
  if (isset ($_POST['ChangeDnsStatic'])) {
    exec("../bin/SetParams 'ext_ip_type' 'static'");
    exec("sudo ../bin/StopFreeDns");
  }
  
  ////////////////////////////////////////////////////////////////////////////
  //*********************LoadSiaTable/Wallet Requests***********************//
  ////////////////////////////////////////////////////////////////////////////
  
  // Checking for unlocking by Auto unlock
  if(isset($_POST['unlockbyAutounlock'])){
    $lockbyautounlock=$_POST['unlockbyAutounlock'];
    if ($lockbyautounlock==="true") {
      exec("sudo /etc/init.d/WalletUnlocker fstart");
    } else if ($lockbyautounlock==="false") {
      exec("sudo /etc/init.d/WalletUnlocker fstop");
    }
  }
  // Checking for generating new address request  
  if (isset($_GET['newaddr'])) {
    $apiwacurl = 'http://localhost:9980/wallet/address';
    $chwa = curl_init();
    curl_setopt($chwa, CURLOPT_USERAGENT, "Sia-Agent");
    curl_setopt($chwa, CURLOPT_URL, $apiwacurl);
    curl_setopt($chwa, CURLOPT_HEADER, false);
    curl_setopt($chwa, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($chwa, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($chwa, CURLOPT_CONNECTTIMEOUT, 5);
    $reswa = curl_exec($chwa);
    $httpCodewa = curl_getinfo($chwa, CURLINFO_HTTP_CODE);
    curl_close($chwa);
    if ($httpCodewa == 200) {
      $datawa = json_decode($reswa);
      $NewSCAddress=$datawa->address;
      echo $NewSCAddress;
    }
  }
  // Checking for inquiry about wallet lock status
  if (isset($_POST['isWUnlocked'])) {
    $apiwscurl = 'http://localhost:9980/wallet';
    $chws = curl_init();
    curl_setopt($chws, CURLOPT_USERAGENT, "Sia-Agent");
    curl_setopt($chws, CURLOPT_URL, $apiwscurl);
    curl_setopt($chws, CURLOPT_HEADER, false);
    curl_setopt($chws, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($chws, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($chws, CURLOPT_CONNECTTIMEOUT, 5);
    $resws = curl_exec($chws);
    $httpCodews = curl_getinfo($chws, CURLINFO_HTTP_CODE);
    curl_close($chws);
    if ($httpCodews == 200) {
      $dataws = json_decode($resws);
      $isUnlocked=$dataws->unlocked;
      if ($isUnlocked) {
	echo "yes";
      } else {
	echo "No";
      }
    }
  }
  // Checking for request for SC address validation
  if (isset($_POST['isScValid'])) {
    $destScAddr = $_POST['destScAddr'];
    $apiwscurl = 'http://localhost:9980/wallet/verify/address/'.$destScAddr;
    $chws = curl_init();
    curl_setopt($chws, CURLOPT_USERAGENT, "Sia-Agent");
    curl_setopt($chws, CURLOPT_URL, $apiwscurl);
    curl_setopt($chws, CURLOPT_HEADER, false);
    curl_setopt($chws, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($chws, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($chws, CURLOPT_CONNECTTIMEOUT, 5);
    $resws = curl_exec($chws);
    $httpCodews = curl_getinfo($chws, CURLINFO_HTTP_CODE);
    curl_close($chws);
    if ($httpCodews == 200) {
      $dataws = json_decode($resws);
      $isScValid=$dataws->valid;
      if ($isScValid) {
	echo "1";
      } else {
	echo "0";
      }
    }
  }
  // Checking for initing a wallet through an new seed
  if (isset($_POST['newSeed'])) {
    $apiwacurl = 'http://localhost:9980/wallet/init';
    $chwa = curl_init();
    curl_setopt($chwa, CURLOPT_USERAGENT, "Sia-Agent");
    curl_setopt($chwa, CURLOPT_URL, $apiwacurl);
    curl_setopt($chwa, CURLOPT_HEADER, false);
    curl_setopt($chwa, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($chwa, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($chwa, CURLOPT_CONNECTTIMEOUT, 5);
    
    $wpass=$_POST['wPass'];
    $PostData=[ 'encryptionpassword' => $wpass ];
    curl_setopt($chwa, CURLOPT_POSTFIELDS, $PostData);
    
    $reswa = curl_exec($chwa);
    $httpCodewa = curl_getinfo($chwa, CURLINFO_HTTP_CODE);
    curl_close($chwa);
    if ($httpCodewa == 200) {
      $datawa = json_decode($reswa);
      $NewSeed=$datawa->primaryseed;
      echo $NewSeed;
    }
  }
  // Checking for initing a wallet through an existing seed
  if (isset($_POST['existingSeed'])) {
    $apiwacurl = 'http://localhost:9980/wallet/init/seed';
    $chwa = curl_init();
    curl_setopt($chwa, CURLOPT_USERAGENT, "Sia-Agent");
    curl_setopt($chwa, CURLOPT_URL, $apiwacurl);
    curl_setopt($chwa, CURLOPT_HEADER, false);
    curl_setopt($chwa, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($chwa, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($chwa, CURLOPT_CONNECTTIMEOUT, 5);
    
    $wpass=$_POST['wPass'];
    $ExistingSeed=$_POST['existingSeed'];
    $PostData=[ 'encryptionpassword' => $wpass, 'seed' => $ExistingSeed ];
    curl_setopt($chwa, CURLOPT_POSTFIELDS, $PostData);
    
    $reswa = curl_exec($chwa);
    $httpCodewa = curl_getinfo($chwa, CURLINFO_HTTP_CODE);
    curl_close($chwa);
    if ($httpCodewa == 200) {
      echo "OK";
    }
  }
  // Checking for sending SC request
  if (isset($_POST['destScAddr']) and isset($_POST['ScAmount'])) {
    $apiwacurl = 'http://localhost:9980/wallet/siacoins';
    $chwa = curl_init();
    curl_setopt($chwa, CURLOPT_USERAGENT, "Sia-Agent");
    curl_setopt($chwa, CURLOPT_URL, $apiwacurl);
    curl_setopt($chwa, CURLOPT_HEADER, false);
    curl_setopt($chwa, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($chwa, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($chwa, CURLOPT_CONNECTTIMEOUT, 5);
    
    $destScAddr=$_POST['destScAddr'];
    $ScAmount=$_POST['ScAmount'];
    $ScAmountH=strval(bcmul(strval($ScAmount),'1000000000000000000000000'));
    $PostData=[ 'amount' => $ScAmountH, 'destination' => $destScAddr ];
    curl_setopt($chwa, CURLOPT_POSTFIELDS, $PostData);
    
    $reswa = curl_exec($chwa);
    $httpCodewa = curl_getinfo($chwa, CURLINFO_HTTP_CODE);
    curl_close($chwa);
    
    //echo $reswa;
    $txids=json_decode($reswa);
    $apiresponse=[];
    if ($httpCodewa==200) {
      $apiresponse=[1,$txids->transactionids[0],$txids->transactionids[1]];
    } else {
      $apiresponse=[0,$txids->message];
    }
    echo json_encode($apiresponse);
  }
  // Checking for locking through the API
  if (isset($_POST['lockWalletNoAuto'])) {
    $apiwacurl = 'http://localhost:9980/wallet/lock';
    $chwa = curl_init();
    curl_setopt($chwa, CURLOPT_USERAGENT, "Sia-Agent");
    curl_setopt($chwa, CURLOPT_URL, $apiwacurl);
    curl_setopt($chwa, CURLOPT_HEADER, false);
    curl_setopt($chwa, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($chwa, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($chwa, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($chwa, CURLOPT_POST, true);
    
    $reswa = curl_exec($chwa);
    $httpCodewa = curl_getinfo($chwa, CURLINFO_HTTP_CODE);
    curl_close($chwa);
    if ($httpCodewa == 200) {
      echo "OK";
    }
  }
  // Checking for unlocking through the API
  if (isset($_POST['unlockWalletNoAuto'])) {
    $apiwacurl = 'http://localhost:9980/wallet/unlock';
    $chwa = curl_init();
    curl_setopt($chwa, CURLOPT_USERAGENT, "Sia-Agent");
    curl_setopt($chwa, CURLOPT_URL, $apiwacurl);
    curl_setopt($chwa, CURLOPT_HEADER, false);
    curl_setopt($chwa, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($chwa, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($chwa, CURLOPT_CONNECTTIMEOUT, 5);
    
    $wpass=$_POST['unlockWalletNoAuto'];
    $PostData=[ 'encryptionpassword' => $wpass ];
    curl_setopt($chwa, CURLOPT_POSTFIELDS, $PostData);
    
    $reswa = curl_exec($chwa);
    $httpCodewa = curl_getinfo($chwa, CURLINFO_HTTP_CODE);
    curl_close($chwa);
    if ($httpCodewa == 200) {
      echo "OK";
    }
  }
  /////////////////////////////////////////////////////////////////
  //*********************Hosting Requests************************//
  /////////////////////////////////////////////////////////////////
  
  // Checking for the request of creating partition list and format list
  // jquery does NOT send empty arrays to the ajax call. So we create them if they were not sent.
  if (isset ($_POST ['partList']) || isset ($_POST ['devFormatList'])) {
    if (!isset ($_POST ['partList'])) {
      $partList=array();
    } else {
      $partList=$_POST['partList'];
    }
    if (!isset ($_POST ['devFormatList'])) {
      $devFormatList=array();
    } else {
      $devFormatList=$_POST['devFormatList'];
    }
    $FinalPartList=$partList;
    // Match if the partList has values mentioned in devFormatList and deleting them.
    foreach ($devFormatList as $value) {
      $temp=preg_grep("/$value/",$FinalPartList,PREG_GREP_INVERT);
      $FinalPartList=$temp;
    }
    // An array for holding all of the host point paths.
    $HostList=array();
    
    $kp=0;
    foreach ($devFormatList as $dev) {
      $output=shell_exec("sudo ../bin/formatter $dev");
      $HostList[$kp]=$output;
      $kp++;
    }
    foreach ($FinalPartList as $part) {
      // For getting a good array response, shell_exec should be used.
      //exec("sudo ../bin/addpart $part 2>&1",$output);
      $output=shell_exec("sudo ../bin/addpart $part");
      $HostList[$kp]=$output;
      $kp++;
    }
    // Writing the hostpoints to a file for later retrieval. We write to /boot as it is owned by apache.
    $file='/boot/HostPoints.txt';
    file_put_contents ($file,$HostList);
    // Jsonizing the hostlist for easy manipulating in JS side.
    echo json_encode ($HostList);
  }
  
  // Checking for the request of setting storage for hosting
  if (isset($_POST['storageSizes'])){
    // Getting the storage
    $storageSizes=$_POST['storageSizes'];
    $hosts=file('/boot/HostPoints.txt');
    
    // Check if the storage path from the file was already set in the software -> user wants to resize.
    // Check if the storage path from the file was not set in the software -> user wants to add new storage.
    $apihstcurl = 'http://localhost:9980/host/storage';
    $chhst = curl_init();
    curl_setopt($chhst, CURLOPT_USERAGENT, "Sia-Agent");
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
      $storagefolders=$datahst->folders;
    }
    
    foreach ($storageSizes as $indx => $val) {
      if ($val>0) {
	// The user is adding new storage or resizing it.
	$apihscurl = 'http://localhost:9980/host/storage/folders/add';
	$hostpath=trim($hosts[$indx]);
	// It must be dividable by 256MiB
	$hostsize=floor(floor($val*1024/256)*256*1024*1024);
	// If the storage size is less than 256MiB and still positive, change it to 256MiB.
	if ($hostsize<268435456) {
	  $hostsize=268435456;
	}
	$storageApiData=[ 'path' => $hostpath, 'size' => $hostsize ];
	// Did the storage we want to add exist in the API host get call?
	// If true, then it is a resizing rather than adding.
	foreach ($storagefolders as $storagefolder) {
	  if (strcmp($hostpath,$storagefolder->path)==0) {
	    // We change the api url and also change one the post fields from size to newsize.
	    $apihscurl = 'http://localhost:9980/host/storage/folders/resize';
	    $storageApiData=[ 'path' => $hostpath, 'newsize' => $hostsize ];
	    break 1;
	  }
	}
	$chsh = curl_init();
	curl_setopt($chsh, CURLOPT_USERAGENT, "Sia-Agent");
	curl_setopt($chsh, CURLOPT_URL, $apihscurl);
	curl_setopt($chsh, CURLOPT_HEADER, false);
	curl_setopt($chsh, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($chsh, CURLOPT_BINARYTRANSFER, true);
	curl_setopt($chsh, CURLOPT_CONNECTTIMEOUT, 5);
	
	curl_setopt($chsh, CURLOPT_POSTFIELDS, $storageApiData);
	
	$ressh = curl_exec($chsh);
	$httpCodesh = curl_getinfo($chsh, CURLINFO_HTTP_CODE);
	curl_close($chsh);
	echo $httpCodesh;
      } else {
	// The user has requested us to remove the storage.
	// We check that if the requested storage for removal is actually a configured storage.
	// If not, we do not need to do anything.
	$hostpath=trim($hosts[$indx]);
	foreach ($storagefolders as $storagefolder) {
	  if (strcmp($hostpath,$storagefolder->path)==0) {
	    $apihscurl = 'http://localhost:9980/host/storage/folders/remove';
	    $storageApiData=[ 'path' => $hostpath, 'force' => true ];
	    $chsh = curl_init();
	    curl_setopt($chsh, CURLOPT_USERAGENT, "Sia-Agent");
	    curl_setopt($chsh, CURLOPT_URL, $apihscurl);
	    curl_setopt($chsh, CURLOPT_HEADER, false);
	    curl_setopt($chsh, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($chsh, CURLOPT_BINARYTRANSFER, true);
	    curl_setopt($chsh, CURLOPT_CONNECTTIMEOUT, 5);
	    
	    curl_setopt($chsh, CURLOPT_POSTFIELDS, $storageApiData);
	    
	    $ressh = curl_exec($chsh);
	    $httpCodesh = curl_getinfo($chsh, CURLINFO_HTTP_CODE);
	    curl_close($chsh);
	    echo $httpCodesh;
	    break 1;
	  }
	}
      }
    }
  }
  // Checking for the request of setting hosting parameters
  if (isset($_POST['SetSettings'])) {
    // hconfigdata will already have the array to be sent to the API call.
    $hconfigdata=json_decode($_POST['hconfig_json'],true);
    // Change SC values to hastings for 3 needed parameters
    if (isset($hconfigdata['collateralbudget'])) {
      $collateralbudget=strval(bcmul(strval($hconfigdata['collateralbudget']),'1000000000000000000000000'));
      $hconfigdata['collateralbudget']=$collateralbudget;
    }
    if (isset($hconfigdata['maxcollateral'])) {
      $maxcollat=strval(bcmul(strval($hconfigdata['maxcollateral']),'1000000000000000000000000'));
      $hconfigdata['maxcollateral']=$maxcollat;
    }
    if (isset($hconfigdata['mincontractprice'])) {
      $mincontprice=strval(bcmul(strval($hconfigdata['mincontractprice']),'1000000000000000000000000'));
      $hconfigdata['mincontractprice']=$mincontprice;
    }
    
    $apiwacurl = 'http://localhost:9980/host';
    $chwa = curl_init();
    curl_setopt($chwa, CURLOPT_USERAGENT, "Sia-Agent");
    curl_setopt($chwa, CURLOPT_URL, $apiwacurl);
    curl_setopt($chwa, CURLOPT_HEADER, false);
    curl_setopt($chwa, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($chwa, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($chwa, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($chwa, CURLOPT_POSTFIELDS, $hconfigdata);
    
    $reswa = curl_exec($chwa);
    $httpCodewa = curl_getinfo($chwa, CURLINFO_HTTP_CODE);
    curl_close($chwa);
    echo $httpCodewa;
  }
  // Checking for announcing request
  if (isset($_POST['announcing'])) {
    /*
      $apihacurl = 'http://localhost:9980/host/announce';
      $chha = curl_init();
      curl_setopt($chha, CURLOPT_USERAGENT, "Sia-Agent");
      curl_setopt($chha, CURLOPT_URL, $apihacurl);
      curl_setopt($chha, CURLOPT_HEADER, false);
      curl_setopt($chha, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($chha, CURLOPT_BINARYTRANSFER, true);
      curl_setopt($chha, CURLOPT_CONNECTTIMEOUT, 5);
      $netaddress=$_POST['hostaddress'];
      $hconfigdata=[ 'netaddress' => $netaddress ];
      curl_setopt($chha, CURLOPT_POSTFIELDS, $hconfigdata);
      
      $resha = curl_exec($chha);
      $httpCodeha = curl_getinfo($chha, CURLINFO_HTTP_CODE);
      curl_close($chha);
      echo $httpCodeha;
    */
  }
  // Checking for retiring request
  if (isset($_POST['retiring'])) {
    $apiwacurl = 'http://localhost:9980/host';
    $chwa = curl_init();
    curl_setopt($chwa, CURLOPT_USERAGENT, "Sia-Agent");
    curl_setopt($chwa, CURLOPT_URL, $apiwacurl);
    curl_setopt($chwa, CURLOPT_HEADER, false);
    curl_setopt($chwa, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($chwa, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($chwa, CURLOPT_CONNECTTIMEOUT, 5);
    $hconfigdata=[ 'acceptingcontracts' => false ];
    curl_setopt($chwa, CURLOPT_POSTFIELDS, $hconfigdata);
    
    $reswa = curl_exec($chwa);
    $httpCodewa = curl_getinfo($chwa, CURLINFO_HTTP_CODE);
    curl_close($chwa);
    echo $httpCodewa;
  }
}
?>