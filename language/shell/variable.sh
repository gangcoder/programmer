#!/bin/bash

#for file in 'ls /etc'
file='/etc'
echo $file

your_name='github'
greet='hello,'$your_name'!'
greet1="hello, ${your_name}!"
echo $greet $greet1
