examples:
	mkdir -p docs/exemples
	echo '## PHP' > docs/exemples/php.md
	echo '```' >> docs/exemples/php.md
	cat samples/code/php/example.php >> docs/exemples/php.md
	echo '```' >> docs/exemples/php.md

publish:
	make examples
	mkdocs build
	cd site; git add --all; git commit -m "site build"; git push