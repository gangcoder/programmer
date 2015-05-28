<?php

 if ( $stream  =  fopen ( 'http://www.baidu.com' ,  'r' )) {
     // print all the page starting at the offset 10
     echo  stream_get_contents ( $stream , 5 ,  -1 );

     fclose ( $stream );
}
sleep(3);

if ( $stream  =  fopen ( 'http://www.baidu.net' ,  'r' )) {
     // print the first 5 bytes
     echo  stream_get_contents ( $stream ,  5 );

     fclose ( $stream );
}

 ?> 