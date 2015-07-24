<?php

/**
* a
*/
class a
{
    protected $c;
    function a()
    {
        echo "a";
        $this->c = 10;
    }
}

/**
* b
*/
class b extends a
{
    
    function print_data()
    {
        return $this->c;
    }
}
$b = new b();

echo $b->print_data();