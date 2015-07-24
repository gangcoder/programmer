<?php
function array_sort($arr, $row, $type = 'asc')
{
    $arrtemp = array();

    foreach ($arr as $key => $value) {
        $arrtemp[$value[$row]] = $value;
    }
    print_r($arrtemp);

    if ($type == 'asc') {
        ksort($arrtemp);
    } elseif ($type == 'desc') {
        krsort($arrtemp);
    }

    return $arrtemp;
}

$person = [
    ['id' => 2, 'name' => 'beijing', 'age' => '4'],
    ['id' => 3, 'name' => 'shanghai', 'age' => '2'],
    ['id' => 12, 'name' => 'qingdao', 'age' => '3'],
];

print_r(array_sort($person, 'name'));