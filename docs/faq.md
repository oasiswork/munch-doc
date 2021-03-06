## FAQ

### Pourquoi un outil dédié ?

Il est théoriquement possible d'envoyer un email à de nombreux destinataires,
cependant :

- on finit vite classé en tant que spam ou rejetés (les serveurs de destination
  sont plus stricts sur les mails « massifs ») ;
- on n'a aucune idée de qui reçoit ou ne reçoit pas les mails ;
- pour chaque email non délivré, l'expéditeur se retrouve noyé par des
  emails automatiques, peu digestes et nombreux.

### Comment personnaliser le lien « Se désinscrire » ?

Un lien est inséré par défaut à la fin de votre document HTML pour permettre à
vos destinataires de se désabonner.

Si vous voulez que ce lien aie l'apparence de votre choix, il vous suffit de
mettre la chaîne `UNSUBSCRIBE_URL` là où vous voulez que l'adresse effective de
désinscription apparaisse, par exemple :

    Message ennuyeux ? Vous pouvez <a href="UNSUBSCRIBE_URL">vous désinscrire</a>


### Puis-je ajouter des pièces jointes ?


Oui, il faut toutefois que votre compte soit autorisé à le faire, contactez-nous
pour cela.

---

**⚠ Il est déconseillé d'utiliser des pièces-jointes dans des envois
en masse**. Il est beaucoup plus efficace d'héberger images et pièces-jointes
sur un serveur web et de fournir des liens. Cela allège l'envoi, l'accélère et
évite d'encombrer les boites mail de vos destinataires.

---


Pour ajouter par exemple le fichier *mon_fichier.png* sur le message `/v1/messages/1/` :

    POST /v1/attachments/

avec les paramètres *POST* suivants :

	file: mon_fichier.png
    message: http://api.crunchmail.net/v1/messages/1/

Cette requête *doit* avoir un `Content-Type` à `multipart/form-data` à l'inverse
de beaucoup d'autres qui transfèrent leur contenu en `application/json`.

Si tout va bien, vous devriez recevoir :

    HTTP 200 OK
    {
        "url": "http://api.crunchmail.net/v1/attachments/1/",
        "message": "http://api.crunchmail.net/v1/messages/1/",
        "file": "http://api.crunchmail.net/medias/attachments/1/mon_fichier.png",
        "filename": "mon_fichier.png"
    }

Les pièces-jointes sont vérifiées par un antivirus. Si un virus est détecté, une
erreur 400 est renvoyée
et le fichier rejeté, par exemple :

	HTTP 400 BAD REQUEST
    {
	   'file': ['Le fichier envoyé contient un virus : "Eicar-Test-Signature"']
    }

<!--  LocalWords:  A-minima
 -->
