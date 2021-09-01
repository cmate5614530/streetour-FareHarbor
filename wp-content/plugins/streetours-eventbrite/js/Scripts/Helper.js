$.fn.addRadioBox=function(name,options,pointer,handler,disabledValue,ifChecked) {
	(disabledValue == "disabled") ?
		$(this).addDisabledRadioBox(name,options,pointer,handler,ifChecked) :
		$(this).addEnabledRadioBox(name,options,pointer,handler,ifChecked);

		if(options.info!=""){
			$(this).children().children("div:last").append($("<sup/>")
			.append($("<a/>")
			.attr("href","")
			.attr("title",options.info)
			.click(function(){return false;})
			.append($("<img style='margin-bottom:0px' src='GeneratorScripts/images/help-16.png' alt='[i]'/>"))
			//.append($("<img src='../i/help.png' alt='[i]'/>"))
			));
		}
	return $(this);
}

$.fn.addDisabledRadioBox = function(name,options,pointer,handler,ifChecked){
	$(this).append($("<label/>").addClass("radio_wrapp")
		.css({"width":options.width, "border": "1.0px dotted transparent"})
		.append($("<div/>").addClass("option")
		.css({"width":options.width})
		.append($("<input type='radio'/>")
		.attr("name",name)
		.attr("value",options.key)
		.attr("group",options.group)   // added for SiT9005 which have common values for spread spectrum option
		.prop("disabled", true)
		.change(function(){
			try{
				pointer[handler]();
				pointer.AvailableOptionsShow();
				pointer.ShowGenerateResult();
			} catch(Exception){}
			SelectOptions();
		}))
        .append(options.value)));
	return $(this);
}

$.fn.addEnabledRadioBox = function(name,options,pointer,handler,ifChecked){
	var inputRadio = ifChecked ?
		addCheckedInputRadio(name,options,pointer,handler) :
		addUnCheckedInputRadio(name,options,pointer,handler);
		$(this).append($("<label/>").addClass("radio_wrapp")
		.css({"width":options.width, "border": "1.0px dotted transparent"})
		.append($("<div/>").addClass("option")
		.css({"width":options.width})
		.append(inputRadio)
        .append(options.value)));
	return $(this);
}

function addCheckedInputRadio(name,options,pointer,handler){
	return $("<input type='radio'/>")
		.attr("name",name)
		.attr("value",options.key)
		.attr("group",options.group)   // added for SiT9005 which have common values for spread spectrum option
		.prop("checked", true)
		.change(function(){
			try{
				pointer[handler]();
				pointer.AvailableOptionsShow();
				var ifCallShowGenerateResult = true;
				if(pointer.GetChangeOptionRestrictionResult()){
					var tempFrequencyValue = '';
					if($("#frequency").val()!== undefined){
						tempFrequencyValue = $("#frequency").val();
					}
					$("#selectorsBody").empty();
					pointer.PaintHTML( $("#selectorsBody"),"Generator");
					pointer.SelectOptions();
					if(tempFrequencyValue!== undefined){
						$("#frequency").val(tempFrequencyValue);
						pointer.OnFrequencyChange();
						ifCallShowGenerateResult = false;
					}
				}

				pointer.AvailableOptionsShow();

				if(ifCallShowGenerateResult)
					pointer.OnFrequencyChange();
					//pointer.ShowGenerateResult();
			} catch(Exception){}
			SelectOptions();
		});
}

function addUnCheckedInputRadio(name,options,pointer,handler){
	return $("<input type='radio'/>")
		.attr("name",name)
		.attr("value",options.key)
		.attr("group",options.group)   // added for SiT9005 which have common values for spread spectrum option
		.change(function(){
			try{
				pointer[handler]();
				pointer.AvailableOptionsShow();
				var ifCallShowGenerateResult = true;
				if(pointer.GetChangeOptionRestrictionResult()){
					var tempFrequencyValue = '';
					if($("#frequency").val()!== undefined){
						tempFrequencyValue = $("#frequency").val();
					}
					$("#selectorsBody").empty();
					pointer.PaintHTML( $("#selectorsBody"),"Generator");
					pointer.SelectOptions();
					if(tempFrequencyValue!== undefined){
						$("#frequency").val(tempFrequencyValue);
						pointer.OnFrequencyChange();
						ifCallShowGenerateResult = false;
					}
				}

				pointer.AvailableOptionsShow();

				if(ifCallShowGenerateResult)
					pointer.OnFrequencyChange();
					//pointer.ShowGenerateResult();

			} catch(Exception){}
			SelectOptions();
		});
}

