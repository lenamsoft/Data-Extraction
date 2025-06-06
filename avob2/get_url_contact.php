<?php
include "common.php";

$csv_data = read_csv('avob-1715589671.csv');

print_R($csv_data[0]);
// die();
$new_csv_data = $csv_data; 
$result = [];


foreach($new_csv_data as $key => $row) {
  if($key == 0) continue;

  $url = $row[4];

  $valid = $row[5];



  //if($valid != "Y") continue;

  $add = $row[22];

  //if(!empty($add)) continue;

  

  
  $filename = "website_text/$key.html";

  if(!file_exists($filename)) continue;

  $html = file_get_contents($filename);

  $links = getTextInBrackets($html);

  foreach($links as $link) {
    

    if(preg_match("/contact/", $link)) {
      
      //echo $link. PHP_EOL;

      //echo checkDomainOrPath($link);
      if(!isValidUrl($link)) {
        $link = generateUrl($url, $link);
      }

      echo $key ." " . $link. PHP_EOL;

      $page = doService($link);

      $filename = "website_contact/$key.html";
      file_put_contents($filename, $page);


      break;
    }

  }
  
  //die();

  
}

function isValidUrl($url) {
    // Use filter_var with FILTER_VALIDATE_URL to validate the URL
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
}

function checkDomainOrPath($input) {
    // Regular expression for validating domain names (including multi-level TLDs like .co.uk)
    $domainRegex = '/^(?!:\/\/)([a-zA-Z0-9-_]{1,63}\.)+[a-zA-Z]{2,}$/';

    // Regular expression for validating paths
    $pathRegex = '/^\/([a-zA-Z0-9-_\/]+)$/';

    if (preg_match($domainRegex, $input)) {
        return "Domain";
    } elseif (preg_match($pathRegex, $input)) {
        return "Path";
    } else {
        return "Invalid";
    }
}



/**
 * Function to generate a URL from a domain and a path.
 *
 * @param string $domain The base domain (e.g., "example.com").
 * @param string $path The relative path on the server (e.g., "path/to/resource").
 * @param array $queryParams Optional query parameters to include in the URL.
 * @return string The complete URL as a string.
 */
function generateUrl($domain, $path, $queryParams = []) {
    // Ensure the domain does not have a trailing slash
    $domain = rtrim($domain, '/');

    // Ensure the path starts with a leading slash
    $path = '/' . ltrim($path, '/');

    // Build the query string from the provided parameters
    $queryString = http_build_query($queryParams);

    // Concatenate the domain, path, and query string
    $url = $domain . $path;

    if ($queryString) {
        $url .= '?' . $queryString;
    }

    return $url;
}

// Example usage:
$domain = "https://example.com";
$path = "path/to/resource";
$queryParams = ['key1' => 'value1', 'key2' => 'value2'];

$url = generateUrl($domain, $path, $queryParams);

echo $url; // Outputs: https://example.com/path/to/resource?key1=value1&key2=value2


function getTextInBrackets($input) {
    // Regular expression to match all content inside square brackets
    preg_match_all('/\[([^\]]+)\]/', $input, $matches);
    
    // The matches are stored in $matches[1] because $matches[0] will contain the full pattern including the brackets.
    return $matches[1];
}