$(document).ready(function(){
    $("#AddressList").change(function() {
	var addr=$(this).val();
	$("#scAdress").val(addr);
	$("#scAdress").select();
	document.execCommand("copy");
	$( "#AddrMessage" ).text( "The address was copied to clipboard." ).show().fadeOut( 8000 );
    });
});
var newSCready=$.Deferred();
$(document).ready(function(){
    $("#SCGenButton").one("mousedown", function(){
	// The date passed to ajax needs to be declared and defined.
	var isWUnlocked=0;
	$.ajax({
	    url: "js/SetParams.php",
	    cache : false,
	    type: "POST",
	    headers : { 'auther': $('meta[name="auther"]').attr('content') },
	    data: { isWUnlocked : isWUnlocked },
	    // The status of the wallet is stored from SetParams.php into unlockData by echoing.
	    success: function (unlockData) {
		if (unlockData!=="yes") {
		    alert("Your wallet is locked. Please unlock it first to be able to generate an address.");
		} else {
		    // Here we know that we can generate a new address.
		    // Make a defined variable to be passed to the ajax call.
		    var newaddr=0;
		    $.ajax({
			url: "js/SetParams.php",
			cache : false,
			headers : { 'auther': $('meta[name="auther"]').attr('content') },
			data: { newaddr : newaddr },
			success: function (data) {
			    // We put the result into #NewSCAddr.
			    $("#NewSCAddr").val(data);
			    wait4NewSc();
			    $.when(newSCready).done(function(){
				$("#NewSCAddr").select().focus();
				$("#AddrMessage2").text( "A new address was generated and you can copy it now." ).show().fadeOut( 12000 );
			    });
			}
		    });
		}
	    }
	});
    });
});

// A function to wait until #NewSCAddr has been populated with the new address.
var scChanged=false;
function wait4NewSc() {
    if ($("#NewSCAddr").val()!=="") {
	scChanged=true;
	newSCready.resolve(true);
    }
    if (!scChanged) {
	setTimeout(wait4NewSc, 10);
    }
}

$(function(){
    $("#sendSCsubmit").click(function(){
	var isWUnlocked="No";
	$.ajax({
	    url: "js/SetParams.php",
	    cache : false,
	    type: "POST",
	    headers : { 'auther': $('meta[name="auther"]').attr('content') },
	    data: { isWUnlocked : isWUnlocked },
	    success: function (unlockData) {
		if (unlockData!=="yes") {
		    alert("Your wallet is locked. Please unlock it first to be able to send coins.");
		} else {
		    var destScAddr=$("#receiveraddress").val();
		    var ScAmount=$("#amountSC").val();
		    if (destScAddr=="") {
			alert("The SC address is empty.");
		    } else {
			var isScValid=1;
			$.ajax({
			    url: "js/SetParams.php",
			    cache : false,
			    type: "POST",
			    headers : { 'auther': $('meta[name="auther"]').attr('content') },
			    data: { isScValid : isScValid, destScAddr : destScAddr },
			    success: function (data) {
				if (data!=="1") {
				    alert("The SC address you have entered is invalid.");
				} else {
				    if ( $.isNumeric(ScAmount) && ScAmount>0 ){
					$("#SendSCModal").show();
					$("#SendSCInfo").html("<p>Are you sure that you want to send <b style='color:brown;'>"+ScAmount+" SC</b> to this address?</p><span style='color:purple;font-size:15px;'>" + destScAddr+"</span>");
				    }
				    else {
					alert("Please enter a proper value for the SC amount.");
				    }
				}
			    }
			});
		    }
		}
	    }
	});
    });

    $("#closeSendSC").click(function(){
	$("#SendSCModal").css("display", "none");
    });

    $("#SendSCFooter").on("click","#CancelsendSCsubmit",function(){
	$("#SendSCModal").hide();
    });

    $("#SendSCFooter").on("click","#OKsendSCsubmit",function(){
	$("#SendSCModal").hide();
	var destScAddr=$("#receiveraddress").val();
	var ScAmount=$("#amountSC").val();
	$.ajax({
	    url : 'js/SetParams.php',
	    cache : false ,
	    type : 'POST',
	    dataType : 'JSON',
	    headers : { 'auther': $('meta[name="auther"]').attr('content') },
	    data : { destScAddr : destScAddr , ScAmount : ScAmount },
	    success : function (result) {
		if (result[0]==1) {
		    $("#SendSCmessage").html('<p style="color:purple">Success! Your transaction was done with these hash IDs:</p><p>'+result[1]+'\n'+result[2]+'</p>').show();
		} else {
		    $("#SendSCmessage").html('<p style="color:red;">Error!</p><p>'+result[1]+'</p>').show();
		}
	    }
	});
    });
    
});

