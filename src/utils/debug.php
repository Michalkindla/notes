<?php 

// tryb scisly
declare(strict_types=1);

error_reporting(E_ALL);  //raportowanie bledow E_ALL -> wszystkie bledy
ini_set('display_errors', '1'); //to samo


function dump($data)
{
    echo '<br/><div style="display: inline-block;
                    background: lightgray;
                    padding: 0 10px;
                    border: 1px dashed gray;">
                    <pre>';
    print_r($data);
    echo '</pre></div><br/>'; 
}