$.fn.addCaption=function(name){
	$(this).append($("<td/>").addClass("caption").append(name));
	return $(this);
}

$.fn.addliClass=function(className, classId, value){
	$(this).append($("<li>"+value+"</li>").addClass(className).attr("id",classId));
	return $(this);
}

$.fn.addliWithWidthClass=function(className, classId, value, width){
	$(this).append($('<li style = "width:' + width + 'px;">'+value+'</li>').addClass(className).attr("id",classId));
	return $(this);
}

function AddListOfFrequencyInGenerator(pointer){
	var ulValue = $("<ul id='freqList'/>");

	if(pointer.ListOfFrequency.length == 1){
	$("#frequency").val(pointer.ListOfFrequency);
	$("#frequency").prop("disabled", true);
	}
	if(pointer.ListOfFrequency.length>9){


		var offsetValue = parseInt(pointer.ListOfFrequency.length/3);
		var valueModulus = pointer.ListOfFrequency.length % 3

		var firstOffset = offsetValue;
		var secontOffset = offsetValue;

		if(valueModulus == 1)
		{
			firstOffset = offsetValue + 1;
			secontOffset = offsetValue;
		} else if(valueModulus == 2){
			firstOffset = offsetValue + 1 ;
			secontOffset = offsetValue + 1;
		}

		if(valueModulus > 0)
			offsetValue++;

		var counter = 0;
		var ifLastValue = false;
		for(var index=0;index < offsetValue ;index++){ //reworked from offsetValue + 1;

			//tested cycle
			if(valueModulus == 1 && ifLastValue){
				ulValue.addliWithWidthClass("rowElem",pointer.ListOfFrequency[index + counter],pointer.ListOfFrequency[index + counter], 70);
				counter = 0;
			}

			if(!ifLastValue ){
				ulValue.addliWithWidthClass("rowElem",pointer.ListOfFrequency[index + counter],pointer.ListOfFrequency[index + counter], 70);
				counter+=firstOffset;
			}


			if(!ifLastValue || valueModulus == 2 && ifLastValue){
				ulValue.addliWithWidthClass("rowElem",pointer.ListOfFrequency[index + counter],pointer.ListOfFrequency[index + counter], 70);
				counter+=secontOffset;
			}


			if(!ifLastValue ){
				ulValue.addliWithWidthClass("rowElem",pointer.ListOfFrequency[index + counter],pointer.ListOfFrequency[index + counter], 70);

				if(index + counter == pointer.ListOfFrequency.length - 1)
					ifLastValue= true;

				counter = 0;
			}
		}
	}
	else
	{
		for ( keyVar in pointer.ListOfFrequency) {
			ulValue.addliWithWidthClass("rowElem",pointer.ListOfFrequency[keyVar],pointer.ListOfFrequency[keyVar],90);
		}
	}
	return ulValue;
}
var freqList;
var MSIE = navigator.userAgent.indexOf('MSIE')?true:false;

function showContextMenu(e){
  position = findPos(document.getElementById("frequency"));

  freqList.style.left = (position.left + 185) + 'px';
  freqList.style.top = (position.top + 27) + 'px';
  freqList.style.display='block';
  freqList.style.width = '220px'
  return false;
}
function hideContextMenu(e){
	if(document.all) e = event;
	if(e.button==0 && !MSIE){

	}else{
		freqList.style.display='none';
	}
}
function highlightContextMenuItem(){
	this.className = "rowElemSelected";
}
function deHighlightContextMenuItem(){
	this.className='rowElem';
	$(this).removeClass("contextMenuHighlighted");
}
function putIntoBox(){
	$("#frequency").val(this.id);
	$("#frequency").change();
	freqList.style.display='none';
}
function initContextMenu(selector){
	freqList = selector[0].children.freqList;
	var menuItems = freqList.getElementsByTagName('LI');
	for(var no=0;no<menuItems.length;no++){
		menuItems[no].onmouseover = highlightContextMenuItem;
		menuItems[no].onmouseout = deHighlightContextMenuItem;
		menuItems[no].onmousedown = putIntoBox;
	}
	freqList.style.display = 'none';
	freqList.style.width = '230px'
	document.getElementById("frequency").onfocus = showContextMenu;
	document.getElementById("frequency").onblur = hideContextMenu;
}
function SelectOptions(){
	$("input[type='radio']").parent().css({backgroundColor: ""});
	$("input[type='radio'][name!='Radio1'][name!='mode']:checked").parent().css({backgroundColor: "#ccf"});

	$("input[type='radio']").parent().parent().css({backgroundColor: ""});
	$("input[type='radio'][name!='Radio1'][name!='mode']:checked").parent().parent().css({backgroundColor: "#ccf"});
}

