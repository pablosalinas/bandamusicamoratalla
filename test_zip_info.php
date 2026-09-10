<?php
$zip = new \ZipArchive();
if ($zip->open('test_enc.zip') === true) {
    echo "Is encrypted: " . ($zip->statName('test.txt')['comp_method'] ?? 'unknown') . "\n";
    print_r($zip->statName('test.txt'));
}
