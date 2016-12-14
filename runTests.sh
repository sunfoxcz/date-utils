#!/bin/bash

rm -rf tests/tmp
rm -rf tests/output
composer dump-autoload

if [ ! -d "php-parallel-lint" ]; then
    composer create-project --no-interaction jakub-onderka/php-parallel-lint php-parallel-lint ^0.9
fi

if [ ! -d "code-checker" ]; then
    composer create-project --no-interaction nette/code-checker code-checker ^2.7
fi

php php-parallel-lint/parallel-lint.php -e php,phpt src
php php-parallel-lint/parallel-lint.php -e php,phpt tests

php code-checker/src/code-checker.php --short-arrays -d src
php code-checker/src/code-checker.php --short-arrays -d tests

vendor/bin/tester -s -p php5.6 -c tests/php.ini tests
vendor/bin/tester -s -p php7.0 -c tests/php.ini tests
vendor/bin/tester -s -p php7.1 -c tests/php.ini tests
