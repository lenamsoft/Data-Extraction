<?php
include "common.php";

$files = glob('./html/*.html');

$result = [];
$result[] = [
  'Source',
  'Name',
  'Website',
  'Logo',
  'Location',
  'Categories',
  'Linkedin',
  'Instagram',
  'Facebook',
  'X (formerly Twitter)',
  'Pinterest',
  'TikTok',
  'Youtube',
];

$i = 0;
$duplicate = [];
foreach($files as $file) {
  $html = file_get_contents($file);
  $html = str_get_html($html);
  $tr = $html->find('.cb-collection-item');
  foreach ($tr as $row) {
    $row_csv = [
      'avob.org.au',
      $row->find('.business-title', 0)->plaintext,
      $row->find('.website-link', 0)->plaintext,
      $row->find('.business-logo', 0)->src,
      $row->find('.filter-category', 0)->plaintext,
      $row->find('.filter-location', 0)->plaintext,
      '',
      '',
      '',
      '',
      '',
      '',
      ''
    ];
    

    foreach ($row->find('.social-media-filter-wrapper a') as $key => $value) {
      $href = $value->href;
      if ($href != '#' && strpos($href, 'linkedin') !== false ) {
        $row_csv[6] = $href;
      }elseif ($href != '#' && strpos($href, 'instagram') !== false ) {
        $row_csv[7] = $href;
      }elseif ($href != '#' && strpos($href, 'facebook') !== false ) {
        $row_csv[8] = $href;
      }elseif ($href != '#' && strpos($href, 'twitter') !== false ) {
        $row_csv[9] = $href;
      }elseif ($href != '#' && strpos($href, 'pinterest') !== false ) {
        $row_csv[10] = $href;
      }elseif ($href != '#' && strpos($href, 'tikTok') !== false ) {
        $row_csv[11] = $href;
      }elseif($href != '#' && strpos($href, 'youtube') !== false ) {
        $row_csv[12] = $href;
      }
    }
    $result[] = $row_csv;
  }
}


$f = fopen("avob.csv", "w");
foreach ($result as $line) {
  fputcsv($f, $line);
}
print  '<a href="https://hoc.livesporttoday.com/avob/avob.csv">avob.csv</a>';
die('Done');
