<?php
include "common.php";

$html = file_get_contents('index.html');

$html = str_get_html($html);

$key = 0;
foreach($html->find('a.btn-primary') as $productDetail){

  

  $filename = "html/$key.html";

  $key++;

  $url = "https://business.vcbc.org.au". $productDetail->href;
  
echo $key . "$url \n";
  if($url) {
    
  
    $page = doService($url);
    file_put_contents($filename, $page);
  }





  
}






