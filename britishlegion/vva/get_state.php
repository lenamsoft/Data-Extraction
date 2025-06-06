<?php
include "common.php";
$post_code = ["AB","AL","B","BA","BB","BD","BH","BL","BN","BR","BS","BT","CA","CB","CF","CH","CM","CO","CR","CT","CV","CW","DA","DD","DE","DG","DH","DL","DN","DT","DY","E","EC","EH","EN","EX","FK","FY","G","GL","GU","HA","HD","HG","HP","HR","HS","HU","HX","IG","IP","IV","KA","KT","KW","KY","L","LA","LD","LE","LL","LN","LS","LU","M","ME","MK","ML","N","NE","NG","NN","NP","NR","NW","OL","OX","PA","PE","PH","PL","PO","PR","RG","RH","RM","S","SA","SE","SG","SK","SL","SM","SN","SO","SP","SR","SS","ST","SW","SY","TA","TD","TF","TN","TQ","TR","TS","TW","UB","W","WA","WC","WD","WF","WN","WR","WS","WV","YO","ZE"];
$data_html = json_decode(file_get_contents('data_html.json'));
$datahtml = [];
foreach ($data_html as $key => $html) {
  $htmlas = str_get_html($html);
  $results = html_entity_decode($htmlas->find('h3',0)->plaintext);
  
  if (!empty($results)) {
    $website = html_entity_decode($htmlas->find('a',0)->plaintext);
    $datahtml[trim($results)] = $website;
  }
  
}
print_r(array_keys($datahtml));
print_r(($datahtml));
die();
// foreach ($post_code as $key => $code) {
//    $url = 'https://www.ssafa.org.uk/localbranchresults/getbranches?&pc='.$code.'1';
//    $data = doService2($url);
//    if (!empty($data)) {
//     $data_html[] = $data;
//    }
// }
// file_put_contents('data_html.json', json_encode($data_html));
// die();
function doService2($url, $action = "GET") {

  $cookie_file_path = "cookie.txt";
  $wwwhandle = curl_init();
  curl_setopt($wwwhandle, CURLOPT_TIMEOUT, 20);

  curl_setopt($wwwhandle, CURLOPT_URL, $url);
  curl_setopt($wwwhandle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($wwwhandle, CURLOPT_USERAGENT, "User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:80.0) Gecko/20100101 Firefox/80.0");
  curl_setopt($wwwhandle, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($wwwhandle, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
  curl_setopt($wwwhandle, CURLOPT_VERBOSE, true);

  curl_setopt($wwwhandle, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($wwwhandle, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($wwwhandle, CURLOPT_ENCODING, '');
  curl_setopt($wwwhandle, CURLOPT_DNS_CACHE_TIMEOUT, "-1");

  // curl_setopt($wwwhandle, CURLOPT_COOKIESESSION, true);
  // curl_setopt($wwwhandle, CURLOPT_COOKIEJAR, $cookie_file_path);
  // curl_setopt($wwwhandle, CURLOPT_COOKIEFILE, $cookie_file_path);

  $headers = ['Host: offices.sc.egov.usda.gov','User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:80.0) Gecko/20100101 Firefox/80.0', 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8'];

  curl_setopt($wwwhandle, CURLOPT_HTTPHEADER, $headers);

  if ($action == "POST") {

    curl_setopt($wwwhandle, CURLOPT_POST, 1);
    curl_setopt($wwwhandle, CURLOPT_POSTFIELDS, $parameter);
  }

  $page = curl_exec($wwwhandle);

  //$httpcode = curl_getinfo($wwwhandle, CURLINFO_HTTP_CODE);
  curl_close($wwwhandle);
  

  if (strpos($page, 'but we were unable to find') === false) {
    return $page;
  }else{
    return ;
  }
 
  
}