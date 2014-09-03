# MunchMail Documentation

Working :

* mkvirtualenv mkdocs
* pip install mkdocs
* make examples
* mkdocs serve

Site Builds :

* mkdir site
* cd site
* git clone <repo URL>
* git checkout gh-pages
* cd ..
* mkdocs build
* cd site
* git add --all && git commit -m "site build" && git push
