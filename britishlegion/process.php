<?php
include "common.php";



$result = [];
$result[] = [
  'Brand Organization Email Extension',
  'Tenant Organization Email Extension',
  'Brand Location Number',
  'Tenant Location Number',
  "Name",
  "Phone",
  "Email",
  "Website",
  "Address Line one",
  "Address Line Two",
  "Latitude",
  "Longitude",
  "Zip Code",
  "Country",
  "State",
  "County",
  "Municipality - Place",
  "Notes",
];

$i = 0;
$duplicate = [];



$url_list = read_csv('url_list.csv');

 // print_R($url_list);die();


foreach($url_list as $key => $data) {
   $url = $data[0];

    $brand_org_email_ext = $tenant_org_email_ext = $brand_local_num = $tenant_local_num = $title = $phone_number =$website = $address = $address_2 = $lat = $lng = $zip_code = $state =  $county = $city = $notes = $email  = '';

    $brand_org_email_ext = 'britishlegion.org.uk';
    $country = 'United Kingdom';

    $title = $data[1];

    $file = "html/$key.html";
    if(file_exists($file)) {
        $html = file_get_contents($file);
        $html = str_get_html($html);
        echo $html; die();
        if($html) {
            foreach($html->find('p') as $productDetail){

                if(preg_match('/meets /', $productDetail->innertext()) || preg_match('/meet /', $productDetail->innertext())) {
                    $notes = $productDetail->innertext();

                    $notes = html_entity_decode(strip_tags($notes));
                    $notes = str_replace('&amp;',  "&", $notes);
                    $notes = str_ireplace(' ',  " ", $notes);
                    


                    break;
                }

            }

        }

        if(!preg_match('/britishlegion.org.uk/', $url)) {
            $website = $url;
        }

       


    }

    

    $dataRow = [$brand_org_email_ext, $tenant_org_email_ext, $brand_local_num, $tenant_local_num, $title, $phone_number, $email, $website, $address, $address_2, $lat, $lng, $zip_code, $country, $state, $county, $city, $notes];

    foreach($dataRow as &$item) $item = trim($item);
    
    //print_R($dataRow);die();


    
    $result[] = $dataRow;
  

}





$filename = "britishlegion-".time().".csv";

$f = fopen($filename, "w");
foreach ($result as $line) {
  fputcsv($f, $line);
}

die('Done');


function find_latlong2($title, $map_markers) {
    foreach($map_markers as $row) {
        // var_dump($title);
        // var_dump($row[6]);

        // print_R($row);die();

        if(strtolower($title) == strtolower($row[6])) {

        //     echo $title;

        // print_R($row);die();


            return [$row[1], $row[2]];
        }
    }
}