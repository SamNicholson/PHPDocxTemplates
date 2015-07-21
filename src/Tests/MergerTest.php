<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 23/03/2015
 * Time: 00:17
 */

namespace SNicholson\PHPDocxTemplates\Tests;


use SNicholson\PHPDocxTemplates\Merger;
use SNicholson\PHPDocxTemplates\RuleCollection;
use SNicholson\PHPDocxTemplates\TemplateFile;

class MergerTest extends \PHPUnit_Framework_TestCase
{

    private $docXHandlerMock;

    public function setUp()
    {
        $this->docXHandlerMock = $this->getMock('SNicholson\PHPDocxTemplates\DocXHandler', [], [], '', false);
    }

    public function testGetterSetterRuleCollection()
    {
        $merger             = $this->getMerger();
        $ruleCollectionMock = new RuleCollection();
        $merger->setRuleCollection($ruleCollectionMock);
        $this->assertEquals($ruleCollectionMock, $merger->getRuleCollection());
    }

    public function testGetterSetterTemplateFile()
    {
        $merger           = $this->getMerger();
        $templateFileMock = new TemplateFile();
        $merger->setTemplateFile($templateFileMock);
        $this->assertEquals($templateFileMock, $merger->getTemplateFile());
    }

    public function testSaveMergedDocument()
    {
        $merger           = $this->getMerger();
        $templateFileMock = new TemplateFile();
        $merger->setTemplateFile($templateFileMock);
        $ruleCollectionMock = new RuleCollection();
        $merger->setRuleCollection($ruleCollectionMock);
        $this->docXHandlerMock->expects($this->once())->method('saveAs')->with('test.docx');
        $this->docXHandlerMock->expects($this->once())->method('getXMLFilesToBeSearched')->willReturn([]);
        $merger->saveMergedDocument('test.docx');
    }

    public function getMerger()
    {
        return new Merger($this->docXHandlerMock);
    }

}
