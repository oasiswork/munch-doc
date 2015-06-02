Exemples
==================================

##le Html contenant l'iframe

	<!doctype html>
	<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Newsletter editor</title>
		<link rel="icon" type="image/png" href="images/favicon.png">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/3.0.3/normalize.min.css">
		<style>
			h1 {
				border-bottom: 1px solid #CCC;
				padding-bottom: 0.3em;
			}
			h1,
			label {
				color: #666;
			}
			.container {
				width: 47em;
				margin: auto;
			}
			input[type="submit"] {
				background: #2e8dcd;
				color: #FFF;
				border: 0;
				padding: 0.8em 2em;
				float: right;
				margin-top: 1em;
			}
			input[type="submit"]:hover,
			input[type="submit"]:focus {
				background: #3498db;
			}
			.form-group { margin-bottom: 1.5em; }
			iframe { margin-top: 1em; }
		</style>
	</head>
	<body>
		<div class="container">
			<h1>Test d'éditeur de newsletter</h1>
			<form name="form" method="POST" onsubmit="return checkFormElements();">
				<div class="form-group">
					<label>Exemple de champ de formulaire</label>
					<textarea class="form-control" id="newsletter-textarea" name="newsletter"></textarea>
				</div>
				<div>
					<input id="test" type="submit" class="form-control"/>
				</div>
			</form>
		</div>
		<script src="bootstrap.js"></script>
		<script type="text/javascript">
		var editor = document.getElementById('newsletter-textarea');
		document.oasisBootstrap(editor, 'http://munchmail.oasiswork/mail_templates/pub/basic/basic.html');
		</script>
		<script>
		//Validation fields function
		function checkFormElements() {
			if(!document.checkTextarea()) {
				return false;
			}
		}
		</script>
	</body>
	</html>

##Le fichier bootstrap.js

	(function () {
    'use strict';
		document.oasisBootstrap = function (element, template) {
	        //hide the element
	        var widthIframe = 750;
	        element.style.display = 'none';
		    	var iframe = document.createElement('IFRAME');
	        iframe.id = "crunchIframeEditeur";
	        iframe.setAttribute('src', template);
	        iframe.style.border = '0';
	        iframe.style.width = widthIframe + 'px';
	        iframe.style.height = parseInt(window.outerHeight*0.8) + 'px';
	        element.parentNode.insertBefore(iframe, element.nextSibling);

	        var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
	        var eventer = window[eventMethod];
	        var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";

	        // Listen to message from child window
	        eventer(messageEvent,function(e) {
	          if(e.data.length > 0 && typeof e.data === 'string') {
	              element.value = e.data;
	          }
	        },false);

	        this.checkTextarea = function() {
	            //Position iframe
	            var rect = document.getElementById('crunchIframeEditeur').getBoundingClientRect();
	            var iframeTop = rect.top + document.body.scrollTop;
	            var iframeLeft = rect.left + document.body.scrollLeft;

	            var leftValue = parseFloat(widthIframe) + parseFloat(iframeLeft);
	            var divCheck = document.createElement('div');
	            divCheck.id = "checkTextarea";
	            var sheet = document.createElement('style');
	            sheet.innerHTML = '#checkTextarea{background:#f39c12;padding:1em;color:#FFF;border-radius:2px;box-shadow:0 5px 13px rgba(0,0,0,.2);width:200px;font-size:14px;text-align:center;position:absolute;opacity:0;-webkit-transition:all .2s ease;-o-transition:all .2s ease;transition:all .2s ease}#checkTextarea:before{content:"";width:0;height:0;display:block;border-style:solid;border-color:#f39c12 transparent transparent;border-width:7px;position:absolute;z-index:20;bottom:-14px;left:50%;margin-left:-7px}';
	            document.body.appendChild(sheet);
	            divCheck.textContent = "Merci de valider l'éditeur !";
	            if(element.value === "") {
	                if(document.getElementById('checkTextarea') === null) {
	                    document.body.appendChild(divCheck);
	                }
	                var heightElement = divCheck.offsetHeight;
	                var widthElement = divCheck.offsetWidth;
	                var posTop = parseFloat(iframeTop) - parseFloat(heightElement) - 4;
	                var posLeft = parseFloat(leftValue) - parseFloat(widthElement) + 40;
	                divCheck.style.cssText = 'position: absolute; top: '+posTop+'px; left: '+posLeft+'px; opacity: 1;';
	                window.scrollTo(0, posTop -50);
	                setTimeout(function(){
	                    if(divCheck.parentNode !== null) {
	                        divCheck.parentNode.removeChild(divCheck);
	                    }
	                }, 2000);
	                return false;
	            }else {
	                return true;
	            }
	        }

	    };
	    

	}());
