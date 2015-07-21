Publipostage
============

Le publipostage consiste à personnaliser chaque email avec des informations
relatives à l'utilisateur (ex: prénom, âge...).


## Ajouter des informations

Il est nécessaire d'indiquer à l'API les informations qu'on veut utiliser pour
le publipostage, cela se passe au moment de
l'[ajout des destinataires](../tutoriel/#2-ajoutmodification-dune-liste-de-destinataires),
via le champ `properties`.
:

    POST /v1/mails/
    [
	    {"to" : "john@domaine.tld",     "message": "https://api.munchmail.net/v1/messages/4/",
         "properties" : {"first_name": "John", "second_name": "Doe" }},
	    {"to" : "jane@domaine.tld",     "message": "https://api.munchmail.net/v1/messages/4/",
        "properties" : {"first_name": "Jane", "second_name": "Doe" }},
	    {"to" : "fox@autredomaine.tld", "message": "https://api.munchmail.net/v1/messages/4/",
        "properties" : {"first_name": "Fox", "second_name": "Durenard" }},
    ]

Le contenu de `properties` est un ensemble de *clefs/valeurs* entièrement
libres.

## Inclure ces informations

Il est nécessaire de mettre des balises spécifiques dans le code HTML que vous
fournissez, par exemple, pour afficher l'attribut `first_name` défini dans
l'exemple précédent, il faudra mettre dans l'attribut `html` du *message*, un
contenu comme :

    <h1>Bonjour {{ first_name }} !</h1>

Ce qui donnera, dans la boite mail de votre premier destinataire :

    <h1>Bonjour John !</h1>

----

*ⓘ Pour des usages avancés, sachez que la syntaxe à base d'accolades  est
celle du
[langage de templates django](https://docs.djangoproject.com/en/dev/ref/templates/language/),
et qu'il est possible d'en utiliser toutes les fonctionnalités, notamment
[les filtres](https://docs.djangoproject.com/en/dev/ref/templates/builtins/).*

----
