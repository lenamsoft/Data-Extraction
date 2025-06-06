<?php

include "common.php";
$csv_data = read_csv('MAG_EXO.CSV', 1000);


// $html = file_get_contents('a.txt');

// $list = [];
//   $html = str_get_html($html);

//   foreach($html->find('a') as $item) {
//     //echo $item->text() . PHP_EOL;

//     if(!in_array($item->text(), $list)) {
//         $list[] = $item->text();
//         echo $item->text() . PHP_EOL;

//     }



//   }
// die();

$text = 'Source  
Name    
Main Phone  
Main Email  
Website 
Website Valid   
Email extension 
Linkedin    
Instagram   
Facebook 
X (formerly Twitter)
Pinterest
TikTok  
Youtube 
Mission 
Veteran Owned Business Y/N  
Is certified Veteran Owned Business Y/N 
Is Veteran Service Organization Y/N 
Is VA or GOV Organization Y/N   
VA or GOV Type  
VA or GOV Subtype   
Service Area 
Address Line one    
Address Line Two    
Latitude    
Longitude   
Zip Code    
Country 
State   
County  
Municipality - Place    
Notes   
Owner   
Owner Phone 
Owner Email';

$text = explode("\n", $text);

$dataRow = [];
foreach ($text as $t) {
    $dataRow[] = trim($t);
}

foreach($dataRow as $t) {
    //echo '$' . strtolower($t). ",";
}

$result = [];
$result[] = $dataRow;



$i = 0;
$duplicate = [];

//print_R($url_list);

$new_csv_data = $csv_data;

// foreach($new_csv_data as $key => $data) {

//     for($i = 5;$i<=34;$i++) {
//         unset($new_csv_data[$key][$i]);
//     }
// }


//$new_csv_data = array_slice($new_csv_data, 0 , 45);

print_R($new_csv_data[0]);


echo "Count: " .count($new_csv_data) . PHP_EOL;


$start = 0;

$end = 3;

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



foreach($new_csv_data as $key => $data) {
    //$new_csv_data[$key][] = "$key.html";

    if($key == 0) continue;
    //if($key == 6203) continue;

    
    $new_csv_data[$key][0] =  'Canada';  
$new_csv_data[$key][1] =  $list_name[$data[1]]; 
    
  
  
}





$filename = "canda-web".time().".csv";

$f = fopen($filename, "w");
foreach ($new_csv_data as $line) {
  fputcsv($f, $line);
}

die('Done');



function detectFirstPhoneNumberFromHTML($html) {
    // Regular expression pattern to match phone numbers
    $pattern = '/\b(?:\+\d{1,2}\s?)?(?:\(\d{3}\)|\d{3})[\s.-]?\d{3}[\s.-]?\d{4}\b|\(\d{3}\)\s?\d{3}-\d{4}/';

    // Match phone numbers in the HTML content
    preg_match_all($pattern, $html, $matches);

    // If phone number is found, return the first match
    if (!empty($matches[0])) {

        foreach($matches[0] as $m) {

                if(strlen($m) == 10) continue;
                if(strlen($m) < 8) continue;
                return $m;
        }
        
    }

    // If no phone number is found, return null
    return null;
}