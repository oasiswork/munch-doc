Intégration de l'éditeur Munchmail
==================================

## Installation

Il faut installer l'application sur le même domaine.

Cette application comporte un fichier d'exemple (static/test.html).

On ajoute la balise:

    <script>document.domain = document.domain;<script>

...en tout début de document (avant les inclusions d'assets externes) (pour
autoriser la communication entre iframes),

Puis…

	<script src="bootstrap.js"></script>

…avec les autres assets.

On l'execute sur le champ de formulaire correspondant et avec le template souhaité

	<script type="text/javascript">
	var editor = document.getElementById('newsletter-textarea');
	document.oasisBootstrap(editor, 'path/nom-du-template.html');
	</script>

Contrainte sur le formulaire:

* Il doit y avoir un formulaire.
* Un seul éditeur de newsletter par page.
* Le textarea est rempli au moment de l'envoie.
* Pas d'attribut required sur le textarea sans le form novalidate, sinon on passe pas dans le onsubmit du coup le textarea n'est pas rempli

## Noms de domaines

On est contraints que l'iframe soit appellée depuis un sous-domaine du nom de la
page principale.

## Configuration du CNAME

Il faut créer un CNAME qui soit un niveau en dessous du domaine de votre interface et qu'il pointe vers editeur.munchmail.net. 

Exemple, votre interface possède l'url suivante : mon-interface.com. Vous pouvez donc créer un CNAME iframe.mon-interface.com qui pointe vers editeur.munchmail.net. Ce qui donnera au niveau de vos enregistrements :

	iframe.mon-interface.com.          CNAME           editeur.munchmail.net.




