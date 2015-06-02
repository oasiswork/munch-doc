Intégration de l'éditeur Munchmail
==================================

## Intégration

Ces modifications sont à effectuer dans le code de votre application (ex:
portail d'entreprise, intranet, CMS...)

Cette application comporte un fichier d'exemple (*static/test.html*) [Voir les fichiers d'exemple](exemple.md). 

On ajoute la balise:

	<script src="bootstrap.js"></script>

…avec les autres assets. [Voir un exemple du fichier bootstrap](exemple.md#le-fichier-bootstrapjs)

On l'exécute sur le champ de formulaire correspondant et avec le template souhaité :

	<script type="text/javascript">
	var editor = document.getElementById('newsletter-textarea');
	document.oasisBootstrap(editor, 'https://dentifrice.munchmail.net/path/nom-du-template.html');
	</script>

## Validation du champ textarea du formulaire

Afin d'éviter que le formulaire soit soumis sans validation du textarea qui contient le html du template, il faut faire appel à la méthode suivante (présente dans bootstrap.js) :

	document.checkTextarea()

Il faut placer cette méthode dans la fonction exécutée lors de la soumission du formulaire.

Exemple dans une fonction présente sur la soumission du formulaire :

	function checkFormElements() {
		if(!document.checkTextarea()) {
			return false;
		}
	}

### Contraintes diverses sur le formulaire:

* Il doit y avoir un formulaire.
* Un seul éditeur de newsletter par page.
* Le textarea est rempli au moment de l'envoi.
