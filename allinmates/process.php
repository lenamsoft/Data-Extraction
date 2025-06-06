<?php
include "common.php";
include "vendor/autoload.php";


$text = 'Address  
Facility Type    
State  
Email  
Phone 
Phone Carrier
County
Fax  
Security Level   
Website
No Of Beds
Mailing Address
';

$text = explode("\n", $text);

$dataRow = [];
foreach ($text as $t) {
    $dataRow[] = trim($t);
}


$result = [];
$result[] = $dataRow;



$i = 0;
$duplicate = [];


$directory = 'html';
$files = scandir($directory);

// Filter out '.' and '..'
$files = array_diff($files, array('.', '..'));

$i = 0;
foreach ($files as $file) {
 

  //if($i>2 ) continue;

  echo $file . "\n";

  //die();
  $html = file_get_contents("html/".$file );

  $html = str_get_html($html);

  foreach($html->find('.table-responsive  td') as $productDetail){

    $row = $productDetail->innertext();

    //$Address = $row;
    echo $row."\n";
    if(preg_match("|<b>Address Of The Facility</b>|", $row)) {
        $Address = str_replace("<b>Address Of The Facility</b>","", $row);
        $Address = strip_tags($Address);


    }

    if(preg_match("|<b>Facility Type</b>|", $row)) {
        $Facility = str_replace("<b>Facility Type</b>","", $row);
        $Facility = strip_tags($Facility);
    }
    if(preg_match("|<b>State</b>|", $row)) {
        $State = str_replace("<b>State</b>","", $row);
        $State = strip_tags($State);
    }
    if(preg_match("|<b>Email</b>|", $row)) {
        $Email = str_replace("<b>Email</b>","", $row);
        $Email = strip_tags($Email);
    }
    if(preg_match("|<b>Phone</b>|", $row)) {
        $Phone = str_replace("<b>Phone</b>","", $row);
        $Phone = strip_tags($Phone);
    }
    if(preg_match("|<b>Phone Carrier</b>|", $row)) {
        $row = str_replace("<b>Phone Carrier</b>","", $row);
        $Phone_carier = strip_tags($row);
    }
    if(preg_match("|<b>County</b>|", $row)) {
        $row = str_replace("<b>County</b>","", $row);
        $County = strip_tags($row);
    }

    if(preg_match("|<b>Fax</b>|", $row)) {
        $row = str_replace("<b>Fax</b>","", $row);
        $Fax = strip_tags($row);
    }

    if(preg_match("|<b>Security Level</b>|", $row)) {
        $row = str_replace("<b>Security Level</b>","", $row);
        $Security = strip_tags($row);
    }
    
    if(preg_match("|<b>Website</b>|", $row)) {
        $row = str_replace("<b>Website</b>","", $row);
        $Website = strip_tags($row);
    }

    if(preg_match("|<b>No Of Beds</b>|", $row)) {
        $row = str_replace("<b>No Of Beds</b>","", $row);
        $No = strip_tags($row);
    }

    if(preg_match("|<b>Mailing Address</b>|", $row)) {
        $row = str_replace("<b>Mailing Address</b>","", $row);
        $Mailing = strip_tags($row);
    }


  }

  if(empty($Address)) continue;

  $dataRow = [$Address  ,
        $Facility,    
        $State , 
        $Email , 
        $Phone ,
        $Phone_carier ,
        $County,
        $Fax  ,
        $Security ,   
        $Website,
        $No,
        $Mailing];

    foreach ($dataRow as &$item)
        if(!empty($item)) $item = trim($item);


    $result[] = $dataRow;

    $i++;
   

}





$filename = "results-".time().".csv";

$f = fopen($filename, "w");
foreach ($result as $line) {
  fputcsv($f, $line);
}

die('Done');
