#! /bin/bash
javac $1
if [ $? -eq 0 ] ; then
    java main_java < $2
else
    echo "erreur de compilation"
fi
