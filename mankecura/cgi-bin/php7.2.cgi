#!/bin/bash
PHPRC=$DOCUMENT_ROOT/../etc/php7.2
export PHPRC
umask 022
exec /bin/php
