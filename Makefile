#!/usr/bin/make -f

GLOBAL_CONFIG := -d memory_limit=-1

# -----------------------------------------------------------------------------

phpcs: clean-build-phpcs build/phpcs.xml

clean-build-phpcs:
	rm -rf build/phpcs.xml

build/phpcs.xml:
	mkdir -p build
	php ${GLOBAL_CONFIG} vendor/bin/phpcs --report-junit=build/phpcs.xml
