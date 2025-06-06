<?php
include "common.php";

$state_list = getUSStateAbbreviations();


$state_list['PR'] = 'Puerto Rico';
$state_list['GU'] = 'Guam';
$state_list['RI'] = 'Rhode Island';
$state_list['MP'] = 'NORTHERN MARIANA IS';

$text = 'Source  
Name    
Main Phone  
Main Email  
Website 
Website Valid   
Email extension 
Linkedin    
Instagram   
Facebook X (formerly Twitter)
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

//$files = glob('./html/*.html');

//print_R($url_list);

//$url_list = array_slice($url_list, 0  , 1);

for($i=1;$i<2500;$i++) {

    $file = "./html/$i.html";

  $id = str_replace("./html/", "", $file);
  $id = str_replace(".html", "", $id);

  //if($id>=2) die();

  $web_file = "website/$id.html";

  if(!file_exists($file)) continue;


  $html_source = file_get_contents($file);

  if(!$html_source) continue;
  
  $html = str_get_html($html_source);

  $tr = $html->find('.detail-table tbody tr');

  $source=$name=$main_phone=$main_email=$website=$website_valid=$email_extension=$linkedin=$instagram=$facebook_x=$pinterest=$tiktok=$youtube=$mission=$veteran_owned_business=$is_certified_veteran_owned_business=$is_veteran_service_organization=$is_va_or_gov_organization=$va_or_gov_type=$va_or_gov_subtype=$service_area=$address_line_one=$address_line_two=$latitude=$longitude=$zip_code=$country=$state=$county=$municipality=$notes=$owner=$owner_phone=$owner_email='';

   $owner = $id.".html";

   $source = "careeronestop.org";
    $country = 'United States';

    $is_va_or_gov_organization = "Y";
    $va_or_gov_type = "Employment";
    $va_or_gov_subtype = "Employment Services - Job Center";



  foreach ($tr as $row) {

    // $service_area = $state_name;

    // $state_name

    
    // $state = $state_name;

    // $state_short_name = $state_name;



    //echo $row->innertext();
    


    $td_list = $row->find('td');

    $label = trim($td_list[0]->text());

    //echo $label;

    if($label == 'Phone') {
        $main_phone = $td_list[1]->text();
        $main_phone = strip_tags($main_phone);
    }

    if($label == 'Hours') {
        $notes = $td_list[1]->text();
        $notes = strip_tags($notes);
    }

    if($label == 'Website') {
        $website = $td_list[1]->text();
        $website = strip_tags($website);

    }


    if($label == 'Address') {

        $address = $td_list[1]->text();

        

          $address_array = explode("</br>", $address);

          //print_R($address_array);

          $address_line_one = $address_array[0];

          if(count($address_array)>3) {
            $address_line_two = $address_array[1];
            $municipality = $address_array[2];
          }
          else {
            $municipality = $address_array[1];
          }

          //echo $municipality;
           echo $address_line_one .PHP_EOL;
          

          $zip_code = extractZipFromAddress($address);

          $municipality = str_replace($zip_code, '', $municipality);

          $municipality = trim($municipality);

          if(preg_match('/,/', $municipality)) {
                    
                    $array = explode(",", $municipality);
                    $municipality = trim($array[0]);
                    $state_short_name = trim($array[1]);
                    $state = $state_list[$state_short_name];
                    
        }




    }

    $tr = $html->find('#GenInfo a');

    foreach($html->find('#GenInfo a') as $productDetail){
        if(preg_match('/mailto/', $productDetail->href)) { 
            $main_email = $productDetail->href;
            $main_email = str_replace('mailto:', "", $main_email);
            break;
        }

    }

    




    //echo "=========================\n";
    //echo "=========================\n";
  



 



    

   }

   $statename = $state;
   if(preg_match('/ /', $state)) {
            $statename = urlencode($name);
        }

        $source = "https://www.careeronestop.org/localhelp/americanjobcenters/find-american-job-centers.aspx?&location=".strtolower($statename)."&radius=20&curPage=1&pagesize=200";


    if(preg_match("/locinfo = (.*?)\n/",$html_source, $match )) {

        $text = trim($match[1]);

        $text = str_replace("};", "}", $text);

        //print_R($text);

        $text_array = json_decode($text, 1);
        //print_R($text_array);

        

        $latitude = $text_array['LAT'];
        $longitude = $text_array['LON'];
        $name = $text_array['NAME'];





    }    

    

    $dataRow = [$source,$name,$main_phone,$main_email,$website,$website_valid,$email_extension,$linkedin,$instagram,$facebook_x,$pinterest,$tiktok,$youtube,$mission,$veteran_owned_business,$is_certified_veteran_owned_business,$is_veteran_service_organization,$is_va_or_gov_organization,$va_or_gov_type,$va_or_gov_subtype,$service_area,$address_line_one,$address_line_two,$latitude,$longitude,$zip_code,$country,$state,$county,$municipality,$notes,$owner,$owner_phone,$owner_email,];

    foreach($dataRow as &$item) {
        $item = str_replace("&amp;", "&", $item);
        $item = trim($item);
    }
        
    
    //print_R($dataRow);die();


    
    $result[] = $dataRow;
  
  


}





$filename = "careeronestop-".time().".csv";

$f = fopen($filename, "w");
foreach ($result as $line) {
  fputcsv($f, $line);
}

die('Done');


