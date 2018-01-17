test:
	php -dzend_extension=/usr/local/opt/php70-xdebug/xdebug.so ./vendor/bin/phpunit --coverage-html=./coverage
