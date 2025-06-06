<?php
set_time_limit(0);
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

  curl_setopt($wwwhandle, CURLOPT_COOKIESESSION, true);
  curl_setopt($wwwhandle, CURLOPT_COOKIEJAR, $cookie_file_path);
  curl_setopt($wwwhandle, CURLOPT_COOKIEFILE, $cookie_file_path);

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


function read_csv($filename, $length = 1000) {
    // Open the file in read mode
    $handle = fopen($filename, 'r');
    if (!$handle) {
        throw new Exception('File cannot be opened for reading');
    }

    // Define an array to store the CSV rows
    $data = [];

    // Loop through each line of the file
    while (($row = fgetcsv($handle, $length, ",")) !== FALSE) {
        $data[] = $row;
    }

    // Close the file
    fclose($handle);

    // Return the array of CSV data
    return $data;
}




function read_csv1(string $file, int $length = 5000, string $delimiter = ','): array {
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


function getUSStateAbbreviations() {
    return [
        'AL' => 'Alabama',
        'AK' => 'Alaska',
        'AZ' => 'Arizona',
        'AR' => 'Arkansas',
        'CA' => 'California',
        'CO' => 'Colorado',
        'CT' => 'Connecticut',
        'DE' => 'Delaware',
        'DC' => 'District of Columbia',
        'FL' => 'Florida',
        'GA' => 'Georgia',
        'HI' => 'Hawaii',
        'ID' => 'Idaho',
        'IL' => 'Illinois',
        'IN' => 'Indiana',
        'IA' => 'Iowa',
        'KS' => 'Kansas',
        'KY' => 'Kentucky',
        'LA' => 'Louisiana',
        'ME' => 'Maine',
        'MD' => 'Maryland',
        'MA' => 'Massachusetts',
        'MI' => 'Michigan',
        'MN' => 'Minnesota',
        'MS' => 'Mississippi',
        'MO' => 'Missouri',
        'MT' => 'Montana',
        'NE' => 'Nebraska',
        'NV' => 'Nevada',
        'NH' => 'New Hampshire',
        'NJ' => 'New Jersey',
        'NM' => 'New Mexico',
        'NY' => 'New York',
        'NC' => 'North Carolina',
        'ND' => 'North Dakota',
        'OH' => 'Ohio',
        'OK' => 'Oklahoma',
        'OR' => 'Oregon',
        'PA' => 'Pennsylvania',
        'RI' => 'Rhode Island',
        'SC' => 'South Carolina',
        'SD' => 'South Dakota',
        'TN' => 'Tennessee',
        'TX' => 'Texas',
        'UT' => 'Utah',
        'VT' => 'Vermont',
        'VA' => 'Virginia',
        'WA' => 'Washington',
        'WV' => 'West Virginia',
        'WI' => 'Wisconsin',
        'WY' => 'Wyoming',

        'PR' => 'Puerto Rico',
        'GU' => 'Guam',
        'MP' => 'NORTHERN MARIANA IS',   
        'AS' => 'American Samoa', 

        'PI' => 'Philippines', 
        'PH' => 'Philippines',
        'VI' => 'Virgin Islands',
        ];
}


function checkWebsiteStatus($url) {
    // Initialize cURL session
    $curl = curl_init();

    // Set cURL options
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
    ));

    // Execute cURL request
    $response = curl_exec($curl);

    // Get HTTP response code
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    // Close cURL session
    curl_close($curl);

    // Check if response code indicates success (200-299)
    if ($httpCode >= 200 && $httpCode < 300) {
        return 1;
    } else {
        return 0;
    }
}


function extractEmailExtension($email) {
    // Using regular expression to match the email extension
    $pattern = '/@([a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+)/';
    preg_match($pattern, $email, $matches);

    // Extracting the extension from the matched result
    if(isset($matches[1])) {
        $extension = $matches[1];
        return $extension;
    } else {
        // If no extension found, return false or handle the error accordingly
        return '';
    }
}


function getAllHtmlFiles($folderPath) {
    // Define the search pattern to find HTML files
    $searchPattern = "$folderPath/*.html";

    // Get an array of file paths matching the pattern
    $htmlFiles = glob($searchPattern);

    return $htmlFiles;
}

function get_body_from_html($html) {
    // Create a new DOMDocument object
    $dom = new DOMDocument();

    // Load HTML content into the DOMDocument
    @$dom->loadHTML($html);

    // Get the body element
    $body = $dom->getElementsByTagName('body')->item(0);

    // Initialize an empty string to store the body content
    $body_content = '';

    // Loop through each child node of the body element
    foreach ($body->childNodes as $node) {
        // Append the HTML content of each child node to the body content string
        $body_content .= $dom->saveHTML($node);
    }

    // Return the body content
    return $body_content;
}