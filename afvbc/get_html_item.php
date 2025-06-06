<?php
include "common.php";

$state_list = getUSStateAbbreviations();


$state_list = [];
$state_list['PR'] = 'Puerto Rico';

$state_list['GU'] = 'Guam';
$state_list['RI'] = 'Rhode Island';
$state_list['MP'] = 'NORTHERN MARIANA IS';



foreach($state_list as $state_short=> $name) {

if(preg_match('/ /', $name)) {
            $name = urlencode($name);
        }

        else continue;


    $url = "https://www.careeronestop.org/localhelp/americanjobcenters/find-american-job-centers.aspx?&location=".strtolower($name)."&radius=100&curPage=1&pagesize=500";

    $html = doService($url);

    file_put_contents("html_state/$state_short.html", $html);

    echo $url. PHP_EOL;


}

die();


$file = 'index.html';
$html = file_get_contents($file);

    //echo $html; die();
$html = str_get_html($html);

$i = 0;
foreach($html->find('a.chamber-finder__state-link') as $productDetail){

      //echo $productDetail->href. "<br>";

      $url = $productDetail->href;

      $name = '';

      $name = $productDetail->find('.state-span', 0)->text();

      if(!$name) continue;

      if(!preg_match('/http/', $url)) {
            continue;
        }

      echo $name. $productDetail->href. "<br>";

      $dataRow = [$url,$name];
      $result[] = $dataRow;

      $html = doService($url);

  file_put_contents("html/$i.html", $html);

  

      $i++;


    }


    //print_R($result);

//die();


$filename = "url_list.csv";

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