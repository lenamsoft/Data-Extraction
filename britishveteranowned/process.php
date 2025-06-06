<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';
use proj4php\Proj4php;
use proj4php\Proj;
use proj4php\Point;
$proj4 = new Proj4php();
$proj3857 = new Proj('EPSG:3857', $proj4);
$proj4326 = new Proj('EPSG:4326', $proj4);



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
$data_out = [];
$data_out[] = array_map('trim', explode(PHP_EOL, $text));


$data = json_decode(file_get_contents('data.json'));
$data = $data->layers[0]->featureSet->features;

foreach ($data as $key => $item) {
  $attributes = $item->attributes;
  $geometry = $item->geometry;

  $point3857 = new Point($geometry->x, $geometry->y);
  $point4326 = $proj4->transform($proj3857, $proj4326, $point3857);
  $latlng = $point4326->x.','.$point4326->y;
  $lg_data = getLG($latlng);


  if (!empty($attributes)) {
    $url = 'https://servulink.app/';

    $source = $name = $main_phone = $main_email = $website = $website_valid = $email_extension = $linkedin = $instagram = $facebook = $twiter = $pinterest = $tiktok = $youtube = $mission = $veteran_owned_business = $is_certified_veteran_owned_business = $is_veteran_service_organization = $is_va_or_gov_organization = $va_or_gov_type = $va_or_gov_subtype = $service_area = $address_line_one = $address_line_two = $latitude = $longitude = $zip_code = $country = $state = $county = $municipality = $notes = $owner = $owner_phone = $owner_email = '';

    $source = $url;

    $name = $attributes->OrgName;
    $main_phone = !empty($attributes->Phone) ? $attributes->Phone : '';
    $main_email = !empty($attributes->Email) ? $attributes->Email : '';
    $address_line_one = !empty($attributes->Address) ? $attributes->Address : '';
    $website = !empty($attributes->Website) ? $attributes->Website : '';
    $linkedin = !empty($attributes->LinkedIn) ? $attributes->LinkedIn : '';
    $facebook = !empty($attributes->Facebook) ? $attributes->Facebook : '';
    $instagram = !empty($attributes->Instagram) ? $attributes->Instagram : '';
    $twiter = !empty($attributes->Twitter) ? $attributes->Twitter : '';
    $youtube = !empty($attributes->YouTube) ? $attributes->YouTube : '';
    $notes = !empty($attributes->OpenHours) ? $attributes->OpenHours : '';

    if (!empty($lg_data['latitude'])) {
      $latitude = $lg_data['latitude'];
    }
    if (!empty($lg_data['longitude'])) {
      $longitude = $lg_data['longitude'];
    }
    if (!empty($lg_data['zip_code'])) {
      $zip_code = $lg_data['zip_code'];
    }
    if (!empty($lg_data['country'])) {
      $country = $lg_data['country'];
    }
    if (!empty($lg_data['region'])) {
      $state = $lg_data['region'];
    }

    if (!empty($lg_data['locality'])) {
      $county = $lg_data['locality'];
    }

    if (!empty($lg_data['place'])) {
      $municipality = $lg_data['place'];
    }

    $dataRow = [$source, $name, $main_phone, $main_email, $website, $website_valid, $email_extension, $linkedin, $instagram, $facebook, $twiter, $pinterest, $tiktok, $youtube, $mission, $veteran_owned_business, $is_certified_veteran_owned_business, $is_veteran_service_organization, $is_va_or_gov_organization, $va_or_gov_type, $va_or_gov_subtype, $service_area, $address_line_one, $address_line_two, $latitude, $longitude, $zip_code, $country, $state, $county, $municipality, $notes, $owner, $owner_phone, $owner_email];


    print_r($lg_data);

    $data_out[] = $dataRow;
  }
}


file_put_contents('servulink.csv', '');
$f = fopen("servulink.csv", "w");
foreach ($data_out as $line) {
  fputcsv($f, $line);
}
fclose($f);


// search json
die('done');


function getLG($address='')
{
  $curl = curl_init();
  $url = 'https://api.mapbox.com/geocoding/v5/mapbox.places/'.$address.'.json?country=au&types=postcode&access_token=pk.eyJ1IjoiYWRob2MiLCJhIjoiY2wyZjNwM3dxMDZ4YjNjbzVwbTZ5aWQ1dyJ9.D8TZ1a4WobqcdYLWntXV_w';
  curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
      'Host: api.mapbox.com',
      'Accept: */*',
      'Origin: https://www.va.gov',
      'Referer: https://www.va.gov/'
    ),
  ));
  $response = curl_exec($curl);
  $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
  curl_close($curl);
  if ($httpcode == 200) {
    $response = json_decode($response);
    $place_data = [];
    if (!empty($response->features[0])) {
      $properties = $response->features[0];
      $place = $properties->context;
      $place_data = [
        'address' => $address,
        'zip_code' => $properties->text,
        'latitude' => (string)$properties->center[1],
        'longitude' => (string)$properties->center[0],
      ];

      foreach ($place as $val) {
         if (strpos($val->id, 'region') !== false ) {
           $place_data['region'] = $val->text;
         }
         if (strpos($val->id, 'country') !== false ) {
           $place_data['country'] = $val->text;
         }  
         if (strpos($val->id, 'place') !== false ) {
           $place_data['place'] = $val->text;
         }    
         if (strpos($val->id, 'locality') !== false ) {
           $place_data['locality'] = $val->text;
         } 

      }
    }
    

    return $place_data;

  }

  return [
    "zip_code" => "",
    "latitude" => "",
    "longitude" => "",
    "place" => "",
    "region" => "",
    "country" => ""
  ];
}

