#!/bin/bash

rm -rf tests/tmp
rm -rf tests/output
composer dump-autoload

if [ ! -d "php-parallel-lint" ]; then
    composer create-project --no-interaction jakub-onderka/php-parallel-lint php-parallel-lint ^0.9
fi

php php-parallel-lint/parallel-lint.php -e php,phpt src tests

vendor/bin/tester -s -p php5.6 -c tests/php.ini tests
vendor/bin/tester -s -p php7.0 -c tests/php.ini tests
vendor/bin/tester -s -p php7.1 -c tests/php.ini tests
