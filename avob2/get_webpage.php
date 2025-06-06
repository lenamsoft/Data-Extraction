<?php
include "common.php";

$csv_data = read_csv('vcbc - AVOB.csv');

print_R($csv_data[0]);
// die();
$new_csv_data = $csv_data; 
$result = [];


foreach($new_csv_data as $key => $row) {
  if($key == 0) continue;

  $url = $row[4];

  if(empty($url)) continue;

  if(file_exists("website/$key.html")) continue;


  $result[] = [$url, "$key.html"];
  


  
}

$filename = "links.csv";

$f = fopen($filename, "w");
foreach ($result as $line) {
  fputcsv($f, $line);
}

die('Done');