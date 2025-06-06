<?php
include "common.php";


$data = read_csv('britishlegion.csv');
print_R($data[0]);

foreach($data as $key => $row) {
  if($key == 0) continue;


  if(1) {
    $filename = "findPlaceFromText/$key.json";

    $json = json_decode(file_get_contents($filename), 1);

    foreach($json['candidates'] as $item) {
        $place_id = $item['place_id'];
        $url = "https://www.google.com/maps/place/?q=place_id:$place_id";
        echo "<a href='$url'>".$item['name']. "</a><br>\n";

        $place_api_data =  getPlaceDetails($place_id);

        //print_R($place_api_data);



        $zip_code = '';
        $city = '';
        $county = '';

        $format_address = $place_api_data['result']['formatted_address'];
        $lat = $place_api_data['result']['geometry']['location']['lat'];
        $long = $place_api_data['result']['geometry']['location']['lng'];

        foreach($place_api_data['result']['address_components'] as $val) {

            if($val['types'][0] == 'postal_code') $zip_code = $val['long_name'];
            if($val['types'][0] == 'postal_town') $city  = $val['long_name'];
            if($val['types'][0] == 'administrative_area_level_2') $county  = $val['long_name'];
        }

        $data[$key][8] = $format_address;
        $data[$key][10] = $lat;
        $data[$key][11] = $long;
        $data[$key][12] = $zip_code;
        $data[$key][15] = $county;
        $data[$key][16] = $city;
        






        //die();
    }
    

  }
  

  //die();

}

$result = $data;

$filename = "britishlegion-".time().".csv";

$f = fopen($filename, "w");
foreach ($result as $line) {
  fputcsv($f, $line);
}



// Example usage:
//$text = "The Royal British Legion at Aberaeron";
// $text = 'britishlegion';
// $location = findPlaceFromText($text);
// echo $location;



die();



$place_id = 'ChIJRTfinlgDdkgRgoMH83XCb18';

$data =  getPlaceDetails($place_id);

print_R($data);



function getPlaceDetails($placeId) {
    // Google Places API endpoint

  $apiKey  = 'AIzaSyDO4_6lFZJBeAvKj7c8zgAdKHWo91X1vQU';

    $apiEndpoint = "https://maps.googleapis.com/maps/api/place/details/json?place_id=$placeId&fields=formatted_address,name,geometry,url,address_components,type,formatted_phone_number,website&key=$apiKey";

    // Initialize cURL session
    $curl = curl_init();

    // Set cURL options
    curl_setopt_array($curl, array(
        CURLOPT_URL => $apiEndpoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
    ));

    // Execute cURL request
    $response = curl_exec($curl);

    // Check for cURL errors
    if (curl_errno($curl)) {
        return "cURL Error: " . curl_error($curl);
    }

    // Close cURL session
    curl_close($curl);

    // Decode JSON response
    $responseData = json_decode($response, true);


    return $responseData;

    // Check if the response contains results
    if (!empty($responseData['result'])) {
        // Extract the formatted address
        $formattedAddress = $responseData['result']['formatted_address'];
        return $responseData;
    } else {
        return "Location not found.";
    }
}






function findPlaceFromText($text) {

  $apiKey  = 'AIzaSyDO4_6lFZJBeAvKj7c8zgAdKHWo91X1vQU';



    // Encode the text for URL
    $encodedText = urlencode($text);

    // Google Places API endpoint
    $apiEndpoint = "https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input=$encodedText&inputtype=textquery&fields=formatted_address,name,types,geometry,place_id,plus_code,type&key=$apiKey";

    //$apiEndpoint = "https://places.googleapis.com/v1/places/$encodedText&fields=formatted_address,name,types,geometry,place_id,plus_code,type&key=$apiKey";

    // Initialize cURL session
    $curl = curl_init();

    // Set cURL options
    curl_setopt_array($curl, array(
        CURLOPT_URL => $apiEndpoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
    ));

    // Execute cURL request
    $response = curl_exec($curl);

    // Check for cURL errors
    if (curl_errno($curl)) {
        return "cURL Error: " . curl_error($curl);
    }

    // Close cURL session
    curl_close($curl);

    // Decode JSON response
    $responseData = json_decode($response, true);

    return $responseData;

    // Check if the response contains results
    if (!empty($responseData['candidates'])) {
        // Extract the formatted address
        $formattedAddress = $responseData['candidates'][0]['formatted_address'];
        return $formattedAddress;
    } else {
        return "Location not found.";
    }
}





