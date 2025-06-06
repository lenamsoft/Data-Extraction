<?php
include "common.php";

// $url = "https://www.afvbc.com/afvbc-uk-breakfast-clubs";
// $page = doService($url);
//   file_put_contents("index.html", $page);

// die();
$html = file_get_contents('index.html');

$html = str_get_html($html);

$key = 0;
foreach($html->find('a') as $productDetail){

  

  $filename = "html/$key.html";

  $key++;

  $url = "https://www.afvbc.com". $productDetail->href;
  
echo $key . "$url \n";
  if($url) {
    
  
    $page = doService($url);
    file_put_contents($filename, $page);
  }





  
}






