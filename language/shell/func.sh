#!/bin/bash

function demoFun(){
    echo "This is your first shell function!"
}

echo "Function begin..."
demoFun
echo "function end!"

funWithParam(){
    echo "first is $1 !"
    echo "second is $2!"
    echo "three is $3 !"
    echo "four is $4 !"
    echo "count of param is $#"
    echo "string param is $*"
}

funWithParam 1 2 3 4

echo $$
echo $?


funWithReturn(){
    echo "The function is to get the sum of two numbers..."
    echo -n "Input first number:"
    read aNum
    
    echo -n "Input another number:"
    read bNum
    
    echo "The two numbers are $aNum and $bNum"
    return $(($aNum+$bNum))
}
funWithReturn
echo "The sum of two numbers is $? !"

