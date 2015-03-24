<?php
use SNicholson\PHPDocxTemplates\DocXHandler;
use SNicholson\PHPDocxTemplates\Merger;
use SNicholson\PHPDocxTemplates\RuleCollection;
use SNicholson\PHPDocxTemplates\SimpleMerge;

include __DIR__.'../../../vendor/autoload.php';

$ruleCollection = new RuleCollection();
$ruleTarget = '#test#';
$ruleData = function(){
    return 'thisisatest'.'test';
};
$ruleCollection->addSimpleRule($ruleTarget,$ruleData);
$ruleCollection->addSimpleRule('#amergecode#','OR DID I DO THIS?');

SimpleMerge::perform('temp/testme.docx','temp/testsimplemerge.docx',$ruleCollection);
