$(function(){
    $("#RetireButton").click(function(){
	$("#RetireHostModal").show();
    });
    $("#RetireHostFooter").on("click","#cancelRetireHost", function() {
	$("#RetireHostModal").hide();
    });
    $("#closeRetireHost").click(function() {
	$("#RetireHostModal").hide();
    });
    $("#RetireHostFooter").on("click","#confirmRetireHost", function() {
	$("#RetireHostModal").hide();
	$("#RetireHostMessage").html('Retiring the host...<i class="fa fa-cog fa-spin fa-2x fa-fw">').show();
	$.ajax({
	    url : 'js/SetParams.php',
	    data : { retiring : true },
	    cache : false,
	    type : "POST",
	    headers : { 'auther': $('meta[name="auther"]').attr('content') },
	    success : function (result) {
		setTimeout(function(){
		    $("#RetireHostMessage").html('The host was retired.').show().fadeOut(5000);
		},1000);
	    }
	});
    });
});
