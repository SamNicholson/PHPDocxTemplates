<?php

use SNicholson\PHPDocxTemplates\DXF;
use SNicholson\PHPDocxTemplates\RuleCollection;
use SNicholson\PHPDocxTemplates\DocXTemplate;

include '../../vendor/autoload.php';

$ruleCollection = new RuleCollection();
$ruleTarget     = '#INVOICE_TABLE_HEADER#';
$ruleData       = function () {
    return DXF::BOLD_START . 'thisisatesttest' . DXF::BOLD_END . DXF::LINEBREAK . 'test test test';
};
$ruleCollection->addSimpleRule($ruleTarget, $ruleData);

DocXTemplate::enableHTMLFormatting();
DocXTemplate::merge('../../temp/testme.docx', '../../temp/testsimplemerge.docx', $ruleCollection);
