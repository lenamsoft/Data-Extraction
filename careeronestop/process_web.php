<?php
include "common.php";



$text = 'Source  
Name    
Main Phone  
Main Email  
Website 
Website Valid   
Email extension 
Linkedin    
Instagram   
Facebook X (formerly Twitter)
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

//$url_list = array_slice($url_list, 0  , 1);
$files = glob('./html/*.html');

foreach($files as $key => $file) {


    //$url = $data[0];
    //$state_name = $data[1];

    //$file = "html/$key.html";


  

  $id = str_replace("./html/", "", $file);
  $id = str_replace(".html", "", $id);

  $web_file = "website/$id.html";

  // echo $web_file;die();

  if(file_exists($web_file)) continue;


  $html_source = file_get_contents($file);

 if(!$html_source) continue;

  $html = str_get_html($html_source);
  $tr = $html->find('.detail-table tr');


  foreach ($tr as $row) {

        if(preg_match('/Website/', $row->innertext())) {


            if($url = $row->find('a', 0)->href) {
                echo $url . "\n";
                $page = doService($url);
                file_put_contents($web_file, $page);

            }
        }
    }




}