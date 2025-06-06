<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once 'simple_html_dom.php';

function doService($url, $action = "GET", $parameter = '') {

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

  $headers = ['User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:80.0) Gecko/20100101 Firefox/80.0', 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8', 'Referer: https://www.navyfederal.org/'];

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
  
 
  return $page;
}

function find_latlong($zip_check, $csv_all_zip) {

  foreach($csv_all_zip as $row) {

    $zip = $row[0];
    
    if(strlen($zip) == 3) $zip = "00". $zip;
    if(strlen($zip) == 4) $zip = "0". $zip;

    if((int)$zip_check == (int)$zip) {
      return [$row[1], $row[2]];
    }
  } 
}


function read_csv(string $file, int $length = 1000, string $delimiter = ','): array {
    $handle = fopen($file, 'r');
    $hashes = [];
    $values = [];
    $header = null;
    $headerUnique = null;

    if (!$handle) {
        return $values;
    }

    $header = fgetcsv($handle, $length, $delimiter);
    $values[] = $header;

    if (!$header) {
        return $values;
    }

    $headerUnique = unique_columns($header);

    while (false !== ($data = fgetcsv($handle, $length, $delimiter))) {
        $hash = md5(serialize($data));

        if (!isset($hashes[$hash])) {
            $hashes[$hash] = true;
            $values[] = $data;
        }
    }

    fclose($handle);

    return $values;
}

function unique_columns(array $columns):array {
    $values = [];

    foreach ($columns as $value) {
        $count = 0;
        $value = $original = trim($value);

        while (in_array($value, $values)) {
            $value = $original . '-' . ++$count;
        }

        $values[] = $value;    
    }

    return $values;
}


function extractZipFromAddress($address) {
    // Define a regular expression pattern to match zip codes
    $pattern = '/\b\d{5}(?:-\d{4})?\b/';

    // Perform the regular expression match
    if (preg_match_all($pattern, $address, $matches)) {
        // Get the last matched zip code
        $lastZip = end($matches[0]);
        return $lastZip !== false ? $lastZip : "";
    } else {
        // Return null if no zip code found
        return "";
    }
}

function find_state_from_zip($zip, $uszip_data) {
   
    foreach($uszip_data as $key=>$row) {

        if($zip == $row[0]) {

            return $row[5];
        }

   }


   return '';


}

function find_city_from_zip($zip, $uszip_data) {
   
    foreach($uszip_data as $key=>$row) {

        if($zip == $row[0]) {

            return $row[3];
        }

   }


   return '';


}

