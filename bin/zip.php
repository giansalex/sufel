<?php

function zip($zipPath, $folder) {
    $zip = new ZipArchive();
    if (!$zip->open($zipPath, ZIPARCHIVE::OVERWRITE))
        die("Failed to create archive\n");

    $zip->addGlob("$folder/*");
    if (!$zip->status == ZIPARCHIVE::ER_OK)
        echo "Failed to write files to zip\n";

    $zip->close();
}

zip($argv[1], $argv[2]);