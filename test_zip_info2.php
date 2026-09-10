<?php
$zip = new \ZipArchive();
$path = 'test_enc2.zip';
if ($zip->open($path, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
    $zip->setPassword('test');
    $zip->addFromString('test.txt', 'hello world');
    $zip->setEncryptionName('test.txt', \ZipArchive::EM_TRAD_PKWARE);
    $zip->close();
}

$zip = new \ZipArchive();
if ($zip->open('test_enc2.zip') === true) {
    print_r($zip->statName('test.txt'));
}
