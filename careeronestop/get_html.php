<?php
include "common.php";

$state_list = getUSStateAbbreviations();



$state_list['PR'] = 'Puerto Rico';

$state_list['GU'] = 'Guam';

$state_list['MP'] = 'NORTHERN MARIANA IS';

$i = 1;

$k = 1;

foreach($state_list as $state_short=> $name) {

    $i = 1;

    $filename = "html_state/$state_short.html";

    $html = file_get_contents($filename);

    $html = str_get_html($html);

    foreach($html->find('#AJCTable a.notranslate ') as $productDetail){
                                    
                    $url =  $productDetail->href ;

                    if(preg_match('/localhelp/', $url)) {

                          
                        

                        

                        

                        

                        $file_name_item = "html/$state_short-$i.html";

                        $file_name_item2 = "html/$k.html";

                        

                        $i++;
                        $k++;

                        //if($state_short != "PR" && $state_short != "MP") continue;

                        $url = "https://www.careeronestop.org".$url;



                        $url = str_replace(" ", "+", $url);

                        if($file_name_item2 == "html/1752.html") echo $url;

                        continue;



                        echo "$k $url" . PHP_EOL;

                        if(file_exists($file_name_item2)) {continue;}

                        $html = doService($url);

                            file_put_contents($file_name_item2, $html);

                            echo $url. PHP_EOL;

                        

                        



                    }

                    
    }




            



    


}

die();


$file = 'index.html';
$html = file_get_contents($file);

    //echo $html; die();
$html = str_get_html($html);

$i = 0;
foreach($html->find('a.chamber-finder__state-link') as $productDetail){

      //echo $productDetail->href. "<br>";

      $url = $productDetail->href;

      $name = '';

      $name = $productDetail->find('.state-span', 0)->text();

      if(!$name) continue;

      if(!preg_match('/http/', $url)) {
            continue;
        }

      echo $name. $productDetail->href. "<br>";

      $dataRow = [$url,$name];
      $result[] = $dataRow;

      $html = doService($url);

  file_put_contents("html/$i.html", $html);

  

      $i++;


    }


    //print_R($result);

//die();


$filename = "url_list.csv";

$f = fopen($filename, "w");
foreach ($result as $line) {
  fputcsv($f, $line);
}



function getAllHtmlFiles($folderPath) {
    // Define the search pattern to find HTML files
    $searchPattern = "$folderPath/*.html";

    // Get an array of file paths matching the pattern
    $htmlFiles = glob($searchPattern);

    return $htmlFiles;
}