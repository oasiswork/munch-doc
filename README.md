# MunchMail Documentation

Working :

    mkvirtualenv mkdocs
    pip install mkdocs
    make examples
    mkdocs serve

Checking for broken links:

    make check_links

Setup github pages :

    mkdir site
    cd site
    git clone <repo URL> .
    git checkout gh-pages
    git branch -d master
    cd ..

Build & push current version to gh-pages:

    make publish
