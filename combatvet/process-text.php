<?php
include "common.php";
include "vendor/autoload.php";

$csv_data = read_csv('combatvet.csv');


$state_list = getUSStateAbbreviations();




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

$new_csv_data = $csv_data;

mkdir('website_text');
mkdir('website_text_short');

$i = 1;
foreach($new_csv_data as $key => $data) {
  
   
  if($key == 0) continue;

    //if($key != 397) continue;

    //if($key>$end) continue;

    $website = $data[4];

    $filename = "website/$key.html";

    if(file_exists($filename)) {
        
        $html = file_get_contents($filename);

        $html = removeAllImgTags($html);


       $html2 = new \Html2Text\Html2Text($html);

        $html = $html2->getText();  // Hello, "WORLD"

        // $html = remove_urls($html);
        // $html = str_replace("[/]", "", $html);
        // $html = str_replace("[]", "", $html);
        //$pattern = '/\[[^\]]*\]/';
        //$html = preg_replace($pattern, '', $html);

             $html = remove_multiple_linebreaks($html);

        echo $filename;echo PHP_EOL; 

                //echo $html;die();

        $filename2 = "website_text/$key.html";

        file_put_contents($filename2, $html);

        $filename2 = "website_text_short/$key.html";


        $pattern = '/\[[^\]]*\]/';
        $html = preg_replace($pattern, '', $html);
        file_put_contents($filename2, $html);


   }

   


   $filename = "website_contact/$key.html";

    if(file_exists($filename)) {
        
        $html = file_get_contents($filename);

        $html = removeAllImgTags($html);


       $html2 = new \Html2Text\Html2Text($html);

        $html = $html2->getText();  // Hello, "WORLD"

        // $html = remove_urls($html);
        // $html = str_replace("[/]", "", $html);
        // $html = str_replace("[]", "", $html);
        //$pattern = '/\[[^\]]*\]/';
        //$html = preg_replace($pattern, '', $html);

             $html = remove_multiple_linebreaks($html);

        echo $filename;echo PHP_EOL; 

                //echo $html;die();

        $filename2 = "website_contact_text/$key.html";

        file_put_contents($filename2, $html);

        $filename2 = "website_contact_text_short/$key.html";


        $pattern = '/\[[^\]]*\]/';
        $html = preg_replace($pattern, '', $html);
        file_put_contents($filename2, $html);


   }

}





// $filename = "county-".time().".csv";

// $f = fopen($filename, "w");
// foreach ($result as $line) {
//   fputcsv($f, $line);
// }

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



function remove_multiple_linebreaks($text) {
    // Define the regular expression pattern
    $pattern = '/(\r\n|\r|\n)+/';

    // Replace multiple consecutive line breaks with just one line break
    $text = preg_replace($pattern, "\n", $text);

    // Return the modified text
    return $text;
}