function SelectOption(name, value){
	$("input[name='"+ name +"']" + "[value='" + value + "']").prop("checked", true);

	//$("input[name='"+ name +"']" + "[value='" + value.key + "']").parent().css({backgroundColor: ""});
	//$("input[[name='"+ name +"']" + "[value='" + value.key + "']:checked").parent().css({backgroundColor: "#ccf"});
}

function SelectOptionWithGroup(name, group, value){
	$("input[name='"+ name +"']" + "[group='" + group + "']" + "[value='" + value + "']").prop("checked", true);
	//$("input[name='"+ name +"']" + "[value='" + value.key + "']").parent().css({backgroundColor: ""});
	//$("input[[name='"+ name +"']" + "[value='" + value.key + "']:checked").parent().css({backgroundColor: "#ccf"});
}

function DeselectOption(name, value){
	$("input[name='"+ name +"']" + "[value='" + value + "']").removeAttr("checked");
}

function disableOption(name, value){
	$("input[name='"+ name +"']" + "[value='" + value + "']").prop("disabled", true);
}

function showAllOptions(name){

		//$("input[name='"+ name +"']").parent().css({ "display": "" });
		$("input[name='"+ name +"']").parent().parent().css({ "display": "" });
}

function showOption(name, value){																	//show all options except selected one!!!

		$("input[name='"+ name +"']").parent().parent().css({ "display": "" });
		$("input[name='"+ name +"']" + "[value='" + value + "']").parent().parent().css({ "display": "none" });
}

function hideOption(name, value){																	//hide all options except selected one!!!

		$("input[name='"+ name +"']").parent().parent().css({ "display": "none" });
		$("input[name='"+ name +"']" + "[value='" + value + "']").parent().parent().css({ "display": "" });
}

function HideHTMLResource(HeaderName, OptionName){

		$("input[name='"+ OptionName +"']").parent().css({ "display": "none" });
		/*$("td:contains('"+ HeaderName +"')").contents().filter(function () {
			return (this.nodeType == 3 && $.trim(this.nodeValue) == HeaderName);
		}).parent().css({ "display": "none" });*/

		$('tr').each(function(){
		var tr = $(this);
		if (tr.find('td:eq(0)').text()== HeaderName

		) tr.hide();
	});
}

function ShowHTMLResource(HeaderName, OptionName){

		$("input[name='"+ OptionName +"']").parent().css({ "display": "" });
		/*$("td:contains('"+ HeaderName +"')").contents().filter(function () {
			return (this.nodeType == 3 && $.trim(this.nodeValue) == HeaderName);
		}).parent().css({ "display": "" });*/
		$('tr').each(function(){
		var tr = $(this);
		if (tr.find('td:eq(0)').text()== HeaderName

		) tr.show();
	});
}

function hideOptionName(name, value){

		//$("input[name='"+ name +"']" + "[value='" + value + "']").parent().css({ "display": "none" });
		$("input[name='"+ name +"']" + "[value='" + value + "']").parent().parent().css({ "display": "none" });
}

function hideOptionGroup(name, value){

		//$("input[name='"+ name +"']" + "[group='" + value + "']").parent().css({ "display": "none" });
		$("input[name='"+ name +"']" + "[group='" + value + "']").parent().parent().css({ "display": "none" });

}

function showOptionGroup(name, value){

		//$("input[name='"+ name +"']" + "[group='" + value + "']").parent().css({ "display": "" });
		$("input[name='"+ name +"']" + "[group='" + value + "']").parent().parent().css({ "display": "" });

}

function selectOptionGroup(group, value){

		$("input[group='"+ group +"']" + "[value='" + value + "']").prop("checked", true);

}

function PadToFour (number){
	if (number<1000 && number >=100) { number = ("0"+number.toString()); }
	if (number<100 && number >=10) { number = ("00"+number.toString()); }
	if (number<10&& number >=0) { number = ("000"+number.toString()); }
	return number;
}

