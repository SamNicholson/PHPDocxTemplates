<?php
use SNicholson\PHPDocxTemplates\RuleCollection;
use SNicholson\PHPDocxTemplates\DocXTemplate;

include '../../vendor/autoload.php';

$ruleCollection = new RuleCollection();
$ruleTarget     = '#test#';
$ruleData       = function () {
    return 'thisisatest' . 'test';
};
$ruleCollection->addSimpleRule($ruleTarget, $ruleData);

DocXTemplate::merge('../temp/testme.docx', '../temp/testsimplemerge.docx', $ruleCollection);
