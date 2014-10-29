jQuery(window).bind("load", function() {	
	var domainInput = jQuery("input#pixtulate_domain");
	var connector = jQuery("select#pixtulate_connector");
	var submit = jQuery("#connector_test");

	if(domainInput.val() == '')
		connector.attr('disabled', true);
	
	domainInput.blur(function() {
		var val = jQuery(this).val();
		if(val == '')
			connector.attr('disabled', true);
		else 
			connector.attr('disabled', false);
	});

	submit.click(function() {
		var val = domainInput.val();
		var opt = connector.val();
		
		if(val == '') {
			jQuery("p.error").css("display", "block");
		} else {
			jQuery("p.error").css("display", "none");
			
			var serviceURL = "http://api.pixtulate.com/account/"+val+"/connector";
			var dataSent = {"location":opt, "connectorName":"HTTP", "fileType":"SOURCE", "clientId":val};
									
			jQuery.ajax({
				type: "PUT",
				contentType: "application/json",
				url: serviceURL,
				data: JSON.stringify(dataSent)
            }).done(function() {
				alert("Configuration Updated Successfully.");
			}).fail(function(data) {
				jQuery("p.error").css("display", "block");
			});		
		}
		
		
	});
});
