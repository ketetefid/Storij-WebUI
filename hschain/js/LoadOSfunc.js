$(document).ready(function(){
    $("#ExpandCheckbox").on('change', function() {
	var isChecked = $(this).is(':checked');
	if(isChecked) {
	    $.ajax({
		url : 'js/SetParams.php',
		cache : false,
		type : 'POST',
		data : { expandCheckbox : "true" },
		headers : { 'auther': $('meta[name="auther"]').attr('content') }
            });
	} else {
	    $.ajax({
                url : 'js/SetParams.php',
		cache : false,
		type : 'POST',
		data : { expandCheckbox : "false" },
		headers : { 'auther': $('meta[name="auther"]').attr('content') }
            });
	}
    });
});

$(document).ready(function(){
    $("#FormatFlashCheckbox").on('change', function() {
        var isChecked = $(this).is(':checked');
        if(isChecked) {
	    // First check if the user has selected only one drive
	    var driveChecked = parseInt($("#SetupDriveSelect input:checkbox:checked").length);
	    if (driveChecked < 1) {
		$("#FormatFlashCheckbox").prop("checked",false);
		alert("You must select a drive first.");
	    } else if (driveChecked > 1) {
		$("#FormatFlashCheckbox").prop("checked",false);
		alert("Only and only one drive must be selected.");
	    } else {
		// Bring up a model window asking for confirmation.
		$("#FormatFlashModal").show();
	    }
        } else {
            $.ajax({
                url : 'js/SetParams.php',
		cache : false,
		type : 'POST',
		data : { formatFlash : "false" },
		headers : { 'auther': $('meta[name="auther"]').attr('content') }
            });
        }
    });
    
    $("#closeFormatFlash").click(function(){
	$("#FormatFlashCheckbox").prop("checked",false);
	$("#FormatFlashModal").hide();
    });
    
    $("#FormatFlashFooter").on("click","#CancelFormatFlash", function(){
	$("#FormatFlashCheckbox").prop("checked",false);
	$("#FormatFlashModal").hide();
    });
    
    $("#FormatFlashFooter").on("click","#ConfirmFormatFlash", function(){
	var bootStrapType=$('input[name=BootStrapOption]:checked', '#BootStrapForm').val();
	var devname=$("#SetupDriveSelect input:checkbox:checked").attr("id");
	$("#FormatFlashModal").hide();
        $.ajax({
            url : 'js/SetParams.php',
	    cache : false,
	    type : 'POST',
	    data : { formatFlash : "true" , setupDev : devname , bootStrapType : bootStrapType },
	    headers : { 'auther': $('meta[name="auther"]').attr('content') },
	    success : function() {
		$("#DynInfoMessage").html('Reboot your device now to setup HyperSpace on the attached drive.').show();
	    }
        });
    });				 
});
$(document).ready(function(){
    $("#siadirsubmit").click(function(e){
	var siadirval=$("#siadir").val();
	if (/^[a-zA-Z0-9\-_/]*$/.test(siadirval) && /^\//.test(siadirval)) {
	    $.ajax({
		url: "js/SetParams.php",
		type: "POST",
		cache : false,
		headers : { 'auther': $('meta[name="auther"]').attr('content') },
		data: { siadir : "XSCDIR", siadirval : siadirval}
	    });
	} else {
	    e.preventDefault();
	    alert("Your path is invalid or has disallowed characters. Only letters are allowed here.");
	}
    });
});

$(document).ready(function(){
    $("#siausrsubmit").click(function(e){
        var siausrval=$("#siausr").val();
	if (!/^\d/.test(siausrval) && /^[a-z0-9]*$/.test(siausrval)) {
            $.ajax({
		url: "js/SetParams.php",
		type: "POST",
		cache : false,
		headers : { 'auther': $('meta[name="auther"]').attr('content') },
		data: { siausr : "SIAUSR" , siausrval : siausrval},
		success : function () {
		    alert("Through the WebShell, set a password for the user, if it is new.");
		}
            });
	} else {
	    e.preventDefault();
	    alert("The chosen user name is disallowed.");
	}
    });
});

