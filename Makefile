composer-normalize:
	/usr/local/bin/composer normalize --dry-run

composer-validate:
	/usr/local/bin/composer validate

cs:
	vendor/bin/phpcs

fix_cs:
	vendor/bin/phpcbf

psalm:
	vendor/bin/psalm

stan:
	vendor/bin/phpstan analyse

test:
	vendor/bin/pest

ci: composer-normalize composer-validate cs stan psalm test
