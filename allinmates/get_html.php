<?php
include "common.php";

$state_list = getUSStateAbbreviations();
$k = 1;
 for($i = 0; $i<=19; $i++) {
$url = "https://allinmates.net/Alabama-prison-facilities/?page=$i";
  if($url) {
    
    
    $page = doService($url);

    $html = str_get_html($page);


foreach($html->find('#prisons a') as $productDetail){

      //echo $productDetail->href. "<br>";

      $url = $productDetail->href;

      $url = "https://allinmates.net".$url;

      echo $url."\n";

      $html = doService($url);

  file_put_contents("html/AL-".$k.".html", $html);

  

      $k++;


    }

   
  }

 }
  





  







