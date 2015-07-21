<?php

namespace SNicholson\PHPDocxTemplates\Tests;

use SNicholson\PHPDocxTemplates\RuleCollection;
use SNicholson\PHPDocxTemplates\DocXTemplate;

/**
 * Class FullMergeTests
 * This test tries to fully utilise the entire library to merge documents together, if all other tests fail
 * but this one passes then that'll do pig, that'll do!
 * @package SNicholson\PHPDocxTemplates\Tests
 */
class FullMergeTests extends \PHPUnit_Framework_TestCase
{

    /**
     * @test Test Simple Document With Closures and string replaces merges correctly
     */
    public function testSimpleDocumentWithClosuresAndStringReplaceMerges()
    {

        $ruleCollection = new RuleCollection();

        //Replace the text merge
        $ruleCollection = new RuleCollection();
        $ruleTarget     = '#MERGE#';
        $ruleData       = function () {
            return 'I replaced merge!!';
        };
        //Replace the text merge 3
        $ruleCollection->addSimpleRule($ruleTarget, $ruleData);
        $ruleTarget = '#MERGE3#';
        $ruleData   = function () {
            return 'I replaced merge 3, noticed how we skipped merge 2?';
        };
        $ruleCollection->addSimpleRule($ruleTarget, $ruleData);

        //Replace the text code
        $ruleCollection->addSimpleRule($ruleTarget, $ruleData);
        $ruleTarget = '#code#';
        $ruleData   = function () {
            return 'mergecode ( <-- just that bit )';
        };
        $ruleCollection->addSimpleRule($ruleTarget, $ruleData);

        //Regexp for MERGETEST and capture the +X part
        $ruleTarget = '/#MERGETEST\+([0-9]*)#/';
        $ruleData   = function ($match) {
            return $match[0] . ' was replaced with this, it had the number ' . $match[1] . ' after it';
        };
        $ruleCollection->addRegexpRule($ruleTarget, $ruleData);


        $targetDocX = __DIR__ . '\DocXFiles\SimpleMerge.docx';
        $destinationDocX = __DIR__ . '\DocXFiles\SimpleMergeOutput.docx';

        DocXTemplate::merge($targetDocX, $destinationDocX, $ruleCollection);

        $this->assertEquals(file_get_contents($targetDocX), file_get_contents($destinationDocX));
    }

}