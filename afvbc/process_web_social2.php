<?php

include "common.php";
$csv_data = read_csv('combatvet.csv');





$i = 0;
$duplicate = [];

//print_R($url_list);

$new_csv_data = $csv_data;



print_R($new_csv_data[0]);

$start = 0;

$end = 787;

foreach($new_csv_data as $key => $data) {
    //$new_csv_data[$key][] = "$key.html";

    if($key == 0) continue;

    // if($key<11) continue;
    // if($key>13) continue;

    $website = $data[4];
    if(!$website)  continue;

    //echo $key. $website."\n";

    $filename = "website_text/$key.html";
    
    

    
    

    $filename_text = "website_text/$key.html";
    $filename_contact_text = "website_contact_text/$key.html";

   if(!file_exists($filename)) continue;


    if(filesize($filename) <10) $new_csv_data[$key][5] =  "N";

        // $new_csv_data[$key][7] =  "";
        // $new_csv_data[$key][8] =  "";
        // $new_csv_data[$key][9] =  "";
        // $new_csv_data[$key][10] =  "";
        // $new_csv_data[$key][11] =  "";
        // $new_csv_data[$key][12] =  "";
        // $new_csv_data[$key][13] =  "";

        $html = "";
        $html = file_get_contents($filename);

        if(file_exists($filename_contact_text)) {
            $html .=  file_get_contents($filename_contact_text);
        }





        if($tw = extractEmails($html)) {
            $new_csv_data[$key][3] =  $tw;

             $extension = extractEmailExtension($tw);
            $new_csv_data[$key][6] =  $extension; 

        }


     
$links = getTextInBrackets($html);

  foreach($links as $link) {
    
        echo $key ." " . $link. PHP_EOL;

        $new_csv_data[$key][5] =  "Y";

        if(preg_match("|tel|", $link)) {
            $link = str_replace("tel:", "", $link);
            $new_csv_data[$key][2] =  $link;
            continue;
            //die();
        }
        if(preg_match("|linkedin.com|", $link)) {
            if(preg_match("|shareArticle|", $link)) continue;
            $new_csv_data[$key][7] =  $link;
        }
        if(preg_match("|instagram.com|", $link)) {
            $new_csv_data[$key][8] =  $link;
        }
        


        if(preg_match("|facebook.com|", $link)) {
            if(preg_match("|sharer.|", $link)) continue;
            $new_csv_data[$key][9] =  $link;
        }
        if(preg_match("|twitter.com|", $link)) {
            $new_csv_data[$key][10] =  $link;
        }
        if(preg_match("|pinterest|", $link)) {
            $new_csv_data[$key][11] =  $link;
        }
        if(preg_match("|tiktok.com|", $link)) {
            $new_csv_data[$key][12] =  $link;
        }
        if(preg_match("|youtube.com|", $link)) {
            $new_csv_data[$key][13] =  $link;
        }

         
    
    }

    //die();

  
       

     
        


        
        

    

    
  
  
}





$filename = "vcbc-web".time().".csv";

$f = fopen($filename, "w");
foreach ($new_csv_data as $line) {
  fputcsv($f, $line);
}

die('Done');


function getTextInBrackets($input) {
    // Regular expression to match all content inside square brackets
    preg_match_all('/\[([^\]]+)\]/', $input, $matches);
    
    // The matches are stored in $matches[1] because $matches[0] will contain the full pattern including the brackets.
    return $matches[1];
}


function getPinterestUrl($html) {
    // Regular expression pattern to match Pinterest URLs
    $pattern = '/https?:\/\/(?:www\.)?pinterest\.com\/[\w\/.-]+/';

    // Search for Pinterest URLs in the HTML
    if (preg_match($pattern, $html, $matches)) {
        // Return the first match found
        return $matches[0];
    } else {
        // If no Pinterest URL found, return false
        return false;
    }
}


function extractEmails($html) {
    // Regular expression pattern to match email addresses
    $pattern = '/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/';

    // Array to store extracted email addresses
    $emails = array();

    // Perform regular expression match
    preg_match_all($pattern, $html, $matches);

    // Iterate through matched email addresses
    foreach ($matches[0] as $email) {
        if(preg_match('/@/', $email)) {

            return $email;
            continue;
        }

        $emails[] = $email;
    }

    return "";
}


function extractInstagramUrls($html) {
    // Regular expression pattern to match Instagram URLs
    $pattern = '/(?:https?:\/\/)?(?:www\.)?(?:instagram\.com\/(?:[a-zA-Z0-9_\.]+\/?)|(?:p|tv)\/[a-zA-Z0-9_\-]+)\/?/i';

    // Array to store extracted URLs
    $urls = array();

    // Perform regular expression match
    preg_match_all($pattern, $html, $matches);

    // Iterate through matched URLs
    foreach ($matches[0] as $url) {
        if(preg_match('/instagram.com/', $url)) {

            return extractUrls($url);
            continue;
        }
        $urls[] = $url;
    }

    return "";
}

