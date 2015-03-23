<?php
use SNicholson\PHPDocxTemplates\DocXHandler;
use SNicholson\PHPDocxTemplates\Merger;
use SNicholson\PHPDocxTemplates\RuleCollection;

include 'vendor/autoload.php';

$template = new \SNicholson\PHPDocxTemplates\TemplateFile();

$ruleCollection = new RuleCollection();
$ruleTarget = '#error#';
$ruleData = function(){
    return 'thisisatest'.'test';
};
$ruleCollection->addSimpleRule($ruleTarget,$ruleData);
$ruleCollection->addSimpleRule($ruleTarget,$ruleData);
$ruleCollection->addSimpleRule($ruleTarget,$ruleData);

$template->setFilename('temp/testme.docx');

$merger = new Merger(new DocXHandler(new ZipArchive()));

$merger->setTemplateFile($template);
$merger->setRuleCollection($ruleCollection);
$merger->merge();
$merger->saveMergedDocument('temp/output.docx');