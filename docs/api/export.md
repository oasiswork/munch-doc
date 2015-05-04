Export et formats
=================

Par défaut, l'API sert le contenu en JSON, il est également possible de le
servir au format [CSV](https://fr.wikipedia.org/wiki/Comma-separated_values).

Les URLs restent les mêmes que pour les données en JSON, deux méthodes au choix
pour changer le format de sortie :

- Positionner l'en-tête `Accept: text/csv` à la requête
- ajouter un paramètre d'URL `?format=csv` à l'URL désirée

Par exemple :

```text
GET https://api.munchmail.net/v1/messages/3/opt_outs/

address,category,date,origin
jane-blacklist@example.com,https://api.munchmail.net/v1/categories/1/,2014-08-08T12:54:35Z,mail
john-greylist@example.com,https://api.munchmail.net/v1/categories/1/,2014-08-08T12:55:16Z,feedback-loop
```

## Notes de conversion

- La première ligne est une légende
- les dictionaires sont aplatis `{"details": {"eyes":"blue", "height": "tall"}}` devient

```text
details.eyes,details.height
blue,tall
```

- les listes sont présentées telles quelles, `{"fruits": ["apple", "banana"]}` devient

```text
fruits
"[""apple"", ""banana""]"
```
