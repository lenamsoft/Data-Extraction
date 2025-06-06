<?php

include "common.php";
$csv_data = read_csv('careeronestop-1713077863.csv', 1500);


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
X (formerly Twitter)
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

$new_csv_data = $csv_data;

foreach($new_csv_data as $key => $data) {

    for($i = 15;$i<=34;$i++) {
        //unset($new_csv_data[$key][$i]);
    }
}


//$new_csv_data = array_slice($new_csv_data, 0 , 45);

// print_R($new_csv_data[0]);die();

$start = 0;

$end = 3;

foreach($new_csv_data as $key => $data) {
    //$new_csv_data[$key][] = "$key.html";

    if($key == 0) continue;


    //if($key>$end) continue;

    $website = $data[4];
    //if(!$website)  continue;

    //echo $key. $website."\n";

    $filename = "website/$key.html";

    //$tw = $data[13];

    //echo $tw;

    //if($tw)  continue;

    //$new_csv_data[$key][5] =  'N';

    $mail = $new_csv_data[$key][3];

        $new_csv_data[$key][6] =  '';

        if($mail) {
                $extension = extractEmailExtension($mail);
                $new_csv_data[$key][6] =  $extension;
                //continue;
        }
        

    $filename2 = "website_text_short/$key.html";

    // if(!file_exists($filename)) {
    //     $page = doService($website);
    //     file_put_contents($filename, $page);
    //     echo $filename;die();
    // }

    //continue;

    if(file_exists($filename)) {
        
        $html = file_get_contents($filename);
        //$html2 = file_get_contents($filename2);

        // if(preg_match('/youtube/', $html)) {
        //     echo $key . " $website ". PHP_EOL;
        // }

        if($youtube = extractYoutubeUrls($html)) {
            echo $key. $website."\n";  

            echo $key. $youtube."\n";     

            $new_csv_data[$key][13] =  $youtube;  
            $new_csv_data[$key][5] =  'Y';

        }


        if($tw = extractLinkedinUrls($html)) {
            echo $key. $website."\n";  

            echo $key. $tw."\n";     

            $new_csv_data[$key][7] =  $tw;  
            $new_csv_data[$key][5] =  'Y';

        }

        



        if($instagram = extractInstagramUrls($html)) {
            //echo $key. $website."\n";  

            //echo $key. $instagram."\n";     

            $new_csv_data[$key][8] =  $instagram;  
            $new_csv_data[$key][5] =  'Y';

        }

        $fb = '';

        if($fb = extractFacebookUrls($html)) {
            echo $key. $website."\n";  

            echo $key. $fb."\n";     

            $new_csv_data[$key][9] =  $fb;  
            $new_csv_data[$key][5] =  'Y';

        }

        if($tw = extractTwitterUrls($html)) {
            echo $key. $website."\n";  

            echo $key. $tw."\n";     

            $new_csv_data[$key][10] =  $tw;  
            $new_csv_data[$key][5] =  'Y';

        }

        

        if($tw = getPinterestUrl($html)) {
            echo $key. $website."\n";  

            echo $key. $tw."\n";     

            $new_csv_data[$key][11] =  $tw;  
            $new_csv_data[$key][5] =  'Y';

        }

        if($tw = extractTikTokUrls($html)) {
            echo $key. $website."\n";  

            echo $key. $tw."\n";     

            $new_csv_data[$key][12] =  $tw;  
            $new_csv_data[$key][5] =  'Y';

        }

        if($youtube = extractYoutubeUrls($html)) {
            echo $key. $website."\n";  

            echo $key. $youtube."\n";     

            $new_csv_data[$key][13] =  $youtube;  
            $new_csv_data[$key][5] =  'Y';

        }
        // if($add = detectFirstAddressFromHTML($html)) {
        //     echo PHP_EOL;echo PHP_EOL;
        //     echo $website. PHP_EOL;

        //     echo $add;echo PHP_EOL;
        //     echo '------------------';
        //     echo PHP_EOL;echo PHP_EOL;

        //     $new_csv_data[$key][22] =  $add;  


        // }

        // if($phone = detectFirstPhoneNumberFromHTML($html2)) {
        //     echo PHP_EOL;echo PHP_EOL;
        //     echo $website. PHP_EOL;

        //     echo $phone;echo PHP_EOL;
        //     echo '------------------';
        //     echo PHP_EOL;echo PHP_EOL;

        //      $new_csv_data[$key][2] =  $phone;  
        // }

        // $new_csv_data[$key][5] =  'Y';

        

        // if($tw = extractEmails($html2)) {
        //     echo $key. $website."\n";  

        //     echo $key. $tw."\n";     

        //     $new_csv_data[$key][3] =  $tw;  
        //     $new_csv_data[$key][5] =  'Y';

        //     $extension = extractEmailExtension($tw);
        //     $new_csv_data[$key][6] =  $extension; 


        // }

        
        $new_csv_data[$key][5] =  'Y';
        //$new_csv_data[$key][1] =  "UT - ".$new_csv_data[$key][1];

    
        }
    

    
  
  
}





