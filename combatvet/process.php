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

$html = file_get_contents("xml_dump.xml");

$html = str_get_html($html);



// Load the XML file
$xml = simplexml_load_file('xml_dump.xml');

// Check if the XML file is valid
if ($xml !== false) {
    // Iterate through child elements
    foreach ($xml->children() as $child) {
        // Output element name
        echo "Element: {$child->getName()}\n";
        

        $source=$name=$main_phone=$main_email=$website=$website_valid=$email_extension=$linkedin=$instagram=$facebook= $twitter =$pinterest=$tiktok=$youtube=$mission=$veteran_owned_business=$is_certified_veteran_owned_business=$is_veteran_service_organization=$is_va_or_gov_organization=$va_or_gov_type=$va_or_gov_subtype=$service_area=$address_line_one=$address_line_two=$latitude=$longitude=$zip_code=$country=$state=$county=$municipality=$notes=$owner=$owner_phone=$owner_email='';

            $source = "https://www.combatvet.us/maps/chapters.php";
            $country = 'United States';

            // Output all attributes of the element
        foreach ($child->attributes() as $key => $value) {
            echo "Attribute: $key = $value\n";

            if($key == "name") {
              $name = $value;
            }
            if($key == "address") {
              $address_line_one = $value;
              $add_array = explode(",", $address_line_one);
              $municipality = $add_array[0];
              $state = $add_array[1];



            }
            if($key == "lat") {
              $latitude = $value;
            }
            if($key == "lng") {
              $longitude = $value;
            }
            if($key == "cco_email") {
              $main_email = $value;

              $email_extension = extractEmailExtension($main_email);
              
            }
            if($key == "chapter_url") {
              $website = $value;
            }

        }

            

           $dataRow = [$source,$name,$main_phone,$main_email,$website,$website_valid,$email_extension,$linkedin,$instagram,$facebook,$twitter,$pinterest,$tiktok,$youtube,$mission,$veteran_owned_business,$is_certified_veteran_owned_business,$is_veteran_service_organization,$is_va_or_gov_organization,$va_or_gov_type,$va_or_gov_subtype,$service_area,$address_line_one,$address_line_two,$latitude,$longitude,$zip_code,$country,$state,$county,$municipality,$notes,$owner,$owner_phone,$owner_email,];

                foreach($dataRow as &$item) $item = trim((string)$item);
            $result[] = $dataRow;


        
    }
} else {
    echo "Failed to load XML file.\n";
}


  

$filename = "combatvet-".time().".csv";

$f = fopen($filename, "w");
foreach ($result as $line) {
  fputcsv($f, $line);
}

die('Done');


