<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

function dump($data)
{
    echo '<br/><div 
    style="
    z-index: 2;
    display: inline-block; 
    padding: 3px 5px; 
    border: 1px dashed black; 
    background: lightgray;
"
>
<pre>';
    print_r($data);
    echo '</pre>
    </div><br/>';
}