function enableOption(name, value){
	$("input[name='"+ name +"']" + "[value='" + value + "']").removeAttr("disabled");
}

function enableAllOptions(name){
		$("input[name='"+ name +"']").removeAttr("disabled");
}
function SelectIfOneOption(optionsArr, groupname, pointer, handler){
	if (optionsArr.length == 1){
		SelectOption(groupname, optionsArr[0].key);
		SelectOptions();
		pointer[handler]();
	}
}

function AppendHTMLResource(selector, header, options, groupname,pointer,handler,mode){
	if(groupname != "driverstrength")
	{
		if (options.length == 1){
			$(selector).append(getResource(header, options, groupname,pointer,handler,mode,true));
		} else {
			$(selector).append(getResource(header, options, groupname,pointer,handler,mode,false));
		}
		SelectIfOneOption(options, groupname, pointer, handler);
	} else if (options.length != 1)
	{
		$(selector).append(getResource(header, options, groupname,pointer,handler,mode));
	}
}

function getErrorSpan(error){
	var tempSpan =  $('<span align = "left" style = "font-size:12px;">'+error+'</span><br/>');
	return tempSpan;
}

function getErrorsDiv(){
	var  tempdivWithError = $('<div class = "contentErrors" align = "center" style = "background:#efefef;"/>');
	return tempdivWithError;
}

function showDecoderErrorsInDiv(divWithElements){
	$("#selectorsBody").append($('<div align="left"/>')
					   .append($(divWithElements))
					   .append($('<br/>')));
}

function drawLinkWithUnrecognizedPartNamber(mode){
	if (mode == 'Decoder')
		modeMarginValue = "0px;";
	else
		modeMarginValue = "85px;";
	$("#selectorsBody").append($('<div class="linkWithUnrecognizedPartNamber" id="linkWithUnrecognizedPart" align="center" style = "background:#efefef; margin-top:'+ modeMarginValue +'"><span style="font-size:12px;">Unrecognized Part Number! Please </span><u><a href="https://www.sitime.com/company/contact-sales" target="_blank" style="font-size:12px;">contact SiTime</a></u></div>'));
}

function drawLinkWithContactQuestions(){
	$("#selectorsBody").append($('<div class="linkWithContactQuestionsclass" id="linkWithContactQuestions" align="center" style = "background:#efefef;"><span style="font-size:12px;">Please </span><u><a href="https://www.sitime.com/company/contact-sales" target="_blank" style="font-size:12px;">contact SiTime</a></u><span style="font-size:12px;"> if you have any questions</span><br><br></div>'));
}

function getResource(header, options, groupname, pointer, handler, mode, ifChecked){
	var disabledValue = "disabled";
	if(mode=="Generator" || mode=="Mixed")
		disabledValue = "";
	var trValue = $("<tr class='generatorRow'/>").addCaption(header);
	var tdValue = $("<td/>").addClass("content");
	var divValue = $("<div/>").addClass("content_wrapper");
	for (var option in options){
		if (options.hasOwnProperty(option)) {
			$(divValue).addRadioBox(groupname, options[option], pointer, handler, disabledValue, ifChecked);
		}
	}
	$(tdValue).append($(divValue))
	$(trValue).append($(tdValue));
	return $(trValue);
}

function getArrayKeyValueWithTrue(){
	var returnArray = new Array();
	returnArray.value = true;
	return returnArray;
}

function getCheckedValue(selectedValue,inputName){
	 if($("input[name="+inputName+"]:checked").val()===undefined)
		return selectedValue;
	else

		return $("input[name="+inputName+"]:checked").val()

}

function getCheckedGroup(selectedValue,inputName){
	 if($("input[name="+inputName+"]:checked").val()===undefined)
		return selectedValue;
	else

		return $("input[name="+inputName+"]:checked").attr('group');

}

function getJSONObject(familyName){
	var returnValue = null;
	$.ajax({
		url: '/themes/custom/sitime/existing-static-tools/partnumber-generator/GeneratorScripts/data.json',
		async: false,
		dataType: 'json',
		success: function (response) {
			response.families.forEach(function(entry) {
				if(entry.PartFamily == familyName){
					returnValue = entry;
				}
			});
		}
	});

	return returnValue;
}

function getDeviderValue(){
var returnValue = new Array();
returnValue.push(createArrayObject("-","-"));
return returnValue;
}

