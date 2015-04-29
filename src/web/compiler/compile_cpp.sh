#! /bin/bash

g++ $1 -o code
chmod 777 code
./code < $2
