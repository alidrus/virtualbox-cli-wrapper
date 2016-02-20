#
# Makefile for building and installing XDMS Command Line Tool phar executable.
#

SOURCEDIR = src

SOURCES := $(shell find $(SOURCEDIR) -name '*.php')

all: vbox.phar

vbox.phar: vbox box.json $(SOURCES)
	box build -v

install: vbox.phar
	install -m 0755 -T vbox.phar $(HOME)/bin/vbox

clean:
	rm -f vbox.phar
