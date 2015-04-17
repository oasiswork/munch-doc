examples:
	mkdir -p docs/exemples
	echo '## PHP' > docs/exemples/php.md
	echo '```' >> docs/exemples/php.md
	cat samples/code/php/example.php >> docs/exemples/php.md
	echo '```' >> docs/exemples/php.md

publish:
	make examples
	mkdocs build
	cd site; git pull; git add --all; git commit -m "site build"; git push

check_links:
	echo 'Dead links :'
	wget --spider -r -p http://localhost:8092 2>&1 |grep '^http://'
