<?php
include 'vendor/autoload.php';

echo('Run');

$zip = new \SNicholson\PHPDocxTemplates\ZipHandler();

$zip->test('temp/testme.docx');