die();
$result =  [];

foreach($data as $key => $row) {
  if($key == 0) continue;

  if(!empty($row[17])) {
    
    $address = $row[17]; // notes

    echo $address ."\n"; 
    
    if($zip = findUKPostcodes($address)) {

      if(preg_match('/very/', $zip)) {
            continue;
        }

      $data[$key][12] = $zip;
    }


   
    }

    //$result[$key] = [$latitude, $longitude, $city];

}




print_R($data);
$result = $data;




$filename = "britishlegion-latlong-".time().".csv";

$f = fopen($filename, "w");
foreach ($result as $line) {
  fputcsv($f, $line);
}

die();


die();
function findUKPostcodes($text) {
    // Regular expression pattern to match UK postal codes
    $pattern = '/[A-Z]{1,2}[0-9R][0-9A-Z]? [0-9][ABD-HJLNP-UW-Z]{2}/i';

    // Match postal codes in the text
    preg_match_all($pattern, $text, $matches);

    // Extracted postal codes
    $postcodes = reset($matches[0]);

    return $postcodes;
}




function process_data_latlong($data) {
  // Check if the response is not empty and contains latitude and longitude
    if ($data->status == "OK") {
        // Extract latitude and longitude
        $latitude = $data->results[0]->geometry->location->lat;
        $longitude = $data->results[0]->geometry->location->lng;

        // Return latitude and longitude as an associative array
        return array(
            'latitude' => $latitude,
            'longitude' => $longitude
        );
    } else {
        // Return null if unable to retrieve latitude and longitude
        return null;
    }


}

function process_data_city($data) {
  if ($data->status == "OK") {
        // Extract latitude and longitude
        $address_components = $data->results[0]->address_components;

        foreach($address_components as $row) {
          if($row->types[0] == 'locality')
            return $row->long_name;
        }


        
    } else {
        // Return null if unable to retrieve latitude and longitude
        return null;
    }

    return null;


}


// Function to get latitude and longitude from address using Google Maps Geocoding API
function getLatLongFromAddress_google($address) {
    // Google Maps API key (optional, but recommended)
    $api_key = 'AIzaSyDO4_6lFZJBeAvKj7c8zgAdKHWo91X1vQU';

    // Google Maps Geocoding API endpoint
    $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($address);

    // If an API key is provided, append it to the URL
    if ($api_key) {
        $url .= "&key=" . $api_key;
    }

    // Fetch the data from the Google Maps Geocoding API
    $response = doService_json($url);

    // Decode the JSON response
    $data = json_decode($response);
    return $data;

    // Check if the response was successful
    
}


function doService_json($url, $action = "GET", $parameter = '') {

  $cookie_file_path = "cookie.txt";
  $wwwhandle = curl_init();
  curl_setopt($wwwhandle, CURLOPT_TIMEOUT, 20);

  curl_setopt($wwwhandle, CURLOPT_URL, $url);
  curl_setopt($wwwhandle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($wwwhandle, CURLOPT_USERAGENT, "User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:80.0) Gecko/20100101 Firefox/80.0");
  curl_setopt($wwwhandle, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($wwwhandle, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
  curl_setopt($wwwhandle, CURLOPT_VERBOSE, FALSE);

  curl_setopt($wwwhandle, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($wwwhandle, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($wwwhandle, CURLOPT_ENCODING, '');
  curl_setopt($wwwhandle, CURLOPT_DNS_CACHE_TIMEOUT, "-1");

  // curl_setopt($wwwhandle, CURLOPT_COOKIESESSION, true);
  // curl_setopt($wwwhandle, CURLOPT_COOKIEJAR, $cookie_file_path);
  // curl_setopt($wwwhandle, CURLOPT_COOKIEFILE, $cookie_file_path);

  $headers = ['User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:80.0) Gecko/20100101 Firefox/80.0', 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8'];

  curl_setopt($wwwhandle, CURLOPT_HTTPHEADER, $headers);

  if ($action == "POST") {

    curl_setopt($wwwhandle, CURLOPT_POST, 1);
    curl_setopt($wwwhandle, CURLOPT_POSTFIELDS, $parameter);
  }

  if ($action == "GET" && $parameter != "") {
    $url .= "?" . $parameter;
  }
  $page = curl_exec($wwwhandle);

  //$httpcode = curl_getinfo($wwwhandle, CURLINFO_HTTP_CODE);
  curl_close($wwwhandle);
  
 //echo $page;die();
  return $page;
}

?>
