#!/bin/bash

num1=100
num2=100

if test $[num1] -eq $[num2]
then
    echo 'The two numbers are equal!'
else
    echo 'The two numbers are not equal!'
fi

if test num1=num2
then
    echo 'the two strings are equal!'
else
    echo 'the two string are not equal!'
fi

cd /bin
if test -e ./bash
then
    echo 'the file already exists!'
else
    echo 'the file does not exists!'
fi