$(document).ready(function(){
    $("#hostnamesubmit").click(function(e){
        var hostnameval=$("#hostname").val();
	if (!/^\d/.test(hostnameval) && /^[a-z0-9]*$/.test(hostnameval)) {
	    e.preventDefault();
            $.ajax({
		url: "js/SetParams.php",
		type: "POST",
		cache : false,
		headers : { 'auther': $('meta[name="auther"]').attr('content') },
		data: { hostname : hostnameval},
		success : function() {
		    $("#DynInfoMessage").html('Reboot your device now to enforce the new hostname.').show();
	    }
            });
	} else {
	    e.preventDefault();
	    alert("The chosen host name is disallowed.");
	}
    });
});

$(document).ready(function(){
    $("#int_ipsubmit").click(function(e){
        var int_ipval=$("#int_ip").val();
	if (/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(int_ipval) || /^auto/.test(int_ipval)) {
            $.ajax({
		url: "js/SetParams.php",
		type: "POST",
		cache : false,
		headers : { 'auther': $('meta[name="auther"]').attr('content') },
		data: { int_ip : "int_ip" , int_ipval : int_ipval}
            });
	} else {
	    e.preventDefault();
	    alert("Please enter a valid IP address or set it to auto.");
	}
    });
});

$(document).ready(function(){
    $("#ext_ipsubmit").click(function(e){
        var ext_ipval=$("#ext_ip").val();
	if (/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(ext_ipval)) {
            $.ajax({
		url: "js/SetParams.php",
		type: "POST",
		cache : false,
		headers : { 'auther': $('meta[name="auther"]').attr('content') },
		data: { ext_ip : "ext_ip" , ext_ipval : ext_ipval}
            });
	} else {
	    e.preventDefault();
	    alert("Please enter a valid IP address.");
	}
    });
});

$(document).ready(function(){
    $("#rpc_portsubmit").click(function(e){
        var rpc_portval=$("#rpc_port").val();
	if (/^[0-9]*$/.test(rpc_portval) && !/^0/.test(rpc_portval)) {
            $.ajax({
		url: "js/SetParams.php",
		type: "POST",
		cache : false,
		headers : { 'auther': $('meta[name="auther"]').attr('content') },
		data: { rpc_port : "xsc_rpc_port" , rpc_portval : rpc_portval}
            });
	} else {
	    e.preventDefault();
	    alert("Please enter a proper numeric value for RPC port.");
	}
    });
});

$(document).ready(function(){
    $("#host_portsubmit").click(function(e){
        var host_portval=$("#host_port").val();
	if (/^[0-9]*$/.test(host_portval) && !/^0/.test(host_portval)) {
            $.ajax({
		url: "js/SetParams.php",
		type: "POST",
		cache : false,
		headers : { 'auther': $('meta[name="auther"]').attr('content') },
		data: { host_port : "xsc_host_port" , host_portval : host_portval}
            });
	} else {
	    e.preventDefault();
	    alert("Please enter a proper numeric value for host port.");
	}
    });
});

$(document).ready(function(){
    $("#disk_layoutsubmit").click(function(e){
        var disk_layoutval=$("#disk_layout").val();
	if (/^msdos$/.test(disk_layoutval) || /^gpt$/.test(disk_layoutval)) {
            $.ajax({
		url: "js/SetParams.php",
		type: "POST",
		cache : false,
		headers : { 'auther': $('meta[name="auther"]').attr('content') },
		data: { disk_layout : "disklayout" , disk_layoutval : disk_layoutval}
            });
	} else {
	    e.preventDefault();
	    alert("Please enter either gpt or msdos.");
	}
    });
});

$(document).ready(function(){
    $("#domain_namesubmit").click(function(){
        var domain_nameval=$("#domain_name").val();
        $.ajax({
            url: "js/SetParams.php",
            type: "POST",
            cache : false,
	    headers : { 'auther': $('meta[name="auther"]').attr('content') },
            data: { domain_name : "domain_name" , domain_nameval : domain_nameval}
        });
    });
});

