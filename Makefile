#
# Makefile for building and installing XDMS Command Line Tool phar executable.
#

SOURCEDIR = src

SOURCES := $(shell find $(SOURCEDIR) -name '*.php')

all: xdms.phar

xdms.phar: xdms box.json $(SOURCES)
	box build -v

install: xdms.phar
	install -m 0755 -T xdms.phar $(HOME)/bin/xdms

clean: xdms.phar
	rm -f xdms.phar
