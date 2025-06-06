<?php
include "common.php";



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
X
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



//print_R($url_list);

//$url_list = array_slice($url_list, 0  , 1);

$files = getAllHtmlFiles("html");

//print_R($files);die();

for($i = 0 ; $i<=464; $i++) {

  $key = $i;

  //if($i>10) continue;

    $file = "html/$key.html";


  $html = file_get_contents($file);
  $html = str_get_html($html);

  $tr = $html->find('title');
  foreach ($tr as $row) {


            $source=$name=$main_phone=$main_email=$website=$website_valid=$email_extension=$linkedin=$instagram=$facebook= $twitter =$pinterest=$tiktok=$youtube=$mission=$veteran_owned_business=$is_certified_veteran_owned_business=$is_veteran_service_organization=$is_va_or_gov_organization=$va_or_gov_type=$va_or_gov_subtype=$service_area=$address_line_one=$address_line_two=$latitude=$longitude=$zip_code=$country=$state=$county=$municipality=$notes=$owner=$owner_phone=$owner_email='';

            $source = "https://www.afvbc.com/afvbc-uk-breakfast-clubs";
            $country = 'United Kingdom';

            
            $name = $row->plaintext;
            $name = str_replace("&amp;", "&", $name);
            $name_array = explode("|", $name);
            $name = $name_array[0];
            $name = str_replace("(AFVBC)", "", $name);







            

         

            $address_line_two  = $html->find('#club-address-details', 0)->plaintext;
            $address_line_two = str_replace("Location", "", $address_line_two);
            $zip_code = extractFirstUKPostcode($address_line_two);

            
            if($zip_code) {
              if($city = getCityFromUKPostcode($zip_code)) {
                $municipality = $city;
                echo $municipality;
                //die();
            }
          }

            


            


           

           $dataRow = [$source,$name,$main_phone,$main_email,$website,$website_valid,$email_extension,$linkedin,$instagram,$facebook,$twitter,$pinterest,$tiktok,$youtube,$mission,$veteran_owned_business,$is_certified_veteran_owned_business,$is_veteran_service_organization,$is_va_or_gov_organization,$va_or_gov_type,$va_or_gov_subtype,$service_area,$address_line_one,$address_line_two,$latitude,$longitude,$zip_code,$country,$state,$county,$municipality,$notes,$owner,$owner_phone,$owner_email,];

                foreach($dataRow as &$item) {
                    $item = trim((string)$item);
                    $item = str_replace("&amp;", "&", $item);
                }
            $result[] = $dataRow;

    }

    
    



    
    
  }


  






$filename = "afvbc-".time().".csv";

$f = fopen($filename, "w");
foreach ($result as $line) {
  fputcsv($f, $line);
}

die('Done');


function extractFirstUKPostcode($text) {
    // Define the regular expression pattern for UK postcodes
    $pattern = '/([A-Z]{1,2}\d{1,2}\s*\d[A-Z]{2})/i';

    // Perform the regex search
    if (preg_match($pattern, $text, $matches)) {
        // Return the first matched postcode
        return $matches[0];
    } else {
        // Return null if no postcode is found
        return null;
    }
}


function getCityFromUKPostcode($postcode) {
    // URL for the Postcodes.io API
    $apiUrl = "https://api.postcodes.io/postcodes/$postcode";

    // Make a GET request to the API
    $response = file_get_contents($apiUrl);

    // Decode the JSON response
    $data = json_decode($response, true);

    // Check if the request was successful and city information is available
    if ($data && isset($data['result']['admin_district'])) {
        // Return the city name
        return $data['result']['admin_district'];
    } else {
        // Return null if city information is not available
        return null;
    }
}


