#!/bin/bash

#for file in `ls /etc`
#do
#    echo $file
#done

for str in 'This is a string'
do
    echo $str
done

COUNTER=0 
while [$COUNTER -lt 5]
do
    COUNTER=`expr $COUNTER+1`
    echo $COUNTER
done


echo 'Input a number betweent 1 to 4'
echo 'Your number is:\c'
read aNum

case $aNum in
    1) echo 'You select 1'
    ;;
    2) echo 'You select 2'
    ;;
    3) echo 'You select 3'
    ;;
    4) echo 'You select 4'
    ;;
    *) echo 'You do not select a number between 1 to 4'
    ;;
esac

while :
do

    echo -n "Input a number between 1 to 5:"
    read aNum
    case $aNum in
    1|2|3|4|5)
        echo "Your number is $aNum!"
    ;;
    *)
        echo 'You do not'
        break
    ;;
    esac
done
