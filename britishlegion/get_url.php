<?php
include "common.php";





$result = [];
// Example usage:
$folderPath = "html1";
$htmlFiles = getAllHtmlFiles($folderPath);

// Display the list of HTML files
foreach ($htmlFiles as $file) {
    
    $html = file_get_contents($file);

    //echo $html; die();
    $html = str_get_html($html);

    foreach($html->find('.m-cards .m-card__container') as $productDetail){

      //echo $productDetail->href. "<br>";

      $url = $productDetail->href;

      $name = '';

      $name = $productDetail->find('h3', 0)->text();

      if(!$name) continue;

      if(!preg_match('/http/', $url)) {
            continue;
        }

      echo $name. $productDetail->href. "<br>";

      $dataRow = [$url,$name];
      $result[] = $dataRow;


    }

    echo "----------------<br>";
        
}


$filename = "url_list-".time().".csv";

$f = fopen($filename, "w");
foreach ($result as $line) {
  fputcsv($f, $line);
}



function getAllHtmlFiles($folderPath) {
    // Define the search pattern to find HTML files
    $searchPattern = "$folderPath/*.html";

    // Get an array of file paths matching the pattern
    $htmlFiles = glob($searchPattern);

    return $htmlFiles;
}