function getPartNumberWithColor(valuesArray, mode){
	var modeMarginValue;
	var trValue = $('<tr/>').addCaption("Part Number - ");
	var tdValue = $("<td/>").addClass("content");
	if (mode == "Generator")
		modeMarginValue = "20px;";
	else if (mode == 'mixed')
		modeMarginValue = "85px;";
	var divValue = $('<div id = "PartValue" align = "center" style = "margin-top:' + modeMarginValue + 'font-size:12px;background:#efefef;"/>').addClass("contentPart")
								.append($('<span ">'+'Part Number      -             '+'</span>'));
								for (var option in valuesArray){
									if(valuesArray[option].value == false)
										$(divValue).append('<span style="background:yellow;font-size:12px;">'+valuesArray[option].key+'</span>');
									else
										$(divValue).append('<span tyle="font-size:12px;">'+valuesArray[option].key+'</span>');
								}
	$(tdValue).append($(divValue));
	$(trValue).append($(divValue));
	$("#selectorsBody").append($(divValue));
}

function getFamilyDescription(value){
	var trValue = $("<tr/>").addCaption("Description");

	var tdValue = $("<td/>").addClass("content");

	var divValue = $("<div id = 'descriptioncontainer'/>").addClass("content_wrapper")
								.append($("<div style='width: 450px; text-wrap:normal; line-height: 26px;'/>")
								.append($("<td/>").append(value)));
	$(tdValue).append($(divValue));
	$(trValue).append($(tdValue));

	return $(trValue);
}

function getFrequencyResource(value,mode,pointer,isNote){
	var disabledValue = "disabled";
	if(mode=="Generator" || mode=="Mixed")
		disabledValue = "";
	//SiT1576 must be KHz
	//var currentFamily = pointer.PartFamily;
	if(isNote){
		var trValue = $("<tr/>").addCaption("Frequency (KHz)");
	}
	else{
		var trValue = $("<tr/>").addCaption("Frequency (MHz)");
	}

	var tdValue = $("<td/>").addClass("content");

	var divValue = disabledValue == "disabled" ?
		$("<div id = 'frequencycontainer'/>").addClass("content_wrapper")
			.append($("<div class='option' style='width: 200px; line-height: 26px; margin-right: 5px;'/>")
			.append($("<input name='frequency' type='text' id='frequency' autocomplete='off' maxlength='10' style='width: 160px; height: 26px; border: 1px solid #c2c2c2;'/>").val(value.key)
			.attr("disabled", disabledValue)
				.change(function(){
					pointer.OnFrequencyChange();
				})
				.keyup(function(){ pointer.OnFrequencyChange(); }))) :
		$("<div id = 'frequencycontainer'/>").addClass("content_wrapper")
			.append($("<div class='option' style='width: 200px; line-height: 26px; margin-right: 5px;'/>")
			.append($("<input name='frequency' type='text' id='frequency' autocomplete='off' maxlength='10' style='width: 160px; height: 26px; border: 1px solid #c2c2c2;'/>").val(value.key)
				.change(function(){
					pointer.OnFrequencyChange();
				})
				.keyup(function(){ pointer.OnFrequencyChange(); })));

	if(mode=="Generator" || mode=="Mixed") {
		//disabledValue = "";
		var currentFamily = pointer.PartFamily;
		try{
			if(pointer.ListOfFrequency !== undefined && pointer.ListOfFrequency.length>0){
				var info = "Supported frequencies: ";
				for ( keyVar in pointer.ListOfFrequency) {
					info = info+pointer.ListOfFrequency[keyVar]+",";
				}
				info = info.substring(0, info.length - 1);
			}
			else if (isNote){
				var info = "From "+pointer.MinFrequency+" to "+pointer.MaxFrequency+"KHz";
			}
			else{
				var info = "From "+pointer.MinFrequency+" to "+pointer.MaxFrequency+"MHz";
			}
		}
		catch(ex){var info = "From "+pointer.MinFrequency+" to "+pointer.MaxFrequency+"MHz";}
		$(divValue).children("div:last").append($("<sup/>")
			.append($("<a/>")
			.attr("href","")
			.attr("title",info)
			.click(function(){return false;})
			.append($("<img style='margin-bottom:0px' src='GeneratorScripts/images/help-16.png' alt='[i]'/>"))
			//.append($("<img src='../i/help.png' alt='[i]'/>"))
			))
			.append($("<div class='infoicon' id='frequency_error'/>")
			.append($("<a href='' class='selector_info' title='error!' onclick='return false;'>")
			.append("<img alt='(i)' src='GeneratorScripts/images/exclamation-16.png' />")));
			//.append("<img alt='(i)' src='../i/exclamation.png' />")));
	}
	//(disabledValue == "disabled") ? $(divValue).attr("disabled", disabledValue) : $(divValue).removeAttr("disabled");
	$(tdValue).append($(divValue));
	$(trValue).append($(tdValue));

	return $(trValue);
}

