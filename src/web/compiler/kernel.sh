#! /bin/bash

echo $2 > compiler/file_input
echo $3 > compiler/file_output

if [[ $4 = "c" ]];then
    echo $1 > compiler/main.c
    compile=`compiler/filtr_c.sh $1`
    if [[ $compile = "ok" ]];then
        res=`compiler/compile_c.sh compiler/main.c compiler/file_input`
        if [[ $res = "" ]]; then
            echo "erreur de compilation !"
        else
            echo $res > compiler/file
            cmp -s compiler/file compiler/file_output
            if [ $? -eq 0 ] ; then
                echo "Bravo!"
            else
                echo "Wrong Answer"
            fi
        fi
    else
        echo "votre code peuve  de sécurité !"
    fi
elif [[ $4 = "cpp" ]]; then
    echo $1 > compiler/main.cpp
    compile=`compiler/filtr_cpp.sh $1`
    if [[ $compile = "ok" ]]; then
        res=`compiler/compile_cpp.sh compiler/main.cpp compiler/file_input`
        if [[ $res = "" ]]; then
        echo "erreur de compilation !"
        else
        echo $res > file
        cmp -s file compiler/file_output
            if [ $? -eq 0 ] ; then
            echo "Bravo!"
            else
            echo "Wrong Answer"
            fi
        fi
    else
        echo "votre code peuve  de sécurité !"
    fi

elif [[ $4 = "java" ]]; then
    echo $1 > compiler/main_java.java
    compile=`compiler/filtr_java.sh $1`
    if [[ $compile = "ok" ]]; then
        res=`compiler/compile_java.sh compiler/main_java.java compiler/file_input`
        if [[ $res = "erreur de compilation" ]]; then
            echo "$res"
        else
            echo $res > file
            cmp -s file compiler/file_output
            if [ $? -eq 0 ] ; then
                echo "Bravo !"
            else
                echo "Wrong Answer"
            fi
        fi
    else
        echo "votre code peuve  de sécurité !"
    fi
elif [[ $4 = "python" ]]; then
    echo $1 > compiler/main.py
    compile=`./filtr_py.sh $1`
    if [[ $compile = "ok" ]]; then
        res=`compiler/compile_py.sh compiler/main.py compiler/file_input`
        if [[ $res = "" ]]; then
            echo "erreur de compilation !"
        else
            echo $res > file
            cmp -s file compiler/file_output
            if [ $? -eq 0 ] ; then
                echo "Bravo !"
            else
                echo "Wrong Answer"
            fi
        fi
    else
        echo "votre code peuve  de sécurité !"
    fi
elif [[ $4 = "bash" ]]; then
    echo $1 > compiler/main.sh
    compile=`./filtr_sh.sh $1`
    if [[ $compile = "ok" ]]; then
        res=`compiler/compile_sh.sh compiler/main.sh compiler/file_input`
        if [[ $res = "" ]]; then
            echo "erreur de compilation !"
        else
            echo $res > file
            cmp -s file compiler/file_output
            if [ $? -eq 0 ] ; then
                echo "Bravo !"
            else
                echo "Wrong Answer"
            fi
        fi
    else
        echo "votre code peuve  de sécurité !"
    fi
fi
