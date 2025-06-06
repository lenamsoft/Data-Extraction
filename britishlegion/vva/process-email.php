<?php
include "common.php";


$data = read_csv('Britishlegion - Sheet3.csv');

// print_R($url_list);die();


foreach($data as $key=>$row) {

    $url = $row[7];
  //$url = "https://branches.britishlegion.org.uk/branches/abbots-langley";

if(!preg_match('/https/', $url)) {
            continue;
        }

        $i = $key;

$filename = "html/$i.html";
        if(file_exists($filename)) {

            $html = file_get_contents($filename);

           
            $html = str_get_html($html);

            foreach($html->find('a') as $productDetail){

                $url = $productDetail->href;

                if(preg_match('/mailto/', $url)) {
                    $email = str_replace('mailto:',  "", $url);
                    $data[$key][6] = $email;

                    continue;

                }

            }

            continue;
            

        }



  echo $key. $url."\n";

  

  //$html = doService($url);

  //file_put_contents("html/$i.html", $html);

  //die();


}

$filename = "saa-".time().".csv";

$f = fopen($filename, "w");
foreach ($data as $line) {
  fputcsv($f, $line);
}

die('Done');
