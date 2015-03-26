<?php
use SNicholson\PHPDocxTemplates\DocXHandler;
use SNicholson\PHPDocxTemplates\Merger;
use SNicholson\PHPDocxTemplates\RuleCollection;
use SNicholson\PHPDocxTemplates\SimpleMerge;

include '../../vendor/autoload.php';

$ruleCollection = new RuleCollection();
$ruleTarget = '#test#';
$ruleData = function(){
    return 'thisisatest'.'test';
};
$ruleCollection->addSimpleRule($ruleTarget,$ruleData);

SimpleMerge::perform('../temp/testme.docx','../temp/testsimplemerge.docx',$ruleCollection);