function getPartFamilyLabel(value,family){
	var trValue = $("<h2 style='text-align: left; font-weight: 500; font-size: 28px; color: #000;'/>").append(family+value); //added style for Part family label
	return $(trValue);
}

function getGeneratorFooter(){ $("#footerRow").remove(); //<div class ='copy' id='copydiv'><input type='submit' name='btnSubmit' id='copy' value='Copy' title='Copy the part number to clipboard' /></div>
		return  "<tr id='footerRow'><td colspan='3'>"+
				"<div id='results'><h3>SiTime Part Number:</h3><div class='input-group'><input name='partnumber' id='partnumber' style='height: 32px; border: 1px solid #c2c2c2; display:inline-block; margin-left: 20%;' type='text' value='' onkeydown = 'trigger(event.keyCode)' readonly='readonly'/>"+
				"<div id='togglediv' class='custom-control custom-switch' style='margin-right:1%; margin-top:0.5%;'><input type='checkbox' class='custom-control-input' id='modeSwitcherBox' name='example'><label style='color: gray;' class='custom-control-label' for='modeSwitcherBox'>Enable/Disable</br>Decoder mode</label></div></div>"+
				"<div id='btnsDiv'>"+
				"<div id='resetdiv'><input type='submit' class='sitime-ga-btn' name='btnSubmit' id='reset' value='Reset' title='Reset all values' style='float:left;'/></div>"+
				"<div id='copydiv'><button class='copy-button sitime-ga-btn' data-clipboard-action='copy' data-clipboard-target='#partnumber' title='Copy the part number to clipboard'>Copy</button></div>"+
				"<div id='requestdiv'><input type='submit' class='sitime-ga-btn' name='btnSubmit' id='request' value='Request Free Samples' title='Request Free Samples' style='float:left;' /></div>"+
				"<div id='inventorydiv'><input type='submit' class='sitime-ga-btn' name='btnSubmit' id='inventory' value='Search Inventory' title='Search Inventory' style='float:left;' /></div>"+
				"<div id='decodediv'><input type='submit' class='sitime-ga-btn' name='btnSubmit' id='decode' value='Decode' title='Decode part number' style='float: left; margin-left:10%; display:none'/></div></div>"+
				"<div id='DKDiv'>"+
				"<button class='sitime-ga-btn' id='buy-on-DK' type='button' style='background-color:#f69e1d'><span class='' role='status' aria-hidden='true'></span> Buy on Digi-Key </button></div>"+
				"<div id='EVB_results'><h3>SiTime Eval Board Part Number:</h3><input name='EVB_partnumber' id='EVB_partnumber' style='height: 32px; border: 1px solid #c2c2c2;' type='text' value='' readonly='readonly'/>"+
				"<div id='copydivEVB'><button class='copy-button' data-clipboard-action='copy' data-clipboard-target='#EVB_partnumber' title='Copy the EVB part number to clipboard'>Copy</button></div></div></div></td></tr>";
}

