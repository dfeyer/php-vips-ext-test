<?php

use Imagine\Image\ImageInterface;

require __DIR__ . '/../vendor/autoload.php';

$s = microtime(true);

if (isset($_GET['driver'])) {
    $adapter = $_GET['driver'];
} else {
    $adapter = $argv[1] ?? 'Gd';
}
$filter = $adapter === 'Gd' ? ImageInterface::FILTER_UNDEFINED : ImageInterface::FILTER_LANCZOS;

function process(\Imagine\Image\AbstractImagine $imagine, string $filter) {
  $size    = new Imagine\Image\Box(600, 600);
  $mode    = Imagine\Image\ImageInterface::THUMBNAIL_INSET;

  $imagine->open('/Users/dfeyer/Documents/Projets/php-vips-ext-test/Web/large-one.jpg')
      ->thumbnail($size, $mode, $filter)
      ->save('/Users/dfeyer/Documents/Projets/php-vips-ext-test/Web/large-one-processed.jpg');
}

switch ($adapter) {
  case 'Gd':
    $driver = new \Imagine\Gd\Imagine();
    break;

  case 'Imagick':
    $driver = new \Imagine\Imagick\Imagine();
    break;

  case 'Vips':
    $driver = new \Imagine\Vips\Imagine();
    break;
}

process($driver, $filter);

$content = [];
$content[] = "Driver: ".$adapter;
$content[] = "Runtime: ".ceil((microtime(true) - $s) * 1000). " ms";
$content[] = "Memory usage: ".(memory_get_peak_usage(true)/1024/1024)." MiB (real)";
$content[] = "Memory usage: ".(memory_get_peak_usage(false)/1024/1024)." MiB";
$content[] = "Generated file size: ".(filesize('/Users/dfeyer/Documents/Projets/php-vips-ext-test/Web/large-one-processed.jpg')/1024)." KiB";

echo implode(chr(10), $content);
