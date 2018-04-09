<?php

function downloadBox($file) {
    if (file_exists($file)) {
        return;
    }

    $options  = array('http' => array('user_agent'=> 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36'));
    $context  = stream_context_create($options);

    $latest = file_get_contents('https://api.github.com/repos/box-project/box2/releases/latest', false, $context);
    $obj = json_decode($latest);
    if (!isset($obj->assets)) {
        echo 'No se pudo encontrar box assets';
        return;
    }
    $pathDownload = '';
    foreach ($obj->assets as $asset) {
        if (strtolower(pathinfo($asset->name, PATHINFO_EXTENSION)) == 'phar') {
            $pathDownload = $asset->browser_download_url;
        }
    }

    if (empty($pathDownload)) {
        echo 'No se pudo encontrar box.phar';
        return;
    }
    $box = file_get_contents($pathDownload, false, $context);
    file_put_contents($file, $box);
}
//chdir(__DIR__.'/../');

$file = 'box.phar';
if (count($argv) > 1) {
    $file = $argv[1];
}

echo "Downloading box in $file".PHP_EOL;
downloadBox($file);

// MOVE SETTINGS
//copy(__DIR__.'/../docker/settings.php', __DIR__.'/../src/settings.php');

// RUN COMPOSER
//exec('composer install --no-interaction --no-dev --optimize-autoloader 2>&1', $output);
//exec('composer dump-autoload --optimize --no-dev --classmap-authoritative 2>&1', $output);

// RUN BOX
//exec("php -d phar.readonly=0 $file build 2>&1", $output);
//echo implode(PHP_EOL, $output);