function showError(text)
{
	error = true;
	$("#frequency").css({backgroundColor: "#FCC"});
	$("#frequency_error a").attr("title", text);
	$("#frequency_error").show();
	try{
		$('#txtPartnumber').val("");
	}
	catch(Ex){}
}
function dismissError()
{
	error = false;
	if ($("#frequency").prop("disabled") != true )
		$("#frequency").css({backgroundColor: "#FFFFFF"});
	$("#frequency_error").hide();
}
//Return value from array/////Take key and array///////////////////////////////////////////////////////////////////////////
function GetValueFromArray(keyElement,array){
	var result;
	for(var element in array){
		if(array[element].key == keyElement.key)
			return array[element].value;
		else result = false
	}
	return result;
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//Return bool if selectedelement is in elementArray/////Take selectedelement and elementArray//////////////////////////////
function IsInArray(selectedelement,elementArray){
	for (var element in elementArray){
		if(elementArray[element].key==selectedelement.key)
			return true;
	}
	return false;
}

function checkArrayEntry(value, array){
	for(var elem in array){
		if (value==array[elem])
			return true;
	}
	return false;
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//Return only value without "&plus;"/////Take argument
function ParsePlusmn(element){
	try{
	if(element.length<7)
		return element;
	else
		return element.substring(8,element.length);
	}
	catch(ex){
		return element;
	}
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//Return true if argument is number/////Take argument
function isNumber(number){
	return !isNaN(parseFloat(number)) && isFinite(number);
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//Return object(element of array)/////Take key, value, width of radioBTN, info(Tip for user)
function createArrayObject(objectKey,objectValue,objectWidth,objectInfo){
	if(objectWidth===undefined){
		objectWidth = "100";
	}
	if(objectInfo===undefined){
		objectInfo = "";
	}
	return {key:objectKey,value:objectValue,width:objectWidth,info:objectInfo};
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//Return funtion name in string format/////Take funtion handler
function getFunctionName(func) {
  if ( typeof func == "function" || typeof func == "object" )
  var fName = (""+func).match(
    /function\s*([\w\$]*)\s*\(/
	);
  if ( fName !== null ) return fName[1];
}
///////Adapte part to check//////////////
function PartnumberAdapter(partnumber){
	var body;
	partnumber=partnumber.replace(/ /g,'');
	if(isNumber(partnumber.substring(0,4)))
		body = partnumber.substring(0);
	else if(partnumber.substring(0,2).toUpperCase()=="CS")
		body = partnumber.substring(0);
	else
		body = partnumber.substring(3);
	body = body.toUpperCase();
	return body;
}

function findPos(obj){
	var curleft = curtop = 0;
	if (obj.offsetParent) {
		curleft += obj.offsetLeft;
		do {
			curtop += obj.offsetTop;
		} while (obj = obj.offsetParent);
	}
	return { top: curtop, left: curleft };
}

function getErrorByErrorID(id){
	for (i = 0; i < ErrorMessages.length; i++) {
		if(id == ErrorMessages[i].key)
			return ErrorMessages[i].value;
	}
}

var ErrorMessages = [
	{
		"key":"1",
		"value":"Frequency Stability & Temperature Range combination is not supported.\n",
		"name":"FrequencyTemperatureError"
	},
	{
		"key":"2",
		"value":"Rail-to-Rail LVCMOS option is available only with DC-Coupled receiver\n",
		"name":"RTROption"
	},
	{
		"key":"3",
		"value":"AC-coupled Receiver(Output VOH) option is available only with AC-Coupled receiver\n",
		"name":"ACCoupledReceiver"
	},
	{
		"key":"4",
		"value":"Frequency Stability & Temperature Range combination is not supported.\n",
		"name":"FrequencyTemperatureError"
	},
	{
		"key":"5",
		"value":"Invalid Frequency Stability.\n",
		"name":"FrequencyStabilityError"
	},
	{
		"key":"6",
		"value":"Frequency Stability & Supply Voltage combination is not supported.\n",
		"name":"SupplyVoltageFrequencyTemperatureError"
	},
	{
		"key":"7",
		"value":"Frequency Stability & Pull range combination is not supported.\n",
		"name":"VCMOFrequencyStabilityError"
	},
	{
		"key":"8",
		"value":"FeaturePin & PackageSize combination is not supported.\n",
		"name":"PackageSizeFeaturePinError"
	},
	{
		"key":"9",
		"value":"Contact SiTime\n",
		"name":"ContactError"
	},
	{
		"key":"10",
		"value":"Invalid Pull range.\n",
		"name":"VCMOError"
	},
	{
		"key":"11",
		"value":"Spread disabled. Invalid Spread option.\n",
		"name":"SpreadOptionDisabled"
	},
	{
		"key":"12",
		"value":"SignallingType & Supply Voltage combination is not supported.\n",
		"name":"SignallingTypeSupplyVoltageError"
	},
	{
		"key":"13",
		"value":"Spread Option & Temperature combination is not supported.\n",
		"name":"SpreadOptionWithTemperatureError"
	},
	{
		"key":"14",
		"value":"Invalid AC/DC Coupling.\n",
		"name":"ACDCCouplingError"
	},
	{
		"key":"15",
		"value":"Value must be a number.\n",
		"name":"CSError"
	},
	{
		"key":"16",
		"value":"Invalid character or character position, '-' required before / after / instead of the character.\n",
		"name":"DividerError"
	},
	{
		"key":"17",
		"value":"Device Address is wrong.\n",
		"name":"DeviceAddressError"
	},
	{
		"key":"18",
		"value":"Invalid Frequency.\n",
		"name":"FrequencyError"
	},
	{
		"key":"19",
		"value":"Invalid Frequency.\n",
		"name":"FrequencyListError"
	},
	{
		"key":"20",
		"value":"Not valid frequency.\nFrequency is limited to ",
		"name":"FrequencyLengthError"
	},
	{
		"key":"21",
		"value":"Invalid Frequency Select.\n",
		"name":"FrequencySelectOptionsError"
	},

	{
		"key":"22",
		"value":" decimal places.\n",
		"name":"FrequencyLengthAfterError"
	},
	{
		"key":"23",
		"value":"FeaturePin & PackageSize combination is not supported.\n",
		"name":"FeaturePinPackageSizeError"
	},
	{
		"key":"24",
		"value":"Frequency value is wrong.\n",
		"name":"FrequencyFormatError"
	},
	{
		"key":"25",
		"value":"Invalid Feature Pin.\n",
		"name":"FeaturePinError"
	},

	{
		"key":"26",
		"value":"Invalid Drive Strength.\n",
		"name":"OutputDriverStrengthError"
	},
	{
		"key":"27",
		"value":"Invalid Output Voh.\n",
		"name":"OutputVohError"
	},
	{
		"key":"28",
		"value":"Invalid Output Vol.\n",
		"name":"OutputVolError"
	},
	{
		"key":"29",
		"value":"PackageSize & Packaging combination is not supported.\n",
		"name":"PackageSizePackagingError"
	},
	{
		"key":"30",
		"value":"Invalid Package Size.\n",
		"name":"PackageSizeError"
	},
	{
		"key":"31",
		"value":"Invalid Packaging.\n",
		"name":"PackagingError"
	},
	{
		"key":"32",
		"value":"Length of Part Number is too short.\n",
		"name":"PartLengthError"
	},
	{
		"key":"33",
		"value":"Invalid Revision Letter.\n",
		"name":"RevisionLetterError"
	},
	{
		"key":"34",
		"value":"Invalid Supply Voltage.\n",
		"name":"SupplyVoltageError"
	},
	{
		"key":"35",
		"value":"Invalid Spread Spectrum.\n",
		"name":"SpreadSpectrumError"
	},
	{
		"key":"36",
		"value":"Invalid Signalling Type.\n",
		"name":"SignallingTypeError"
	},
	{
		"key":"37",
		"value":"Invalid Swing Select.\n",
		"name":"SwingOptionsError"
	},
	{
		"key":"38",
		"value":"Invalid Temperature Range.\n",
		"name":"TemperatureRangeError"
	},
	{
		"key":"39",
		"value":"Control Pin & Pull range combination is not supported.\n",
		"name":"VCMOControlPinError"
	},
	{
		"key":"40",
		"value":"Drive Strength & Frequency combination is not supported.\n",
		"name":"VCMOControlPinError"
	},
	{
		"key":"41",
		"value":"Control Pin & DeviceAddress combination is not supported.\n",
		"name":"VCMODeviceAddressError"
	},
	{
		"key":"42",
		"value":"Invalid Serial Mode.\n",
		"name":"SerialModeError"
	},
	{
		"key":"43",
		"value":"FeaturePin & SpreadSpectrum combination is not supported.\n",
		"name":"FeaturePinSpreadSpectrumError"
	},
	{
		"key":"44",
		"value":"Special Features is wrong.\n",
		"name":"SpecialFeaturesError"
	},
	{
		"key":"45",
		"value":"SpreadOption & SpreadSpectrum combination is not supported.\n",
		"name":"SpreadOptionSpreadSpectrumError"
	},
	{
		"key":"46",
		"value":"Temperature & SerialMode combination is not supported.\n",
		"name":"SpreadOptionSpreadSpectrumError"
	},
	{
		"key":"47",
		"value":"Invalid Signalling Group.\n",
		"name":"SignallingGroupError"
	},
	{
		"key":"48",
		"value":"Invalid Reserved Option.\n",
		"name":"ReservedOptionError"
	},
	{
		"key":"49",
		"value":"SignallingGroup & SignallingType combination is not supported.\n",
		"name":"SignallingGroupSignallingTypeError"
	}
];


