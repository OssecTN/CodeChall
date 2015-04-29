#! /bin/bash

gcc $1 -o compiler/code
chmod 777 compiler/code
compiler/code < $2
rm compiler/code
