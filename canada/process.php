<?php
include "common.php";

$state_list = getUSStateAbbreviations();


$state_list['PR'] = 'Puerto Rico';
$state_list['GU'] = 'Guam';
$state_list['RI'] = 'Rhode Island';
$state_list['MP'] = 'Northern Mariana Islands';
$state_list['FM'] = 'Federated States of Micronesia';
$state_list['MH'] = 'Republic of Marshall Islands';
$state_list['PW'] = 'Republic of Palau';


unset($state_list['PI']);
unset($state_list['PH']);

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


//$url_list = read_csv('links.csv');
//print_R($url_list);

//$url_list = array_slice($url_list, 0  , 1);

$file_list = getAllHtmlFiles('html');

$i = 1;
foreach($file_list as $key => $data) {
  

if (preg_match('/html\/.+? \((.+?)\)\.html/', $data, $matches)) {
      

        $state_name_shortname  = $matches[1];
        $state_name = $state_list[$state_name_shortname];
    }

    else {

      continue;
    }


  

    


    $filename = "html/$state_name ($state_name_shortname).html";



 echo $filename;


if(!file_exists($filename)) {
  echo $filename;die();

};




  $html = file_get_contents($filename);

$html = removeScriptTags($html);
$html = removeAllImgTags($html);
$html = removeAllStyleTags($html);


  $a = explode("Economic Development Districts", $html);

if(!$a[1]) continue;
$html = str_get_html($a[1]);



  foreach($html->find('ul', 0)-> find('a') as $rowitem) {
    echo $rowitem->text() . PHP_EOL;

    $source=$name=$main_phone=$main_email=$website=$website_valid=$email_extension=$linkedin=$instagram=$facebook=$twiter = $pinterest=$tiktok=$youtube=$mission=$veteran_owned_business=$is_certified_veteran_owned_business=$is_veteran_service_organization=$is_va_or_gov_organization=$va_or_gov_type=$va_or_gov_subtype=$service_area=$address_line_one=$address_line_two=$latitude=$longitude=$zip_code=$country=$state=$county=$municipality=$notes=$owner=$owner_phone=$owner_email='';

    //$state_name_shortname  = $key;
    //$state_name = $data;
$state_name = $state_list[$state_name_shortname];
$state= $state_list[$state_name_shortname];

    $source = "https://www.eda.gov/grant-resources/economic-development-directory/$state_name_shortname";
    $source = strtolower($source);

    $country = 'United States';

    $is_va_or_gov_organization = "Y";
    $va_or_gov_type = "Economic Development";
    $va_or_gov_subtype = "Econimic Development District";


    $website = $rowitem->href;
    //$website_valid = "Y";

    $name = $rowitem->text();
    $name = str_replace('(link is external)', '', $name);

    $name = $state_name_shortname ." - " . $name;

    $owner = "$i.html";

    
    $i++;

 
    $dataRow = [$source,$name,$main_phone,$main_email,$website,$website_valid,$email_extension,$linkedin,$instagram,$facebook,$twiter, $pinterest,$tiktok,$youtube,$mission,$veteran_owned_business,$is_certified_veteran_owned_business,$is_veteran_service_organization,$is_va_or_gov_organization,$va_or_gov_type,$va_or_gov_subtype,$service_area,$address_line_one,$address_line_two,$latitude,$longitude,$zip_code,$country,$state,$county,$municipality,$notes,$owner,$owner_phone,$owner_email,];

    foreach($dataRow as &$item) $item = trim($item);
    
    //print_R($dataRow);die();


    
    $result[] = $dataRow;





  }












    
  

}





$filename = "EDD-District".time().".csv";

$f = fopen($filename, "w");
foreach ($result as $line) {
  fputcsv($f, $line);
}

die('Done');


function removeAllStyleTags($html) {
    // Regular expression to remove all <style> tags and their content
    $html = preg_replace('#<style[^>]*>.*?</style>#is', '', $html);
    return $html;
}



function removeAllImgTags($html) {
    // Regular expression to remove all <img> tags
    $html = preg_replace('/<img[^>]*>/i', '', $html);
    return $html;
}


function removeScriptTags($html) {
    $pattern = '#<script[^>]*>.*?</script>#is';
    return preg_replace($pattern, '', $html);
}
