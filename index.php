<?php
use SNicholson\PHPDocxTemplates\RuleCollection;

include 'vendor/autoload.php';

$template = new \SNicholson\PHPDocxTemplates\TemplateFile();

$ruleCollection = new RuleCollection();
$ruleTarget = 'testText';
$ruleData = function(){
    return 'thisisatest'.'test';
};
$ruleCollection->addSimpleRule($ruleTarget,$ruleData);
$ruleCollection->addSimpleRule($ruleTarget,$ruleData);
$ruleCollection->addSimpleRule($ruleTarget,$ruleData);
$ruleCollectionRules = $ruleCollection->getRules();

var_dump($ruleCollectionRules);

foreach($ruleCollectionRules AS $rule){
    var_dump($rule);
}