$filename = "careeronestop-web".time().".csv";

$f = fopen($filename, "w");
foreach ($new_csv_data as $line) {
  fputcsv($f, $line);
}

die('Done');

function detectFirstPhoneNumberFromHTML($html) {
    // Regular expression pattern to match phone numbers
    $pattern = '/\b(?:\+\d{1,2}\s?)?(?:\(\d{3}\)|\d{3})[\s.-]?\d{3}[\s.-]?\d{4}\b|\(\d{3}\)\s?\d{3}-\d{4}/';

    // Match phone numbers in the HTML content
    preg_match_all($pattern, $html, $matches);

    // If phone number is found, return the first match
    if (!empty($matches[0])) {

        foreach($matches[0] as $m) {

                if(strlen($m) == 10) continue;
                if(strlen($m) < 8) continue;
                return $m;
        }
        
    }

    // If no phone number is found, return null
    return null;
}

// Example HTML content
$html = "
    <html>
    <body>
        <div>
            <p>Phone: +1 (123) 456-7890</p>
            <p>Mobile: 987-654-3210</p>
            <p>Timestamp: 1618491263</p>
        </div>
    </body>
    </html>";

// Detect the first phone number from the HTML content
$phoneNumber = detectFirstPhoneNumberFromHTML($html);

// Output the detected phone number
if ($phoneNumber) {
    echo $phoneNumber;
} else {
    echo "No phone number found.";
}





function detectFirstAddressFromHTML($html) {
    // Create a DOMDocument instance to parse HTML
    $dom = new DOMDocument();
    // Load HTML content, suppress errors if any
    @$dom->loadHTML($html);
    
    // Initialize an empty array to store addresses
    $addresses = array();
    
    // Get all text nodes in the HTML content
    $textNodes = $dom->getElementsByTagName('body')->item(0)->getElementsByTagName('*');

    // Regular expression pattern to match addresses including zip codes
    $pattern = '/\b\d{1,5}\s+\w.*?\s+\d{5}(?:-\d{4})?/';

    // Loop through text nodes
    foreach ($textNodes as $node) {
        // Get text content of the node
        $textContent = $node->textContent;
        // Match addresses in the text content
        preg_match($pattern, $textContent, $matches);
        // If address is found
        if (!empty($matches)) {
            // Get the first match
            $address = $matches[0];
            // Check if the address length is less than or equal to 200 characters
            if (strlen($address) <= 200) {
                return $address;
            }
        }
    }
    
    // If no suitable address is found, return null
    return null;
}

// Example HTML content
$html = "
    <html>
    <body>
        <div>
            <p>123 Main St, City, State, 12345</p>
            <p>456 Elm St, City, State, 67890</p>
            <p>789 Oak St, City, State, 98765</p>
        </div>
    </body>
    </html>";

// Detect the first address from the HTML content
$address = detectFirstAddressFromHTML($html);

// Output the detected address
if ($address) {
    echo $address;
} else {
    echo "No suitable address found.";
}



// Example HTML content
$html = "
    <html>
    <body>
        <p>123 Main St, City, State</p>
        <p>456 Elm St, City, State</p>
        <p>789 Oak St, City, State</p>
    </body>
    </html>";




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

        if(preg_match('/png/', $email)) continue;
        if(preg_match('/jpg/', $email)) continue;

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

    if (preg_match('/https?:\/\/(?:www\.)?youtube\.com\/(?:watch\?v=|channel\/|user\/)([\w-]+)/', $html, $matches)) {
            $youtubeLinks[] = $matches[0];

            return $matches[0];
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
        if(preg_match('/sharer/', $match)) continue;
        if(preg_match('/profile.php/', $match)) continue;
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
        if(preg_match('/share/', $url)) continue;
        if(preg_match('/intent/', $url)) continue;

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
        if(preg_match('/shareArticle/', $url)) continue;

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