.PHONY: coverage

serve:
	php -S localhost:8000 public/index.php
install:
	composer install
test:
	vendor/bin/phpunit --color
	vendor/bin/phpcs --standard=PSR2 -p --colors src tests
	vendor/bin/phpstan analyse -l 7 src
	vendor/bin/phpstan analyse -c phpstan.tests.neon -l 5 tests
coverage:
	$(eval TMPDIR=$(shell mktemp -d))
	vendor/bin/phpunit --coverage-html=$(TMPDIR)
	gnome-www-browser $(TMPDIR)/index.html
