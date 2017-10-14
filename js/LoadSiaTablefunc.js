// A check variable to indicate the modal shown belongs to wallet unlocking.
var isItUnlockWalletModal=false;
$(document).ready(function(){
    $("#unlockWallet").on('change', function() {
	var isAutoUnlock=$("#AutoUnlockCheckbox").is(':checked');
	var isChecked = $(this).is(':checked');
	// If AutoUnlock is enabled, we will go through a normal /etc/init.d/WalletUnlocker start/stop command.
	if (isAutoUnlock) {
	    if(isChecked) {
		$.ajax({
		    data : { unlockbyAutounlock : "true" },
		    type : "POST",
		    cache : false,
		    headers : { 'auther': $('meta[name="auther"]').attr('content') },
		    url : 'js/SetParams.php'
		});
	    } else {
		$.ajax({
		    data : { unlockbyAutounlock : "false" },
		    type : "POST",
		    cache : false,
		    headers : { 'auther': $('meta[name="auther"]').attr('content') },
                    url : 'js/SetParams.php'
		});
	    }
	    // Else we have to bring up a modal window, asking for password.
	    // Then set the API call for unlocking bound to a the button inside the modal.
	} else {
	    if (isChecked) {
		// For unlocking we first make a modal window and get the password.

		if ($("#checkIfInitialized").val()==1) {

		    // The modal you see belongs to wallet unlocking.
		    isItUnlockWalletModal=true;
		    $("#initWalletModal").show();
		    $("#walletModalHeaderTitle").html("Unlock The Wallet");
		    $("#initWalletFooter").html('<input type="submit" value="Unlock" id="unlockWalletButton" class="OkCancelButton">');
		    $("#initWalletInfo").html('<p>Enter your wallet password:</p><input type="password" class="SCvalue" value="" id="walletPass4unlocking" title="Enter your current wallet password here.">');
		} else {
		    // Error if the wallet is not initialized yet.
		    $("#unlockWallet").prop("checked",false);
		    alert("Your wallet is not initialized yet.");
		}
	    } else {
		// For locking, we directly lock it here.
		var lockWalletNoAuto="";
		$.ajax({
		    url : 'js/SetParams.php',
		    type : "POST",
		    cache : false,
		    data : {lockWalletNoAuto : lockWalletNoAuto},
		    headers : { 'auther': $('meta[name="auther"]').attr('content') }
		});
	    }
	}
    });
    // We bind the unlocking task to the newly created button and then we set an API call.
    $("#initWalletFooter").on('click','#unlockWalletButton',function(){
	var unlockWalletNoAuto=$("#walletPass4unlocking").val();
	// We hide the modal now, as the first time unlocking takes time and putting this inside the success function
	// would make it seem it is stuck.
	$("#initWalletModal").hide();
	$.ajax({
	    url : 'js/SetParams.php',
	    type : "POST",
	    cache : false,
	    data : {unlockWalletNoAuto : unlockWalletNoAuto},
	    headers : { 'auther': $('meta[name="auther"]').attr('content') },
	    success : function (result) {
		// Wait for 1 sec and reload LoadSiaWallet to make the page process and show the addresses.
		setTimeout(function(){
		    $("#SiaWallet").load("LoadSiaWallet.html");
		},1000);
	    }
	});
    });
});

