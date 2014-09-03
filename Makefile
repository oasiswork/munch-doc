examples:
	mkdir -p docs/exemples
	echo '## PHP' > docs/exemples/php.md
	echo '```' >> docs/exemples/php.md
	cat samples/code/php/example.php >> docs/exemples/php.md
	echo '```' >> docs/exemples/php.md

