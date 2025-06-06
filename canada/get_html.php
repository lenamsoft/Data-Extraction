<?php
include "common.php";
$list_name = [
'AB'=> 'Alberta',
'BC'=> 'British Columbia',
'MB'=> 'Manitoba',
'NB'=> 'New Brunswick',
'NL'=> 'Newfoundland and Labrador',
'NS'=> 'Nova Scotia',
'ON'=> 'Ontario',
'PE'=> 'Prince Edward Island',
'QC'=> 'Quebec',
'SK'=> 'Saskatchewan',
'NT'=> 'Northwest Territories',
'NU'=> 'Nunavut',
'YT'=> 'Yukon Territory',
];

$list = [
'AB'=> 'https://www.city-data.com/canada/Alberta-Index.html',
'BC'=> 'https://www.city-data.com/canada/British-Columbia-Index.html',
'MB'=> 'https://www.city-data.com/canada/Manitoba-Index.html',
'NB'=> 'https://www.city-data.com/canada/New-Brunswick-Index.html',
'NL'=> 'https://www.city-data.com/canada/Newfoundland-and-Labrador-Index.html',
'NS'=> 'https://www.city-data.com/canada/Nova-Scotia-Index.html',
'ON'=> 'https://www.city-data.com/canada/Ontario-Index.html',
'PE'=> 'https://www.city-data.com/canada/Prince-Edward-Island-Index.html',
'QC'=> 'https://www.city-data.com/canada/Quebec-Index.html',
'SK'=> 'https://www.city-data.com/canada/Saskatchewan-Index.html',
'NT'=> 'https://www.city-data.com/canada/Northwest-Territories-Index.html',
'NU'=> 'https://www.city-data.com/canada/Nunavut-Index.html',
'YT'=> 'https://www.city-data.com/canada/Yukon-Territory-Index.html',
];

$new_csv_data = [];
foreach($list as $state=>$url ) {
    

    $html = doService($url);


$filename = "$state.html";

//file_put_contents($filename, $html);

if(file_exists($filename)) {
      $html = file_get_contents($filename);

      $list = [];
  $html = str_get_html($html);

  foreach($html->find('.r4 a') as $item) {
    //echo $item->text() . PHP_EOL;

    if(!in_array($item->text(), $list)) {
        $list[] = $item->text();
        echo $item->text() . PHP_EOL;

        $new_csv_data[] = ['Canada', $list_name[$state], $item->text()];

    }



  }
}



}




$filename = "county-web".time().".csv";

$f = fopen($filename, "w");
foreach ($new_csv_data as $line) {
  fputcsv($f, $line);
}

die('Done');