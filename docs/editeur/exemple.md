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
	    <script>
	    document.domain = document.domain;
	    </script>

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
	</head>
	<body>
		<div class="row">
			<div class="col-md-6">
				<h1>Test d'éditeur de newsletter</h1>
				<form name="form" method="POST">
					<div class="form-group">
						<label>Exemple de champ de formulaire</label>
						<textarea class="form-control" id="newsletter-textarea" name="newsletter"></textarea>
					</div>
					<div class="form-group">
						<input type="submit" class="form-control"/>
					</div>
				</form>
			</div>
		</div>
		<script src="bootstrap.js"></script>
		<script type="text/javascript">
		var editor = document.getElementById('newsletter-textarea');
		document.oasisBootstrap(editor, 'http://dentifrice.munchmail.net/path/nom-du-template.html');
		</script>
	</body>
	</html>

##Le fichier bootstrap.js

	(function () {
	    'use strict';
	    var SRC = 'http://editeur.portail.mon-intranet.com/';
		document.oasisBootstrap = function (element, template) {
	        //hide the element
	        element.style.display = 'none';
		    var iframe = document.createElement('IFRAME');
	        iframe.setAttribute('src', SRC + template);
	        iframe.style.width = 720 + 'px';
	        iframe.style.height = 480 + 'px';
	        element.parentNode.insertBefore(iframe, element.nextSibling);

	        var parseXml;
	        var parser;
	        var serializer = new XMLSerializer();

	        if (typeof window.DOMParser !== 'undefined') {
	            parser = new window.DOMParser();
	            parseXml = function(xmlStr) {
	                return parser.parseFromString(xmlStr, 'application/xml');
	            };
	        } else if (typeof window.ActiveXObject !== 'undefined') {
	            parseXml = function(xmlStr) {
	                var xmlDoc = new window.ActiveXObject('Microsoft.XMLDOM');
	                xmlDoc.async = 'false';
	                xmlDoc.loadXML(xmlStr);
	                return xmlDoc;
	            };
	        } else {
	            throw new Error('No XML parser found');
	        }
	        /**
	         * Return parents of the element in a array
	         */
	        function getParents(el) {
	            var parents = [];

	            var p = el.parentNode;

	            while (p !== null) {
	                var o = p;
	                parents.push(o);
	                p = o.parentNode;
	            }
	            return parents; // returns an Array []
	        }
	        var parents = getParents(element);
	        var form;
	        for (var i = 0; i < parents.length; i++) {
	            if (parents[i].tagName === 'FORM') {
	                form = parents[i];
	            }
	        }

	        function oasisSubmit () {
	            var content = iframe.contentDocument || iframe.contentWindow.document;
	            content.destroyNewsletterEditor();
	            var contentString = serializer.serializeToString(content);
	            element.value = contentString;
	            return true;
	        }
	        if (form) {
	            form.onsubmit = oasisSubmit;
	        }
	    };

	}());
