var global_fromSeparatePage = false;
var global_done = false;

function setElementVisibility(element, show){
 if (show) {
   element.style.visibility = "visible";
 }
 else{
     element.style.visibility = "collapse";
 }
}

function hidePNC(){
	setElementVisibility(document.getElementById("PNC_TR"), false);
}

function showPNC(urlToPNC){
	var pncHeight = '500px';
	
	document.getElementById('txtPartnumber').value = "";
	
	if (urlToPNC.match("8003XT") != null) {
		pncHeight = '500px';
	}
	else if (urlToPNC.match("9001") != null) {
		pncHeight = '500px';
	}
	else if (urlToPNC.match("9002") != null) {
		pncHeight = '500px';
	}
	else if (urlToPNC.match("9003") != null) {
		pncHeight = '500px';
	}
	else if (urlToPNC.match("9102") != null) {
		pncHeight = '500px';
	}
	else if (urlToPNC.match("9") != null) {
		pncHeight = '500px';
	}
	else if (urlToPNC.match("3621") != null) {
		pncHeight = '700px';
	}
	
	var elementFrame = document.getElementById('framePNC');
	elementFrame.style.height = pncHeight;
	elementFrame.src = urlToPNC;
}

function showHidePNC(urlToPNC)
{
	showPNC(urlToPNC);
}

function getParameters( name )
{
  name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
  var regexS = "[\\?&]"+name+"=([^&#]*)";
  var regex = new RegExp( regexS );
  var results = regex.exec( window.location.href );
  if( results == null )
    return null;
  else  
      return results[1];
}

function OpenPage(url){
	window.open(url);
}

function PlusMinButtonClick(){
	var chose_buttton_toggler = document.querySelector(".circle-plus");
	
    if($(".generatorDiv").css('display') == "none"){
		
	   chose_buttton_toggler.classList.toggle("opened");
	   chose_buttton_toggler.classList.toggle("closed");
	   
	   $('.generatorDiv').each(function() {
		 $(this).insertBefore($(this).parent().find('.selectedPartnumber'));
	   });
	   $(".generatorDiv").css('margin-top','2%');
       $(".generatorDiv").slideDown(700, "swing");
	   $(".choseOscillatorIcon").text("-");
    }else if($(".generatorDiv").css('display') == "block"){
	   chose_buttton_toggler.classList.toggle("closed");
	   chose_buttton_toggler.classList.toggle("opened");
	   
	   if (!global_done && global_fromSeparatePage){
			global_done = true;
			$(".generatorDiv").hide();
	   }
	   else{
		   $(".generatorDiv").slideUp(700, "swing");
		   $(".choseOscillatorIcon").text("+");
	   }
    }

}

function CheckPN(){
	var partNumber = getParameters('product');	
	if(partNumber != "" && partNumber != null){
		var tempPN = partNumber.substring(3, 7);
		if(tempPN == "8003" && getParameters('xt') != null)
			tempPN = tempPN + 'XT';
		if($('input:radio[name="radio"]').filter("[value='SiT" + tempPN + "']").val())
		{	
			global_fromSeparatePage = true;
			//Workaround to reduce blink effect when user try to enter PNG from product page.
			$('#tab-container').css('visibility', 'visible');		
			
			$('.buttonPack').css("display", "");
			//hide PNG table if user comes from Parametric search or product page.			
			//$(".generatorDiv").hide();
			//document.getElementById('txtPartnumber').value = partNumber;
			$('input:radio[name="radio"]').filter("[value='SiT" + tempPN + "']").trigger('click');			
		}
		else{
			ShowDefault();
		}
	}
	else{
		ShowDefault();
	}
}
function OnLoad() {
    myVar = setInterval(CheckPN(), 5);
}
function ShowDefault(){
		$('.buttonPack').css("display", "");
		//Workaround to reduce blink effect when user try to enter PNG from product page.
		$('#tab-container').css('visibility', 'visible');
		//showHidePNC('/products/partnumber-generator/8008B/index.html?fromSeparatePage=yes');
		//$('input:radio[name="radio"]').filter("[value='SiT1602']").trigger('click')
}