function extractYoutubeUrls($html) {
    // Regular expression pattern to match Instagram URLs
    $pattern = '/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:watch\?v=|embed\/|v\/|user\/(?:\S+\/)?|user\/)?|youtu\.be\/)([a-zA-Z0-9_-]{11})/';

    // Array to store extracted URLs
    $urls = array();

    // Perform regular expression match
    preg_match_all($pattern, $html, $matches);

    // Iterate through matched URLs
    foreach ($matches[0] as $url) {
        if(preg_match('/youtube.com/', $url)) {

            $url = extractUrls($url);

            if(preg_match('/watch/', $url)) {
            
                    continue;
            }
            if(preg_match('/embed/', $url)) {
            
                    continue;
            }



            return $url;
            continue;
        }
        $urls[] = $url;
    }

    return "";
}



function extractFacebookUrls($html) {
    // Regular expression pattern to match Facebook URLs
    $pattern = '/https?:\/\/(?:www\.)?facebook\.com\/[\w\/.-]+/';

    // Array to store all Facebook URLs found in the HTML
    $facebookUrls = array();

    // Search for all Facebook URLs in the HTML
    preg_match_all($pattern, $html, $matches);

    // Loop through the matches and add them to the result array
    foreach ($matches[0] as $match) {

        if($match == 'https://www.facebook.com/tr') continue;
        if(preg_match('/fbml/', $match)) {

            
            continue;
        }



        return $match;

        $facebookUrls[] = $match;
    }

    // Return the array of Facebook URLs
    return '';
}




function hasAboutPage($html) {
    // Fetch the HTML content of the websit

    // Check if the HTML contains common indicators of an About page
    if (stripos($html, '<h1>About</h1>') !== false || stripos($html, '<h2>About</h2>') !== false || stripos($html, 'About Us') !== false) {
        return true;
    } else {
        return false;
    }
}




function extractTwitterUrls($html) {
    // Regular expression pattern to match Twitter URLs
    $pattern = '/(?:https?:\/\/)?(?:www\.)?(?:twitter\.com\/(?:[^\/]+\/?|\?)(?:\?[^\/]+)?)|(?:(?:[a-zA-Z0-9]+\.)?twitter\.com\/(?:[^\/]+\/?|\?)(?:\?[^\/]+)?)/';

    // Array to store extracted URLs
    $urls = array();

    // Perform regular expression match
    preg_match_all($pattern, $html, $matches);

    // Iterate through matched URLs
    foreach ($matches[0] as $url) {

        if(preg_match('/twitter.com/', $url)) {

            $url = extractUrls($url);

            if($url == 'https://twitter.com/') continue; 

            return $url;
            continue;
        }
        $urls[] = $url;
    }

    return '';
}


function extractTikTokUrls($html) {
    // Regular expression pattern to match TikTok URLs
    $pattern = '/(?:https?:\/\/)?(?:www\.)?(?:tiktok\.com\/(?:@[^\/]+\/video\/\d+|@[^\/]+)|vm\.tiktok\.com\/(?:[a-zA-Z0-9]{6}))/';

    // Array to store extracted URLs
    $urls = array();

    // Perform regular expression match
    preg_match_all($pattern, $html, $matches);

    // Iterate through matched URLs
    foreach ($matches[0] as $url) {

        if(preg_match('/tiktok.com/', $url)) {

            return extractUrls($url);
            continue;
        }
        $urls[] = $url;
    }

    return '';
}

function extractLinkedinUrls($html) {
    // Regular expression pattern to match LinkedIn URLs
    $pattern = '/(?:https?:\/\/)?(?:www\.)?(?:linkedin\.com\/.*)/i';

    // Array to store extracted URLs
    $urls = array();

    // Perform regular expression match
    preg_match_all($pattern, $html, $matches);


//print_R($matches);
    // Iterate through matched URLs
    foreach ($matches[0] as $url) {
        //print_R($matches);die();
        if(preg_match('/linkedin.com/', $url)) {

            return extractUrls($url);
            continue;
        }
        $urls[] = $url;
    }

    return "";
}

function extractUrls($html) {
    // Regular expression pattern to match URLs
    $pattern = '/(?<!\w)((?:https?|ftp):\/\/(?:[\w+&@#\/%?=~_|!:,.;-]*[\w+&@#\/%=~_|]))/';

    // Array to store extracted URLs
    $urls = array();

    // Perform regular expression match
    preg_match_all($pattern, $html, $matches);

    // Iterate through matched URLs
    foreach ($matches[0] as $url) {
        return $url;
        $urls[] = $url;
    }

    return '';
}