$(document).ready(function(){
    $("#AutoUnlockCheckbox").on('change', function() {
	var isChecked = $(this).is(':checked');
	if(isChecked) {
	    // Show a modal for setting the password and authentication in /usr/local/bin/unlocker.
	    $("#AutoUnlockModal").show();
	} else {
	    $.ajax({
                url : 'js/SetParams.php',
		cache : false,
		type : 'POST',
		data : { autoUnlock : "false" },
		headers : { 'auther': $('meta[name="auther"]').attr('content') }
            });
	}
    });

    $("#closeAutoUnlock").click(function(){
	// Uncheck the button if we closed the auto onlock modal.
	$("#AutoUnlockCheckbox").prop( "checked", false );
	$("#AutoUnlockModal").hide();
    });
    $("#AutoUnlockFooter").on("click","#CancelSetPassAutoUnlock", function () {
	// Uncheck if we canceled the modal window.
	$("#AutoUnlockCheckbox").prop( "checked", false );
	$("#AutoUnlockModal").hide();
    });
    $("#AutoUnlockFooter").on("click","#SetPassAutoUnlock", function () {
	var pass=$("#AutoUnlockWalletPass").val();
	// The user must supply the password.
	if (pass.length==0) {
	    alert ("Please enter the password.");
	} else if (/^[a-zA-Z0-9*!@#%+-]*$/.test(pass)) {
	    // Set the password in /usr/local/bin/unlocker and enable auto-unlocking. Change the parameters.txt as well.
	    $.ajax({
                url : 'js/SetParams.php',
		cache : false,
		type : 'POST',
		data : { autoUnlock : "true" },
		headers : { 'auther': $('meta[name="auther"]').attr('content') }
	    });
	    $.ajax({
		url : 'js/SetParams.php',
		type : "POST",
		cache : false,
		data : { autoUnlockpass : pass },
		headers : { 'auther': $('meta[name="auther"]').attr('content') }
	    }).done(function(){
		$("#AutoUnlockModal").hide();
	    });
	} else {
	    alert ("Please enter a proper password. Only letters, numbers and these characters are allowed: +-*%!@#");
	}
    });
});

// A function used to check the availability of the server after reboot.
function checkStatus() {
    $.ajax({
	url:'index.html',
	cache : false,
	success : function (result) {
	    window.location.replace("index.html");
	},
	error : function (result) {
	    setTimeout(checkStatus,2000);
	}
    })
}
var rebootDiv=$.Deferred();
$(document).ready(function () {
    // When the user clicks on reboot, a modal will be shown asking for confirmation.
    $("#rebootButton").click(function (){
	$("#OSactionModal").show();
	$("#OSactionInfo").html("Are you sure you want to <b>reboot</b>?");
	$("#confirmRebootAction").show();
	$("#confirmShutAction").hide();
    });
    // When the user clicks on shutdown, a modal will be shown asking for confirmation.
    $("#shutdownButton").click(function (){
	$("#OSactionModal").show();
	$("#OSactionInfo").html("<p>Are you sure you want to <b>shutdown</b>?</p>If you are intending to move the device to another network, make sure that you have set the LAN IP to auto.");
	$("#confirmRebootAction").hide();
	$("#confirmShutAction").show();
    });

    $("#OSactionFooter").on('click','#confirmRebootAction',function(){
	var reboot="";
	$.ajax({
	    url : "js/SetParams.php",
	    type : "POST",
	    data : { reboot : reboot },
	    cache : false,
	    headers : { 'auther': $('meta[name="auther"]').attr('content') },
	    success : function (result) {
		$("#OSactionModal").hide();
		$("#ConfigOS").html("<div style='font-size:26px;font-weight:bold;display:flex;align-items:center;justify-content:center;margin-bottom:25px;'>The system is rebooting...<i class='fa fa-cog fa-spin fa-3x fa-fw'></i></div><h4>You will be redirected when the server is ready.</h4>");
		rebootDiv.resolve(true);
	    }	    
	});
    });
    $.when(rebootDiv).done(function(){
	// Start after 25 sec and periodically check if the server is ready.
	setTimeout(checkStatus,25000);
    });
    
    $("#OSactionFooter").on('click','#confirmShutAction',function(){
	var shutdown="";
	$.ajax ({
	    url : "js/SetParams.php",
	    type : "POST",
	    cache : false,
	    data : { shutdown : shutdown },
	    headers : { 'auther': $('meta[name="auther"]').attr('content') },
	    success : function (result) {
		$("#OSactionModal").hide();
		$("#ConfigOS").html("<h2 style='margin-bottom:20px'>The system is shutting down...</h2><h3>Bye!</h3>");
	    }
	});
    });
    $("#closeOSaction").click(function(){
	$("#OSactionModal").hide();
    });
    $("#cancelOSaction").click(function(){
	$("#OSactionModal").hide();
    });
    
});
$(document).ready(function () {
    $("#useDomainName").on('change', function () {
	var useDomain = $(this).is(':checked');
	if (useDomain) {
	    $.ajax({
		url : "js/SetParams.php",
		type : "POST",
		cache : false,
		data : { useDomain : "true" },
		headers : { 'auther': $('meta[name="auther"]').attr('content') }
	    });
	} else {
	    $.ajax({
		url : "js/SetParams.php",
		type : "POST",
		cache : false,
		data : { useDomain : "false" },
		headers : { 'auther': $('meta[name="auther"]').attr('content') }
	    });
	}
    });
    $("#extIPtype").on('change', function () {
	var isChecked = $(this).is(':checked');
	if (isChecked) {
	    $("#DynInfoMessage").empty();
	    $(".DynamicIPInfo").hide();
	    $(".useDomainNameBox").show(1000);
	    $(".StaticIPInfo").show(1000);
	    var ChangeDnsStatic=""
	    $.ajax({
		url : "js/SetParams.php",
		type : "POST",
		cache : false,
		data : { ChangeDnsStatic : ChangeDnsStatic },
		headers : { 'auther': $('meta[name="auther"]').attr('content') }
	    });
	} else {
	    // We just inform the user to complete the next steps to enable FreeDNS.
	    $(".StaticIPInfo").hide();
	    $(".useDomainNameBox").hide();
	    $(".DynamicIPInfo").show(1000);
	    $("#DynInfoMessage").html("<p>Remember that you need to complete the form and submit it.<br>Otherwise the IP type will remain static.</p>").show();
	}
    });
});

$(document).ready(function () {
    $("#FreeDNSInfoSubmit").click(function () {
	var login=$("#FreeDNSLogin").val(),
	    pass=$("#FreeDNSPass").val(),
	    domain=$("#FreeDNSdomain").val();
	// We check if the user has input all the necessary data.
	if (login && pass && domain && /^[a-zA-Z0-9.]*$/.test(login) && /^[a-zA-Z0-9.*!@#$%]*$/.test(pass) && /^[a-zA-Z0-9.]*$/.test(domain) ) {
	    $.ajax ({
		url : "js/SetParams.php",
		type : "POST",
		cache : false,
		headers : { 'auther': $('meta[name="auther"]').attr('content') },
		data : { FreeDnsLogin : login , FreeDnsPass : pass , FreeDnsDomain : domain }
	    }).done(function (){
		$("#FreeDNSInfoSubmit").val("Done!");
		// Wait for 2 seconds so that the user gets informed by seeing "Done!"
		setTimeout(function(){ 
		    window.location.reload();
		},2000);
	    });
	} else {
	    alert("Please fill the form completely with proper values.");
	}
    });
});

$(function(){
    $("#OpenWebShell").click(function(){
	var int_ip=$("#int_ip").val();
	var hostnameval=$("#hostname").val();
	if (int_ip == "auto") {
	    alert ("You have set the internal IP to auto. Please first reboot the device to make it get a pre-defined IP.");
	} else {
	    $("#WebShellMessage").html('Opening a Web Shell...<i class="fa fa-cog fa-spin fa-2x fa-fw"></i>').show().fadeOut(3000);
	    // Wait for 1 sec to make sure the webshell has started and print a message.
	    setTimeout(function(){
		// This has the caveat that if the user changes int_ip in the meantime, the page will not load.
		// We have to do this as Android does not support .local DNS resolution.
		// Also it is because of the bug that the browser does not show the content.
		// I finally opted for .local domains.
		$("#WebShellInfo").html('<iframe src="http://'+hostnameval+'.local:4554" style="width:100%;"></iframe>');
		$("#WebShellModal").show();
	    },1000);
	}
    });

    $("#closeWebShell").click(function(){
	$("#WebShellModal").hide();
    });

    $("#WebShellFooter").on("click","#CloseWebShellButton",function(){
	$("#WebShellModal").hide();
    });
});

$(function(){
    $("#StopSiaButton").click(function(){
	$("#WebShellMessage").html('Stopping HyperSpace daemon...<i class="fa fa-cog fa-spin fa-2x fa-fw">');
	$.ajax({
	    url : "js/SetParams.php",
	    type : "POST",
	    cache : false,
	    headers : { 'auther': $('meta[name="auther"]').attr('content') },
	    data : { startSiad : "false" },
	    success : function (result) {
		setTimeout(function(){
		    window.location.reload();
		},5000);
	    }
	});
    });
});
