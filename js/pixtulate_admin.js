jQuery(window).bind("load", function() {	
	var domainInput = jQuery("input#pixtulate_domain");
	var connector = jQuery("select#pixt_connector");
	var submit = jQuery("#connector_btn");
	var pix_render = jQuery("p#pixtulate_rendering_input");
	var tooltip = jQuery(".pixt_tooltip");
	var count = 0;
		
	if(domainInput.val() == '')
		connector.attr('disabled', true);
	
	if(pix_render.find("input").eq(1).is(':checked')) { 
		connector.find("option").eq(0).attr('selected', true);
		connector.find("option").eq(1).attr('disabled', true);
	}
		
	domainInput.blur(function() {
		var val = jQuery(this).val();
		if(val == '')
			connector.attr('disabled', true);
		else 
			connector.attr('disabled', false);
			
		if(pix_render.find("input").eq(1).is(':checked')) { 
			connector.find("option").eq(0).attr('selected', true);
			connector.find("option").eq(1).attr('disabled', true);
		}
	});

	
	pix_render.find("input").change(function() {
		if(pix_render.find("input").eq(1).is(':checked')) {
			connector.find("option").eq(0).attr('selected', true);
			connector.find("option").eq(1).attr('disabled', true);
		}
		else {
			connector.find("option").eq(1).attr('disabled', false);
		}
	});	domainInput.bind("input propertychange", function() {		matches = jQuery(this).val().match(/[@&!?#$%`~^*(),._+\-=\[\]{}:;"\\|<>\/]*$/g);				if(matches.length == 2) {			rep = jQuery(this).val().replace(/[@&!?#$%`~^*(),._+\-=\[\]{}:;"\\|<>\/]*$/g, ""); 			jQuery(this).val(rep);		}	});
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
				jQuery("form#pixtForm").submit();
			}).fail(function(data) {
				jQuery("p.error").css("display", "block");
			});		
		}
		
	});
	
	// tooltips
	jQuery("img.tooltip01").hover(function() {
		tooltip_func(1);
	}, function() {
		tooltip.fadeOut(300);
	});
	
	jQuery("img.tooltip02").hover(function() {
		tooltip_func(2);
	}, function() {
		tooltip.fadeOut(300);
	});

	jQuery("img.tooltip03").hover(function() {
		tooltip_func(3);
	}, function() {
		tooltip.fadeOut(300);
	});
	
	jQuery("img.tooltip04").hover(function() {
		tooltip_func(4);
	}, function() {
		tooltip.fadeOut(300);
	});
	
	jQuery("img.tooltip05").hover(function() {
		tooltip_func(5);
	}, function() {
		tooltip.fadeOut(300);
	});
	
	function tooltip_func(casenum) {
		tooltip.fadeIn(300);
		switch(casenum) {
			case 1:
				tooltip.css({ position: "absolute", top: "317px", left: "424px" });
				tooltip.html("Images set to display at full width and which are larger than visitor's screen will be downsized to screen width.");
			break;
			case 2:
				tooltip.css({ position: "absolute", top: "340px", left: "360px" });
				tooltip.html("Sets an image's dimensions to that of its container. Will cause default width and height attributes set by WP to be ignored.");
			break;
			case 3:
				tooltip.css({ position: "absolute", top: "435px", left: "286px" });
				tooltip.html("All images will load over SSL (https) even if your site does not.");
			break;
			case 4:
				tooltip.css({ position: "absolute", top: "518px", left: "211px" });
				tooltip.html("Only process images contained inside posts and pages.");
			break;
			case 5:
				tooltip.css({ position: "absolute", top: "495px", left: "173px" });
				tooltip.html("Process ALL images including logos and theme assets outside content of page or post. Must select host only option as connector path.");
			break;
			default:
			break;
		}
	}
});