<?php
include "common.php";


$file = 'index.html';
$html = file_get_contents($file);

    //echo $html; die();
$html = str_get_html($html);
$result = [];
$i = 1;


foreach($html->find('a') as $productDetail){

      //echo $productDetail->href. "<br>";

    echo $productDetail->href;
    //die();

      $url = $productDetail->href;

      $name = '';

      //$name = $productDetail->find('.state-span', 0)->text();

      //if(!$name) continue;

      if(!preg_match('/business-profile/', $url)) {
            continue;
        }

      echo $name. $productDetail->href. "<br>";

      $dataRow = [$url, "$i.html"];
      $result[] = $dataRow;

     // $html = doService($url);

  //file_put_contents("html/$i.html", $html);
 
 //die();
  

      $i++;


    }


    //print_R($result);

//die();


$filename = "links.csv";

$f = fopen($filename, "w");
foreach ($result as $line) {
  fputcsv($f, $line);
}