$(document).ready(function(){
    // A variable for checking if we want to init the wallet by a new seed or an existing one.
    var byNewSeed;
    $("#initWithNew").click(function(){
	// We want to init by a new seed. We will append a password field. 
	byNewSeed=true;
	$("#initWalletModal").show();
	$("#initWalletInfo").html('<p>Enter your desired password for the wallet:</p><input type="password" class="SCvalue" value="" id="walletPass" title="Enter your future wallet password here.">');
    });

    $("#initWithExisting").click(function(){
	// We want to init by an existing seed. We will append a password filed and a text area for entering the seed.
	byNewSeed=false;
	$("#initWalletModal").show();
	$("#initWalletInfo").html('<p>Enter your desired password for the wallet:</p><input type="password" class="SCvalue" value="" id="walletPass" title="Enter your future wallet password here.">');
	$("#initWalletInfo").append("<p style='margin-top:20px;'> Enter your seed. Make sure that you have entered it correctly.</p>");
	// Calculating the best width of the text area based on the screen/parent width
	var optwidth=$("#initWalletInfo").width()-40;
	$("#initWalletInfo").append("<textarea spellcheck='false' id='existingSeed' rows='5' style='width:"+optwidth+"px;'></textarea>");

    });
    // The final OK click function
    $("#initWalletFooter").on('click','#OkGotSeed',function(){ 
	$("#initWalletModal").hide();
    });
    // The upper right close button
    $("#closeInitWalletInfo").click(function(){
	// If the modal was for wallet unlocking, we quickly uncheck the unlock checkbox as the user canceled it.
	if (isItUnlockWalletModal) {
	    $("#unlockWallet").prop( "checked", false );
	}
	$("#initWalletModal").hide();
    });
    
    $("#initTheWallet").click(function(){
	if ($.trim($("#walletPass").val())=="") {
	    alert("Please set a proper password for your wallet.");
	    // The user clicked on initialize button. This is by a new seed. We will append a text area containing the new seed.
	} else if (byNewSeed) {
	    var newSeed="";
	    var wPass=$("#walletPass").val();
	    $.ajax ({
		url : "js/SetParams.php",
		cache : false,
		type : "POST",
		data : { newSeed : newSeed, wPass : wPass},
		headers : { 'auther': $('meta[name="auther"]').attr('content') },
		success: function (theSeed) {
		    var optwidth=$("#initWalletInfo").width()-40;
		    $("#initWalletInfo").html("<textarea id='newSeed' spellcheck='false' rows='5' style='width:"+optwidth+"px;' readonly></textarea>");
		    $("#newSeed").val(theSeed);
		    $("#newSeed").select();
		    $("#initWalletInfo").append("<p style='margin-bottom:0px;'>This is your new seed. Make sure that you have recorded it in a safe place.</p>");
		    $("#initWalletFooter").html('<input type="button" value="OK" id="OkGotSeed" class="OkCancelButton">');
		}
	    });
	} else {
	    // The user clicked on initialize and this is by an existing seed. So we will just print some information.
	    var wPass=$.trim($("#walletPass").val());
	    var existingSeed=$.trim($("#existingSeed").val());
	    if (existingSeed=="") {
		alert("Please enter your seed.");
	    } else {
		$("#initWalletInfo").html("<p>Wallet scanning has been started. Please note that this will take some time to complete.</p>");
		$("#initWalletFooter").html('<input type="button" value="OK" id="OkGotSeed" class="OkCancelButton">');
		$.ajax ({
		    url : "js/SetParams.php",
		    cache : false,
		    type : "POST",
		    data : { existingSeed : existingSeed, wPass : wPass},
		    headers : { 'auther': $('meta[name="auther"]').attr('content') }
		});
	    }
	}
    });
});

$(document).ready(function(){
    var siadinfo=$.Deferred();
    var siadinfo2=$.Deferred();
    $("#runsiadsubmit").click(function(){
	$.ajax({
	    type : "POST",
	    data: { startSiad : "true" },
	    url: "js/SetParams.php",
	    cache : false,
	    headers : { 'auther': $('meta[name="auther"]').attr('content') }
	});
	//$('#SiaTable').load('WaitForSiad.html');
	$('#SiaTable').html('<table id="wait4sia" class="t1"><tr><td><div class="HostConfigItems" style="justify-content:center">Please wait until Sia dameon have started...<i class="fa fa-cog fa-spin fa-2x fa-fw"></i></div></td></tr></table>').show();
	// Make the WaitForSiad stay on page for 10 seconds and then reload the whole page.
	setTimeout(function(){
	    siadinfo.resolve(true);
	},10000);
    });
    $.when(siadinfo).done(function(v1){
	// We first load the SiaTable once to get the session variables ready for the rest.
	$("#SiaTable").load("LoadSiaTable.html");
	siadinfo2.resolve(true);
    });
    $.when(siadinfo2).done(function(v1){
	// Then we reload.
	window.location.reload();
    });
});

$(function(){
    $("#SyncStatus").click(function(){
	if ($("#SyncStatusCheck").val()==1 || sessionStorage.syncType==3) {
	    $("#SyncStatus").html("Not Yet :(");
	    $("#SyncStatusCheck").val(2);
	    sessionStorage.syncType=1;
	} else if ($("#SyncStatusCheck").val()==2 || sessionStorage.syncType==1) {
	    $("#SyncStatus").html($("#SyncStatusCheckVal2").val()+"%");
	    $("#SyncStatusCheck").val(3);
	    sessionStorage.syncType=2;
	} else if ($("#SyncStatusCheck").val()==3 || sessionStorage.syncType==2) {
	    $("#SyncStatus").html($("#SyncStatusCheckVal3").val()+" remaining blocks");
	    $("#SyncStatusCheck").val(1);
	    sessionStorage.syncType=3;
	}
    });
});
