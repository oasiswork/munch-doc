Instance de test/démonstration
==============================

Durant la phase de tests et de développement de votre application, nous vous
encourageons à utiliser l'instance publique de démonstration :

**[https://demo.munchmail.net](https://demo.munchmail.net)**

Il est identique au serveur de production, mais :

- Il n'envoie pas réellement les mails
- Il est effacé périodiquement

Authentification
----------------

L'authentification est similaire à celle de l'API réelle, mais au lieu de votre
certificat, vous utilisez un *certificat de démonstration*. Le *fichier de CA*
permet à votre navigateur d'authentifier notre serveur de démonstration.

* Certificat client (format pkcs12):
  [demo-client-cert.p12](/files/ssl/demo-client-cert.p12) ou
  [demo-client-cert.pem](/files/ssl/demo-client-cert.pem)
* Fichier de CA : [demo-ca.pem](/files/ssl/demo-ca.pem)


Exploration de l'API
-------------------

L'API est *explorable* : en ouvrant votre navigateur sur
https://demo.munchmail.net, vous avez une version graphique de l'API que vous
pouvez utiliser tester depuis le navigateur pour découvrir ou débuguer par
exemple.

Si vous voulez tester l'API, vous devez installer [le certificat client de
démonstration](/files/ssl/demo-client-cert.p12) dans votre navigateur.

### Chrome

*Paramètres → Afficher les paramètres avancés → Gérer les certificats  →
 Importer*

(laisser le champ vide quand un mot de passe vous est demandé)

### Firefox

*Édition → Préférences → Avancé → Afficher les certificats → Importer*

(laisser le champ vide quand un mot de passe vous est demandé)

