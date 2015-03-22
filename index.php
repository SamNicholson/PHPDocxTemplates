<?php
include 'vendor/autoload.php';

$template = new \SNicholson\PHPDocxTemplates\TemplateFile();

$template->setFilename('test.docx');
$template->setFilename('invalid');


echo('Run');
