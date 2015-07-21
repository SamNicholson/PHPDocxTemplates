<?php
use SNicholson\PHPDocxTemplates\DocXHandler;
use SNicholson\PHPDocxTemplates\Merger;
use SNicholson\PHPDocxTemplates\RuleCollection;
use SNicholson\PHPDocxTemplates\PHPDocX;

include __DIR__.'../../vendor/autoload.php';

$test = 1;

$ruleCollection = new RuleCollection();
$ruleTarget = '/Testing123/';
$ruleData = function($matches){
    global $test;
    $test++;
    return $test;
};
$ruleCollection->addRegexpRule($ruleTarget,$ruleData);

PHPDocX::merge('../temp/testme.docx','../temp/testsimplemerge.docx',$ruleCollection);
