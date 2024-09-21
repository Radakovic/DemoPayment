#!/bin/bash

echo "================================================================================"
echo "Coverage"
echo "================================================================================"
echo "Code coverage minimum: $PHPUNIT_CODE_COVERAGE_PERCENTAGE_MINIMUM%"
php .github/script/check-code-coverage.php ./clover.xml "$PHPUNIT_CODE_COVERAGE_PERCENTAGE_MINIMUM"
