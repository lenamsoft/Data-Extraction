<?php
include "common.php";

$state_list = getUSStateAbbreviations();


$state_list['PR'] = 'Puerto Rico';
$state_list['GU'] = 'Guam';
$state_list['RI'] = 'Rhode Island';
$state_list['MP'] = 'NORTHERN MARIANA IS';


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


$url_list = read_csv('links.csv');
//print_R($url_list);

//$url_list = array_slice($url_list, 0  , 1);

foreach($url_list as $key => $data) {

    $url = $data[0];
    $state_name = $data[1];
    $id = $key+1;

    $file = "website/$id.html";

    echo $file;

    if(!file_exists($file)) continue;


  $html = file_get_contents($file);
  //$html = str_get_html($html);
  
$html = get_body_from_html($html);

$html = removeScriptTags($html);
$html = removeAllImgTags($html);
$html = removeAllStyleTags($html);


$html = removeInlineStyles($html);
file_put_contents($file, $html);
  

}


function removeInlineStyles($html) {
    $dom = new DOMDocument();
    @$dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD); // Load HTML without adding doctype, html, and body tags

    $xpath = new DOMXPath($dom);
    $nodes = $xpath->query('//*[@style]'); // Find all elements with a 'style' attribute

    foreach ($nodes as $node) {
        $node->removeAttribute('style'); // Remove the 'style' attribute
    }

    $nodes = $xpath->query('//*[@class]'); // Find all elements with a 'style' attribute

    foreach ($nodes as $node) {
        $node->removeAttribute('class'); // Remove the 'style' attribute
    }

    $nodes = $xpath->query('//*[@title]'); // Find all elements with a 'style' attribute

    foreach ($nodes as $node) {
        $node->removeAttribute('title'); // Remove the 'style' attribute
    }



    return $dom->saveHTML();
}


function removeAllStyleTags($html) {
    // Regular expression to remove all <style> tags and their content
    $html = preg_replace('#<style[^>]*>.*?</style>#is', '', $html);

    $html = preg_replace('#<path[^>]*>.*?</path>#is', '', $html);
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


