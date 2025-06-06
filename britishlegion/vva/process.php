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


$data_html = file_get_contents('./data_html.json');
$data_list = json_decode($data_html);
// print_r($data_list);
// die();

$titles = [];
foreach ($data_list as $key => $data_item) {

    $brand_org_email_ext = $tenant_org_email_ext = $brand_local_num = $tenant_local_num = $title = $phone_number = $website = $address = $address_2 = $lat = $lng = $zip_code = $state = $county = $city = $notes = $email = '';

    $dom = str_get_html($data_item);

    //print_R($data_item);
    
    


    $brand_org_email_ext = 'ssafa.org.uk';
    $country = 'United Kingdom';

    $title = "SSAFA " . $dom->find('h3', 0)->text();

    if (in_array($title, $titles)) {
        continue;
    } else {
        $titles[] = $title;
    }
    $details = $dom->find('div', 0);

    if (!empty($details->find('p', 1)) && $details->find('p', 1)->text() != '') {
        $address = $details->find('p', 1)->text();
        $address = str_replace('<br>', '', $address);
        $address = str_replace('&nbsp;', ' ', $address);
        $address = str_replace(array("\r", "\n"), ' ', $address);

        if (strstr($address, 'no fixed') || strstr($address, 'No fixed') || strstr($address, 'No branch') || strstr($address, 'TBC')) {
            $address = '';
        }
        echo $address . '<br>';
    }
    if (!empty($details->find('p', -1))) {
        $phone_number = $details->find('p', -1)->text();
    }


    $tag_paragraphs = $dom->find('p');
    if (!empty($tag_paragraphs)) {
        foreach ($tag_paragraphs as $key => $paragraph) {
            if (!empty($paragraph->find('strong', 0)) && !empty($paragraph->find('a', 0)) && $paragraph->find('strong', 0)->text() == 'Website link:') {
                $website = $paragraph->find('a', 0)->href;

                if(!preg_match('/https/', $website)) {
                    $website = "https://www.ssafa.org.uk".$website;
                    }

                
            }
        }
    }


    foreach($dom->find('div') as $productDetail){

                if(preg_match('/Opening times/', $productDetail->innertext()) || preg_match('/meet /', $productDetail->innertext())) {
                    $notes = $productDetail->innertext();

                    $notes = html_entity_decode(strip_tags($notes));
                    $notes = str_replace('&amp;',  "&", $notes);
                    $notes = str_ireplace(' ',  " ", $notes);
                    
                    //echo $notes;die();

                    break;
                }

            }



    $dataRow = [$brand_org_email_ext, $tenant_org_email_ext, $brand_local_num, $tenant_local_num, $title, $phone_number, $email, $website, $address, $address_2, $lat, $lng, $zip_code, $country, $state, $county, $city, $notes];

    foreach ($dataRow as &$item)
        $item = trim($item);

    //print_R($dataRow);die();



    $result[] = $dataRow;


}





$filename = "ssafa-" . time() . ".csv";

$f = fopen($filename, "w");
foreach ($result as $line) {
    fputcsv($f, $line);
}

die('Done');


function find_latlong2($title, $map_markers)
{
    foreach ($map_markers as $row) {
        // var_dump($title);
        // var_dump($row[6]);

        // print_R($row);die();

        if (strtolower($title) == strtolower($row[6])) {

            //     echo $title;

            // print_R($row);die();


            return [$row[1], $row[2]];
        }
    }
}