Authentification
================

La communication est chiffrée via HTTPS.

* Nous **authentifions votre application** (*clef d'API*) et isolons vos données
  de celles de nos autres clients
* Votre application **authentifie notre serveur** (*certificat serveur*)
* Le traffic entre votre application et notre serveur est **chiffré**

## Authentification par session (pour tests/debug)

Vous pouvez vous authentifier avec votre email et votre mot de passe, dans un
navigateur, à l'adresse
[https://api.munchmail.net/v1/api-auth/login/](https://api.munchmail.net/v1/api-auth/login/).

## Par clef d'API (HTTP Basic)

### Récupérer la clef d'API

Une fois connecté via la méthode précédente, vous pouvez vous rendre à l'adresse
[https://api.munchmail.net/v1/profile/](https://api.munchmail.net/v1/profile/)
pour récupérer la clef d'API de votre propre utilisateur humain.

Vous également aussi récupérer la clef d'une application via
[https://api.munchmail.net/v1/users/](https://api.munchmail.net/v1/users/apps/)

### S'authentifier

L'authentification de cette API s'appuie sur le mécanisme standard
[HTTP Basic](https://fr.wikipedia.org/wiki/Authentification_HTTP#M.C3.A9thode_Basic),

Référez-vous à la documentation de votre langage de programmation pour savoir
comment utiliser l'authentification *HTTP Basic*.

Les détails à fournir sont :

* login : *« api »*
* password : votre clef d'API (commençant par *« key- »*)

Les détails d'authentification sont à attacher à chaque requête.

L'authentification est abordée notamment dans nos
[exemples pour PHP](/exemples/php/).
