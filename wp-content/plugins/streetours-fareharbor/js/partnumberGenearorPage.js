var clip;
var obj;
var alert;

$(document).ready(function () {
	if ($("#decode").val()) {
		$(document).ajaxStop($.unblockUI);

		dismissDecoderError();

		/*ZeroClipboard.setMoviePath("ZeroClipboard.swf");
			clip = new ZeroClipboard.Client();
			clip.glue('copy', 'copydiv');
			clip.addEventListener('OnMouseDown', function(client) {
			clip.setText(obj.GetClipboardValues());
		});*/

		$("#parseSection").hide();
		/*$("#copy").click(function(){
				ZeroClipboard.setMoviePath("ZeroClipboard.swf");
				clip = new ZeroClipboard.Client();
				//clip.glue('copy', 'copydiv');

				clip.setText(obj.GetClipboardValues());

		});*/
		$("#copy").click(function () {
			var ClipboardContent = obj.GetClipboardValues();
			var Clipboard = $('<input id="clipboard">').val(ClipboardContent).appendTo('body').select();
			document.execCommand('copy');
			//alert(ClipboardContent.replace(/\t/g, ''));
			$("#clipboard").remove();


		});
		$("#decode").click(function () {
			$("#linktoContact").hide();
			$("#selectorsBody").empty();
			$("#resultPlaceHolder").empty();
			$("#copydiv").hide();

			hideToolTip();
			dismissDecoderError();
			var divWithError = getErrorsDiv();
			var temp = $("#partnumberToParse").val();
			$("#partnumberToParse").val(temp.replace(/ |'|"|\?|;|:/g, ''));
			obj = CreateGeneratorEntity($("#partnumberToParse").val(), "Decoder");
			if (obj != undefined) {
				if (obj.validationErrors.length > 0) {
					if ((obj.validationErrors[0] == ErrorMessages.ContactError) && (obj.validationErrors.length == 1)) {
						$("#linktoContact").show();
					} else {
						showDecoderErrorsInDiv(divWithError.append($(getErrorSpan(obj.validationErrors[0]))));
						drawLinkWithUnrecognizedPartNamber(obj.Mode);
						$("#linkWithUnrecognizedPart").show();
					}

				} else {
					obj.Reverse($("#partnumberToParse").val(), $("#selectorsBody"));
					if (obj.validationErrors.length > 0) {
						var text = "";
						for (var keyVar in obj.validationErrors) {
							if (obj.validationErrors[keyVar] != ErrorMessages.ContactError)
								divWithError.append($(getErrorSpan(obj.validationErrors[keyVar])));
						}
						if ((obj.validationErrors[0] == ErrorMessages.ContactError) && (obj.validationErrors.length == 1)) {
							SelectOptions();
							$("#linktoContact").show();
						} else {
							getPartNumberWithColor(obj.GenerateForColor(), obj.Mode);
							showDecoderErrorsInDiv(divWithError);
							drawLinkWithContactQuestions();
							$("#linkWithContactQuestions").show();
						}
					} else {
						SelectOptions();

						/*params = "command=getLeadTime&device=" + obj.PartFamily + "&package=" + obj.SelectedPackageSize.key;
						$("#resultPlaceHolder").block({
							message: 'Loading lead time for specified partnumber...'
						});
						$.post("../../../LeadTimeCalculator/server.php", params, LeadTimeCallback, "json");*/
						params = "command=getLeadTime&device=" + obj.PartFamily + "&package=" + obj.SelectedPackageSize.key;
						$("#resultPlaceHolder").block({ message: 'Loading lead time for specified partnumber...' });
						$.post("/api/lead_time", params, LeadTimeCallback, "json");

						$("#copydiv").css({
							"display": ""
						});
					}
				}
			} else {
				drawLinkWithUnrecognizedPartNamber("Decoder");
				$("#linkWithUnrecognizedPart").show();
			}
		});

		$("#productLink").click(function () {
			window.open(obj.LinkToProductPage, "_blank");
		});


		modeChangedDecoder();
	}
	//draw generator page here. onclick buttons events (clipboard param - actions look at Helper.js getGeneratorFooter )
	else {
		dismissError();
		var clipboard = new Clipboard('.copy-button');

		$("input[name='radio']").change(function () {

			if(this.id != 'radioUrl'){
				try {
					PlusMinButtonClick();
					$("#selectorsBody").empty();
				} catch (err) {}
				
				var val = $(this).val();
				//SelectOptions();
				obj = CreateGeneratorEntity(PartnumberAdapter(val), "Generator");
				if (obj == undefined)
					$("#selectorsBody").append($('<h2 style="text-align: left; font-weight: 500; font-size: 28px; color: #000;">Sorry, ' + val + ' Is under construction.</h2>'));
				obj.PartFamily = val;
				obj.PaintHTML($("#selectorsBody"), "Generator");
				
				SelectOptions();
			}
			else{
				$("#selectorsBody").empty();
				$("#footerRow").empty();
				SelectOptions();
			}

			//code example to decode partnumber from url (xreftool)
			/*var name = 'product';
			name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
			var regexS = "[\\?&]"+name+"=([^&#]*)";
			var regex = new RegExp( regexS );
			var results = regex.exec( window.location.href );
			$("#partnumber").val(results[1]);

			if (results[1].length > 14){
				setTimeout(function(){ $('#modeSwitcherBox').click()}, 100);
				setTimeout(function(){ $('#decodediv').click()}, 100);
			}*/

			$("body").on('click', '#requestdiv', function () {
				try {
					partNumberToLog("request  ");
				} catch (err) {
					console.log(err.message);
				}

				var partnumber = $("#partnumber").val();

				// checking partnumber and partnumber validity according to decoder process
				if (partnumber != "(please select all the options)" && partnumber && obj != undefined && obj.validationErrors.length == 0) {
					window.top.location.href = "/request-samples?pn=" + partnumber;
				} else if (partnumber == "(please select all the options)") {
					alert("Partnumber is invalid. Please select all the options.");
				} else {
					alert("Partnumber is invalid or is not set properly.");
				}
			});

			$("body").on('click', '#inventorydiv', function () {
				try {
					partNumberToLog("inventory");
				} catch (err) {
					console.log(err.message);
				}
				var partnumber = $("#partnumber").val();

				// checking partnumber and partnumber validity according to decoder process
				if (partnumber != "(please select all the options)" && partnumber && obj != undefined && obj.validationErrors.length == 0) {
					//window.top.location.href = "https://dilp.netcomponents.com/cgi-bin/sitime.asp?Region=NA&mode=1&partnumber1="+partnumber;
					window.top.location.href = "https://www.sitime.com/inventory-search?partId=" + partnumber;
				} else if (partnumber == "(please select all the options)") {
					alert("Partnumber is invalid. Please select all the options.");
				} else {
					alert("Partnumber is invalid or is not set properly.");
				}
				return;
			});

			$('body').on('click', '#resetdiv', function () {
				$("#selectorsBody").empty();

				obj = CreateGeneratorEntity(PartnumberAdapter(val), "Generator");
				obj.PartFamily = val;
				obj.PaintHTML($("#selectorsBody"), "Generator");
			});


			$('body').on('click', '#copydiv', function () {
				try {
					partNumberToLog("copy     ");
				} catch (err) {
					console.log(err.message);
				}
			});

			$('body').on('click', '#modeSwitcherBox', function () {
				if ($('#modeSwitcherBox').is(':checked')) {
					$("#decode").show();
					$("#partnumber").prop("readonly", !$(this).is(":checked"));
				} else {
					$("#decode").hide();
					$("#partnumber").prop("readonly", !$(this).is(""));
				}
			});

		});

		$('body').on('click', '#buy-on-DK', function () {

			var temppartnumber = $("#partnumber").val();
			var DK_button = $(this).find('span');
			if (temppartnumber != "(please select all the options)" && temppartnumber != "(invalid frequency)" && temppartnumber != undefined && temppartnumber != "") {

				DK_button.addClass('spinner-border spinner-border-sm');
				get_DK_JSONObject(temppartnumber);
				//get_DK_JSONObject(temppartnumber, function(objResponse) { ParseAPIresponse(objResponse, temppartnumber) });

				try{
					if ($('#info').length) {
								$('#infoWrap').remove();
							}
			}
			catch(err){
					console.log(err.message);
					}
			}
			else{
				$("#partnumber").css({backgroundColor: "#FCC"});
				setTimeout(function(){
				  $("#partnumber").css({backgroundColor: "#FFF"});
				}, 500);
			}

			setTimeout(function(){
				DK_button.removeClass("spinner-border spinner-border-sm");
				$('#infoWrap').css('display', '');
			}, 3300);
		});

		//decode button for 'mixed' mode.
		//'mixed' mode - allows to decode part number on PNG page.
		$('body').on('click', '#decodediv', function () {
			var temp = $("#partnumber").val();
			if (temp != "(please select all the options)" && temp != "(invalid frequency)" && temp != undefined && temp != "") {

				try {
					partNumberToLog("decode   ");
				} catch (err) {
					console.log(err.message);
				}

				$("#linktoContact").hide();
				$("#selectorsBody").empty();
				$("#resultPlaceHolder").empty();

				//hideToolTip();
				dismissDecoderError();
				var divWithError = getErrorsDiv();

				$("#partnumber").val(temp.replace(/ |'|"|\?|;|:/g, ''));
				obj = CreateGeneratorEntity($("#partnumber").val(), "Mixed");

				if (obj != undefined) {
					if (obj.validationErrors.length > 0) {
						if ((obj.validationErrors[0] == ErrorMessages.ContactError) && (obj.validationErrors.length == 1)) {
							$("#linktoContact").show();
							$("#selectorsBody").append($('<h2 style="text-align: left; font-weight: 500; font-size: 28px; color: #000;">Sorry, ' + val + ' Is under construction.</h2>'));
							$(".circle-plus").css({
								"position": "static"
							});
							$("#EVB_partnumber").val("");
						} else {
							drawLinkWithUnrecognizedPartNamber(obj.Mode);
							showDecoderErrorsInDiv(divWithError.append($(getErrorSpan(obj.validationErrors[0]))));
							$("#linkWithUnrecognizedPart").show();
							$("#EVB_partnumber").val("");
							//$("#selectorsBody").append($('<h2 style="text-align: left; font-weight: 500; font-size: 28px; color: #000;">'+obj.validationErrors[0]+'</h2>'));
						}
					} else {
						obj.Reverse($("#partnumber").val(), $("#selectorsBody"));
						if (obj.validationErrors.length > 0) {
							var text = "";
							for (var keyVar in obj.validationErrors) {
								if (obj.validationErrors[keyVar] != ErrorMessages.ContactError)
									divWithError.append($(getErrorSpan(obj.validationErrors[keyVar])));
							}
							if ((obj.validationErrors[0] == ErrorMessages.ContactError) && (obj.validationErrors.length == 1)) {
								SelectOptions();
								$("#linktoContact").show();
								$("#selectorsBody").append($('<h2 style="text-align: left; font-weight: 500; font-size: 28px; color: #000;">Sorry, ' + val + ' Is under construction.</h2>'));

							} else {
								//$(".buttonPack").css({"margin-top":"25%"});
								getPartNumberWithColor(obj.GenerateForColor(), obj.Mode);
								showDecoderErrorsInDiv(divWithError);
								drawLinkWithContactQuestions();
								$("#linkWithContactQuestions").show();
								$("#EVB_partnumber").val("");
								//$("#selectorsBody").append($('<h2 style="text-align: left; font-weight: 500; font-size: 28px; color: #000;">Sorry, '+val+' Is under construction.</h2>'));

							}
						} else {
							SelectOptions();
						}
					}
				} else {
					//drawLinkWithUnrecognizedPartNamber(obj.Mode);
					$("#selectorsBody").append($('<div class="linkWithUnrecognizedPartNamber" id="linkWithUnrecognizedPart" align="center" style = "background:#efefef; margin-top: 85px;"><span style="font-size:12px;">Unrecognized Part Number! Please </span><u><a href="https://www.sitime.com/company/contact-sales" target="_blank" style="font-size:12px;">contact SiTime</a></u></div>'));
					$("#linkWithUnrecognizedPart").show();
					$("#EVB_partnumber").val("");
					//$("#selectorsBody").append($('<h2 style="text-align: center; font-weight: 500; font-size: 28px; color: #000;">'+temp+'</h2>'));
					//$("#selectorsBody").append($('<h2 style="text-align: left; font-weight: 500; font-size: 28px; color: #000;"></h2>'));

				}
				obj.Mode = "Generator";
				if ($('#modeSwitcherBox').is(':checked') == false)
					$("#modeSwitcherBox").trigger("click");
			}
			else{
				$("#partnumber").css({backgroundColor: "#FCC"});
				setTimeout(function(){
				  $("#partnumber").css({backgroundColor: "#FFF"});
				}, 500);
			}
		});
	}
});

function ParseAPIresponse (objResponse, temppartnumber, checkBulkFlag){
	if (objResponse.responseJSON != undefined && objResponse.responseJSON.StatusCode != null){
				switch (objResponse.responseJSON.StatusCode){
				case 503:
				case 500:
				case 400:
				case 401:
					if (objResponse.responseJSON.ErrorMessage == "The Bearer token is invalid" || objResponse.responseJSON.ErrorMessage == "Bearer token  expired")
						$( '<div id="infoWrap" style = "display : none"><br><span id="info" style= "color:red">Sorry, service is temporally unavailable. We are working on fixing this issue.<br> Please visit Digi-Key web site by using this</span> <u><a href="https://www.digikey.com/products/en?keywords='+temppartnumber+'" target="_blank" style="font-size:12px;"> link.</a></u></div>' ).insertAfter( "#DKDiv" );	
					else
						$( '<div id="infoWrap" style = "display : none"><br><span id="info" style= "color:red">'+"Sorry, " + objResponse.responseJSON.ErrorMessage + "." +'</span></div>' ).insertAfter( "#DKDiv" );						
					console.log(objResponse.responseJSON.ErrorMessage + '\n' 
								+ objResponse.responseJSON.ErrorDetails);
					break;
				/*case 404:
					window.open('https://www.digikey.com/forms/en/CustQT/SiTimeQuoteRequest/Index/' + temppartnumber.replace(".", ","));
					break;*/
				default:
					$( '<div id="infoWrap" style = "display : none"><br><span id="info" style= "color:red">Something went wrong. Please </span><u><a href="https://www.sitime.com/company/contact-sales" target="_blank" style="font-size:12px;">contact SiTime.</a></u><span style= "color:red"> Or visit </span><u><a href="https://www.digikey.com" target="_blank" style="font-size:12px;"> Digi-Key</a></u></div>' ).insertAfter( "#DKDiv" );
					break;
				}
	}
	else if (objResponse.ProductsCount == undefined){
		switch (objResponse.length){
		case 1:
			window.open(objResponse[0].ProductUrl);
			break;
		default:
			window.open('https://www.digikey.com/products/en?keywords=' + temppartnumber);
			break;
		}
	}
	else{
		if (!checkBulkFlag) {
			CheckForBulkPackagingInAPI(temppartnumber);
		}
		else
			window.open('https://www.digikey.com/forms/en/CustQT/SiTimeQuoteRequest/Index/' + temppartnumber.replace(".", ","));
	}
}

function CheckForBulkPackagingInAPI (temppartnumber){
	temppartnumber = temppartnumber.substring(0, temppartnumber.length - 1);
	setTimeout(function(){
				  get_DK_JSONObject(temppartnumber, true);
				}, 2000);

	//objResponse = get_DK_JSONObject(temppartnumber);
	//ParseAPIresponse(objResponse, temppartnumber, true);
}

function get_DK_JSONObject(partnumberToAPI, flag)  {
	var returnValue = null;
	switch(true) {
		case window.location.hostname.toString().includes("dev"):
			//var url = 'http://dev.sitime.com/digikey/search';
			var url = 'http://dev.sitime.com/digikey/keyword';
			break;
		case window.location.hostname.toString().includes("stage"):
			//var url = 'http://stage.sitime.com/digikey/search';
			var url = 'http://stage.sitime.com/digikey/keyword';
			break;
		case window.location.hostname.toString().includes("www"):
			//var url = 'https://www.sitime.com/digikey/search';
			var url = 'https://www.sitime.com/digikey/keyword';
			break;
		default:
			//var url = 'http://dev.sitime.com/digikey/search';
			var url = 'http://dev.sitime.com/digikey/keyword';

	}
	$('#buy-on-DK').attr("disabled", true);

	var settings = {
	  method: 'POST',
	  url : url,
	  headers: {
	  	"Accept": 'application/json',
	  	"Cache-Control": 'no-cache',
	  	"Content-Type": 'application/json'
	  },

	  //data: JSON.stringify({product: partnumberToAPI})
	  data:  JSON.stringify({keyword: partnumberToAPI, limit: 7})

	};

	$.ajax(settings)
	.done(function (response) {
	    //console.log(response);
	    console.log("done");
		ParseAPIresponse(objResponse = response, partnumberToAPI, flag);
	})
	.fail(function (response) {
		//console.log(response);
		console.log("fail");
		ParseAPIresponse(objResponse = response, partnumberToAPI, flag);

	})
	.complete(function(){
		//Ajax request is finished, so we can enable
		//the button again.
		$('#buy-on-DK').attr("disabled", false);
	});
}

function partNumberToLog(pressedButton) {
	var partnumber = $("#partnumber").val();

	var today = new Date();
	var date = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
	var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
	var dateTime = date + ' ' + time;

	$.ajax({
			//url: "http://localhost:80/products/PNG_Drupal/partnumber-generator_v3/GeneratorScripts/Log/PartnumberLogHelper.php",
			url: "/themes/custom/sitime/existing-static-tools/partnumber-generator/GeneratorScripts/Log/PartnumberLogHelper.php",
			data: {
				dateTime: dateTime,
				PN: partnumber,
				button: pressedButton
			},
			cache: false,
			async: true,
			type: 'POST',
			timeout: 5000
		})
		.done(function () {
			console.log("success");
		})
		.fail(function () {
			console.log("error");
		})
		.always(function () {
			console.log("complete");
    });
}

function modeChangedGenerator() {
	hideToolTip();
	$("#linktoContact").hide();
	dismissDecoderError();
	$("#devicesSection").show();
	$("input[name='Radio1']:checked").click();

	$("#framePNC").show();
	$("#parseSection").hide();

	$("input[name='Radio1']").removeAttr("disabled");
}

function modeChangedDecoder() {
	hideToolTip();
	$("#linktoContact").hide();
	dismissDecoderError();
	$("#selectorsBody").empty();
	$("#resultPlaceHolder").empty();
	$("#partnumberToParse").val("");

	$("input[name='Radio1']").attr("disabled", "disabled");
	$("#devicesSection").hide();
	$("#framePNC").hide();
	$("#parseSection").show();
}

function LeadTimeCallback(data) {
	$("#resultPlaceHolder").empty();
	var qntiesTR = $("<tr/>").append($("<td class='headerColumn'/>").text("Order Qty Range"));
	var leadtimeTR = $("<tr/>").append($("<td class='headerColumn'/>").text("Lead Time(weeks)"));

	counter = 0;
	$.each(data.LeadTime, function (i, item) {
		$(qntiesTR).append($("<td class='simpleColumn'/>").append(i));
		$(leadtimeTR).append($("<td class='simpleColumn'/>").append(item));
		counter++;
	});

	$("#resultPlaceHolder").append($("<table class='table'/>").attr("id", "tbl").append($(qntiesTR)).append($(leadtimeTR)));

	$("#resultPlaceHolder").append();
	$.each(data.Comments, function (i, item) {
		$("#tbl")
			.append($("<tr class='comment'/>")
				.append($("<td/>").attr("colspan", "" + ++counter)
					.append($("<div class='commentWrapper'/>").text(item.Value))));
	});
}

function showDecoderErrorOnForm(text) {
	$("#selectorsBody").append($("<textarea name='error' cols='50' rows='20' disabled='disabled' style='resize:none;'/>")
		.append(text));
}

function showDecoderErrorOnRedButton(text) {
	$("#frequency_error a").attr("title", text);
	$("#frequency_error").show();
}

function dismissDecoderError() {
	$("#frequency_error").hide();
	$("#linktoContact").hide();
}

function trigger(keyCode) {
	if (keyCode == 13) {
		$("#decode").click();
	}
}

function PartnumberAdapter(partnumber) {
	var body;
	if (isNumber(partnumber.substring(0, 4)))
		body = partnumber.substring(0);
	else
		body = partnumber.substring(3);
	body = body.toUpperCase();
	return body;
}

function modeChangedGenerator() {
	$("#devicesSection").show();
	$("input[name='Radio1']:checked").click();

	$("#framePNC").show();
	$("#parseSection").hide();

	$("input[name='Radio1']").removeAttr("disabled");
}
