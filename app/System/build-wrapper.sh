#!/bin/bash

#This builds the wrapper and sets the correct bits for setuid.

mkdir /usr/local/bin/sitegate

cp sitegate-wrapper.php /usr/local/bin/sitegate
gcc sitegate-wrapper.c -o /usr/local/bin/sitegate/sitegate-wrapper

cd /usr/local/bin/sitegate

chown root:www-data sitegate-wrapper
chmod 4550 sitegate-wrapper

chown root:www-data sitegate-wrapper.php
chmod 400 sitegate-wrapper.php
