<?php

namespace SNicholson\PHPDocxTemplates\Tests;

use PHPUnit_Framework_TestCase;
use SNicholson\PHPDocxTemplates\DocXTemplate;
use SNicholson\PHPDocxTemplates\TestHelper;

/**
 * Class FullMergeTests
 * This test tries to fully utilise the entire library to merge documents together, if all other tests fail
 * but this one passes then that'll do pig, that'll do!
 * @package SNicholson\PHPDocxTemplates\Tests
 */
class FullMergeTests extends PHPUnit_Framework_TestCase
{

    /**
     * @test Test Simple Document With Closures and string replaces merges correctly
     */
    public function testSimpleDocumentWithClosuresAndStringReplaceMerges()
    {
        $rc = DocXTemplate::ruleCollection();
        $rc->addSimpleRules(
            [
                '#MERGE#'           => 'I replaced merge!!',
                '#MERGE3'           => 'I replaced merge 3, noticed how we skipped merge 2?',
                '#code#'            => 'mergecode ( <-- just that bit )',
                '#FOOTERMERGE#'     => 'Yep, we can replace text in the footer',
                '#HEADERMERGE#'     => 'Yep, we can replace text in the header',
                '#JUSTMERGEONPAGE#' => 'I was the only text on a page!'
            ]
        );

        //Regexp for MERGETEST and capture the +X part
        $ruleTarget = '/#MERGETEST\+([0-9]*)#/';
        $ruleData   = function ($match) {
            return $match[0] . ' was replaced with this, it had the number ' . $match[1] . ' after it';
        };
        $rc->addRegexpRule($ruleTarget, $ruleData);

        $targetDocX      = __DIR__ . '\DocXFiles\SimpleMerge.docx';
        $referenceDocX   = __DIR__ . '\DocXFiles\SimpleMergeOutputReference.docx';
        $destinationDocX = __DIR__ . '\DocXFiles\SimpleMergeOutput.docx';

        DocXTemplate::merge($targetDocX, $destinationDocX, $rc);

        TestHelper::compare2DocXFiles($referenceDocX, $destinationDocX);
    }
}