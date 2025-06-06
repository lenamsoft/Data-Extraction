<?php
include "common.php";

echo 'abc';
$data = read_csv('britishlegion.csv');



$result = $result_gg = [];

foreach($data as $key => $row) {
  if($key == 0) continue;

  if(!empty($row[8]) && empty($row[10])) {
    
    $address = $row[8]; // notes

    echo $address ."\n"; 
    $location_data = getLatLongFromAddress_google($address);

    $location_data = (array)$location_data;


    $latitude = $longitude = '';
    $city = $state = $country = $postal_code = $county = $postal_code = '';
    
    
    if (!empty($location_data['results'][0])) {

        //print_R($location_data['results'][0]);

        $location_data = json_decode(json_encode($location_data), true);
        
        $lat = $location_data['results'][0]['geometry']['location']['lat'];
        $long = $location_data['results'][0]['geometry']['location']['lng'];

        foreach($location_data['results'][0]['address_components'] as $val) {

            if($val['types'][0] == 'postal_code') $zip_code = $val['long_name'];
            if($val['types'][0] == 'postal_town') $city  = $val['long_name'];
            if($val['types'][0] == 'administrative_area_level_2') $county  = $val['long_name'];
        }

  
        $data[$key][10] = $lat;
        $data[$key][11] = $long;
        $data[$key][12] = $zip_code;
        $data[$key][15] = $county;
        $data[$key][16] = $city;

        

   
    }



  }

  
}





$filename = "latlong-".time().".csv";

$f = fopen($filename, "w");
foreach ($data as $line) {
  fputcsv($f, $line);
}



die();
// Example usage
$address = "1600 Amphitheatre Parkway, Mountain View, CA"; // Example address
$location = getLatLongFromAddress($address);

if ($location) {
    echo "Latitude: " . $location['latitude'] . "<br>";
    echo "Longitude: " . $location['longitude'];
} else {
    echo "Unable to retrieve latitude and longitude for the provided address.";
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
