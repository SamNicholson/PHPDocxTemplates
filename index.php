<?php
include 'vendor/autoload.php';

echo('Run');

$zip = new \SNicholson\PHPWordMerger\ZipHandler();

$zip->test('temp/testme.docx');