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
Facebook 
X
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

$files = getAllHtmlFiles("html");

//print_R($files);die();

for($i = 1 ; $i<=13; $i++) {

  $key = $i;

    $file = "html/$key.html";


  $html = file_get_contents($file);
  $html = str_get_html($html);

  $tr = $html->find('.cb-collection-item');
  foreach ($tr as $row) {


            $source=$name=$main_phone=$main_email=$website=$website_valid=$email_extension=$linkedin=$instagram=$facebook= $twitter =$pinterest=$tiktok=$youtube=$mission=$veteran_owned_business=$is_certified_veteran_owned_business=$is_veteran_service_organization=$is_va_or_gov_organization=$va_or_gov_type=$va_or_gov_subtype=$service_area=$address_line_one=$address_line_two=$latitude=$longitude=$zip_code=$country=$state=$county=$municipality=$notes=$owner=$owner_phone=$owner_email='';

            $source = "https://www.avob.org.au/certified-businesses";
            $country = 'Australia';

            
            $name = $row->find('.business-title', 0)->plaintext;
            $website = $row->find('.website-link', 0)->plaintext;
            

            foreach ($row->find('.social-media-filter-wrapper a') as $key => $value) {
              $href = $value->href;
              if ($href != '#' && strpos($href, 'linkedin') !== false ) {
                $linkedin = $href;
              }elseif ($href != '#' && strpos($href, 'instagram') !== false ) {
                $instagram = $href;
              }elseif ($href != '#' && strpos($href, 'facebook') !== false ) {
                $facebook = $href;
              }elseif ($href != '#' && strpos($href, 'twitter') !== false ) {
                $twitter = $href;
              }elseif ($href != '#' && strpos($href, 'pinterest') !== false ) {
                $row_csv[10] = $href;
              }elseif ($href != '#' && strpos($href, 'tikTok') !== false ) {
                $tiktok = $href;
              }elseif($href != '#' && strpos($href, 'youtube') !== false ) {
                $youtube = $href;
              }
            }

           $dataRow = [$source,$name,$main_phone,$main_email,$website,$website_valid,$email_extension,$linkedin,$instagram,$facebook,$twitter,$pinterest,$tiktok,$youtube,$mission,$veteran_owned_business,$is_certified_veteran_owned_business,$is_veteran_service_organization,$is_va_or_gov_organization,$va_or_gov_type,$va_or_gov_subtype,$service_area,$address_line_one,$address_line_two,$latitude,$longitude,$zip_code,$country,$state,$county,$municipality,$notes,$owner,$owner_phone,$owner_email,];

                foreach($dataRow as &$item) $item = trim((string)$item);
            $result[] = $dataRow;

    }

    
    



    
    
  }


  






$filename = "avob-".time().".csv";

$f = fopen($filename, "w");
foreach ($result as $line) {
  fputcsv($f, $line);
}

die('Done');


