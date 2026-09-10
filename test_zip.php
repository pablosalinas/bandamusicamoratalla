<?php
$zip = new \ZipArchive();
$path = 'test_enc.zip';
if ($zip->open($path, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
    $zip->setPassword('test');
    $zip->addFromString('test.txt', 'hello world');
    $res = $zip->setEncryptionName('test.txt', \ZipArchive::EM_AES_256);
    var_dump($res);
    $zip->close();
}
