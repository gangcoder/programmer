arrayname=(1 2 3 4 5 6 7)
ass[0]=1
ass[2]=2

echo ${arrayname[3]}
echo ${ass[2]}

length=${#arrayname[@]}

echo $length
echo ${#arrayname[*]}
echo ${#ass[2]}
