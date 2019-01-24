$(document).ready(function () {
    
    // An array for storing to-be-formatted devices.
    var devFormatList=[];
    $(".FormatDriveCheckbox").on('change', function() {
        var isChecked = $(this).is(':checked');
	var devname=$(this).attr("id");
        if (isChecked) {
	    // If the user checked an item from the class, add the name (which is the id of the checkbox)
	    // to the array. Hide the whole partition list too.
	    devFormatList.push(devname);
	    $(this).closest(".WholeDevice").find(".DrivePartsListMain").hide();
        } else {
	    // If unchecked, do the reverse: delete from the array and then show the partition list.
	    devFormatList.splice($.inArray(devname,devFormatList),1);
	    $(this).closest(".WholeDevice").find(".DrivePartsListMain").show(); 
	}
    });

    // An array for holding all of the partitions which will be mounted as space holders.
    var partList=[];
    $(".PartitionCheckbox").on("change", function () {
	var isChecked=$(this).is(":checked");
	var item=$(this).attr("id");
	if (isChecked) {
	    // If checked, add to the array.
	    partList.push(item);
	} else {
	    // If unchecked, delete from the array.
	    partList.splice($.inArray(item, partList),1);
	}
    });

    $("#AddSpaceButton").click(function () {
	// If both selections are empty, alert the user.
	if (devFormatList.length==0 && partList.length==0) {
	    alert("You have not selected anything.")
	} else {
	    // If the user has opted to format drives, warn him by changing the content of modal body.
	    if (devFormatList.length>0) {
		$("#AddSpaceInfo").html("You have chosen to format one or more drives. Please confirm your final selection.");
	    }
	    $("#AddSpaceModal").show();
	}
    });
    $("#closeAddSpace").click(function () {
	$("#AddSpaceModal").hide();
    });
    $("#AddSpaceFooter").on('click','#cancelAddSpace',function(){
	$("#AddSpaceModal").hide();
    });
    var d1=$.Deferred();
    $("#AddSpaceFooter").on('click','#confirmAddSpace',function(){
	// Send both arrays to ajax call.
	// Remember that ajax will NOT send empty arrays!
	$("#AddSpaceModal").hide();
	// Show that we are working for now.
	$("#HostMessage").html('Allocating space ...<i class="fa fa-cog fa-spin fa-2x fa-fw">');
	$.ajax ({
	    url : "js/SetParams.php",
	    cache : false,
	    type : "POST",
	    headers : { 'auther': $('meta[name="auther"]').attr('content') },
	    data : { partList : partList , devFormatList : devFormatList } ,
	    dataType : "json",
	    success : function (result) {
		// Reload the hosting page
		$("#Hosting").load("LoadHosting.html");
		// Make the resolving wait for 1 sec
		setTimeout(function () {
		    d1.resolve(result);
		},1000);
	    }
	});		
    });
    $.when(d1).done(function(v1){
	// Make the hosting page reflect the new state, once resolving is done.
	$("#HostMessage").html("The space for hosting was created.").fadeOut(3000);
	// Clear the corresponding td in the table and populate it with the data.
	$("#HostPointstd").empty();
	$.each (v1, function (i,val) {
	    $("#HostPointstd").append('<div style="display:flex;"><input class="HostPoint" value="'+val+'" readonly></div>');
	});
	// Store the current active tab in the sessionStorage and reload.
	// So that we do not have to repaint all the storage sliders in here.
	setTimeout(function(){
	    sessionStorage.ActiveTab="hosting";
	    window.location.reload();
	},3000);
    });

    // For putting the value of the slider into the span.
    $(".StorageSlider").on('input', function(){
	// We force the output value to always be shown in 1 decimal digit.
	var storageVal=parseFloat($(this).val()).toFixed(1);
	$(this).closest(".StorageShower").find(".StorageSliderVal").html(storageVal+" GiB");
    });

    // For converting blocks to day in hosting configuration.
    $("#HCMaxDuration").keyup(function(){
	var days=$(this).val();
	if (days=="") {
	    $("#HCMaxDuration2block").html("25920");
	} else {
	    var blocks=Math.floor(days*6*24);
	    $("#HCMaxDuration2block").html(blocks);
	}
    });

    $("#HCWindowSize").keyup(function(){
	var days=$(this).val();
	if (days=="") {
	    $("#HCWindowSize2block").html("144");
	} else {
	    var blocks=Math.floor(days*6*24);
	    $("#HCWindowSize2block").html(blocks);
	}
    });
});
$(document).ready(function(){
    $("#SaveHostSettings").click(function(){
	// We need show() or it will not be shown more than once.
	$("#SaveSettingsMessage").html('Applying the settings...<i class="fa fa-cog fa-spin fa-2x fa-fw">').show();
	var hostaddress=$.trim($("#HostAddress4hosting").val());
	var maxdlbsize=$.trim($("#HCMaxDownloadBatchSize").val());
	var maxduration=$.trim($("#HCMaxDuration").val());
	var maxrevbsize=$.trim($("#HCMaxRevisedBatchSize").val());
	var windowsize=$.trim($("#HCWindowSize").val());
	var collat=$.trim($("#HCCollateral").val());
	var collatbudget=$.trim($("#HCCollateralBudget").val());
	var maxcollat=$.trim($("#HCMaxCollateral").val());
	var mincontprice=$.trim($("#HCMinContractPrice").val());
	var mindlprice=$.trim($("#HCMinDLPrice").val());
	var minupprice=$.trim($("#HCMinUpPrice").val());
	var minstprice=$.trim($("#HCMinStoragePrice").val());
	
	hconfig_json={};
	
	if (maxdlbsize!="") {
	    hconfig_json["maxdownloadbatchsize"]=maxdlbsize;
	}
	if (maxduration!="") {
	    hconfig_json["maxduration"]=maxduration;
	}
	if (maxrevbsize!="") {
	    hconfig_json["maxrevisebatchsize"]=maxrevbsize;
	}
	if (windowsize!="") {
	    hconfig_json["windowsize"]=windowsize;
	}
	if (collat!="") {
	    hconfig_json["collateral"]=collat;
	}
	if (collatbudget!="") {
	    hconfig_json["collateralbudget"]=collatbudget;
	}
	if (maxcollat!="") {
	    hconfig_json["maxcollateral"]=maxcollat;
	}
	if (mincontprice!="") {
	    hconfig_json["mincontractprice"]=mincontprice;
	}
	if (mindlprice!="") {
	    hconfig_json["mindownloadbandwidthprice"]=mindlprice;
	}
	if (minupprice!="") {
	    hconfig_json["minuploadbandwidthprice"]=minupprice;
	}
	if (minstprice!="") {
	    hconfig_json["minstorageprice"]=minstprice;
	}

	var checkvals=true;
	$.each (hconfig_json, function (indx,val) {
	    if (!$.isNumeric(val) || val<0) {
		checkvals=false;
		alert("You must enter a correct value for "+indx+".");
		$("#SaveSettingsMessage").empty();
	    }
	});
	// As we don't need to check these two, we should put them after the checking process.
	hconfig_json["acceptingcontracts"]=false;
	hconfig_json["netaddress"]=hostaddress;
	
	if (checkvals) {
	    $.ajax({
		url : 'js/SetParams.php',
		type : 'POST',
		cache : false,
		headers : { 'auther': $('meta[name="auther"]').attr('content') },
		data : { SetSettings : true, hconfig_json : JSON.stringify(hconfig_json) },
		success : function (result) {
		    setTimeout(function(){
			$("#SaveSettingsMessage").html('The settings were applied.').show().fadeOut(3000);
		    },1000);
		},
		error : function (msg) {
		    alert ('An error occured.');
		}
	    });
	}
    });

    $("#SaveStorageSettings").click(function(){
	$("#SaveStorageSettingsMessage").html('Configuring Sia storage...<i class="fa fa-cog fa-spin fa-2x fa-fw">').show();
	// Getting storage data
	var storageSizes=[];
	// Count the folders
	var numStorage=$(".StorageSlider").length;
	for (i=1; i<=numStorage; i++) {
	    var fsize=$("#hstorage"+i).val();
	    storageSizes.push(fsize);
	}
	$.ajax({
	    url : 'js/SetParams.php',
	    type : 'POST',
	    cache : false,
	    headers : { 'auther': $('meta[name="auther"]').attr('content') },
	    data : { storageSizes : storageSizes },
	    success : function (result) {
		setTimeout(function(){
		    $("#SaveStorageSettingsMessage").html('The storage settings were applied.').show().fadeOut(3000);
		},1000);
	    },
	    error : function (msg) {
		alert ('An error occured.');
	    }
	});
    });
    
    $("#AnnounceButton").click(function(){
	$("#AnnounceHostModal").show();
    });

    $("#AnnounceHostFooter").on("click","#cancelAnnounceHost", function() {
	$("#AnnounceHostModal").hide();
    });

    $("#closeAnnounceHost").click(function() {
	$("#AnnounceHostModal").hide();
    });

    $("#AnnounceHostFooter").on("click","#confirmAnnounceHost", function() {
	$("#AnnounceHostModal").hide();
	$("#SaveSettingsMessage").html('Announcing...<i class="fa fa-cog fa-spin fa-2x fa-fw">').show();
	var hostaddress=$.trim($("#HostAddress4hosting").val());
	var announcing=true;
	$.ajax({
	    url : 'js/SetParams.php',
	    headers : { 'auther': $('meta[name="auther"]').attr('content') },
	    cache : false,
	    type : 'POST',
	    data : { announcing : announcing , hostaddress : hostaddress },
	    success : function () {
		setTimeout(function(){
		    $("#SaveSettingsMessage").html('Done!').show().fadeOut(3000);
		},1000);
	    }
	});
    });
});
