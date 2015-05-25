#!/bin/bash

your_name='github'
greet='hello,'$your_name'!'
greet1="hello, ${your_name}!"
echo $greet $greet1

string='abcd'
echo ${#string}

string="alibaba is a great companty"
echo ${string:1:4}

echo `expr index "$string" is`
