Intégration de l'éditeur Munchmail
==================================

## Prérequis : configuration du CNAME

Il faut créer un enregistrement DNS CNAME qui soit un niveau en dessous du
domaine de votre interface (ex: portail interne) et qu'il pointe vers
*editeur.munchmail.net*.

Exemple, votre interface possède l'url suivante : *mon-intranet.com*. Vous pouvez
donc créer un CNAME *editeur.portail.mon-interface.com* qui pointe vers
*editeur.munchmail.net*. Ce qui donnera au niveau de vos enregistrements :

	editeur.portail.mon-intranet.com.          CNAME           dentifrice.munchmail.net.

## Intégration

Ces modifications sont à effectuer dans le code de votre application (ex:
portail d'entreprise, intranet, CMS...)

<!--
// Préciser un peu le rapport avec le formulaire de la page de l'application
// tierce, et ce que va faire le script bootstrap.js dans les très grandes lignes.
-->

Cette application comporte un fichier d'exemple (*static/test.html*) [Voir les fichiers d'exemple](exemple.md). 

<!--
// PLUTOT POINTER VERS UN SAMPLE ? « cette application » n'a pas de sens, ils
// n'ont pas le code en local...
-->

On ajoute la balise:

    <script>document.domain = document.domain;<script>

...en tout début de document (avant les inclusions d'assets externes) (pour
autoriser la communication entre iframes),

Puis…

	<script src="bootstrap.js"></script>

…avec les autres assets. [Voir un exemple du fichier bootstrap](exemple.md#le-fichier-bootstrapjs)

On l'exécute sur le champ de formulaire correspondant et avec le template souhaité

	<script type="text/javascript">
	var editor = document.getElementById('newsletter-textarea');
	document.oasisBootstrap(editor, 'http://dentifrice.munchmail.net/path/nom-du-template.html');
	</script>

### Contraintes diverses sur le formulaire:

* Il doit y avoir un formulaire.
* Un seul éditeur de newsletter par page.
* Le textarea est rempli au moment de l'envoi.
* Pas d'attribut required sur le textarea sans le form novalidate, sinon on passe pas dans le onsubmit du coup le textarea n'est pas rempli
