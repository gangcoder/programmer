#!/bin/bash

echo 'type <CTRL-D> to terminate'
echo -n 'Enter your most like film:'
while read FILE
do
    echo "Yeah! great film the $FILE"
done
