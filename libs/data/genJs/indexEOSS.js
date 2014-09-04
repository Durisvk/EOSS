$( '#text' ).bind('keypress',function (event) {$.get('libs/eoss/RequestDealer.php',{'eoss':'indexEOSS','id':'text','event':'onkeypress','values':createJSON(),'param': event.keyCode, curValue:$(this).val()+String.fromCharCode(event.keyCode)}, function (data) {
								$( '#jsRefresh' ).html('<script src="libs/data/genJs/genFunctions.js">');
								textonkeypress(data);
							});
						});$( '#click' ).bind('click',function () {$.get('libs/eoss/RequestDealer.php',{'eoss':'indexEOSS','id':'click','event':'onclick','values':createJSON()}, function (data) {
								$( '#jsRefresh' ).html('<script src="libs/data/genJs/genFunctions.js">');
								clickonclick(data);
							});
						});