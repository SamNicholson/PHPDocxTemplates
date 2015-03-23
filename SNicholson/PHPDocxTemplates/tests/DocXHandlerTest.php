<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 22/03/2015
 * Time: 17:18
 */

namespace SNicholson\PHPDocxTemplates\Tests;


use SNicholson\PHPDocxTemplates\DocXHandler;
use ZipArchive;

class DocXHandlerTest extends \PHPUnit_Framework_TestCase {

    private $templateFileMock;
    private $zipArchiveMock;

    function setUp(){
        $this->templateFileMock = $this->getMock('SNicholson\PHPDocxTemplates\TemplateFile');
        $this->zipArchiveMock = $this->getMock('ZipArchive');
    }

    function createDocXHandlerTest(){
        return new DocXHandler($this->templateFileMock,$this->zipArchiveMock);
    }

    function testCheckReadIteratesOverAllZipEntries(){
        $docXHandler = $this->createDocXHandlerTest();
        $this->templateFileMock->expects($this->once())->method('getFilename')->willReturn('test.docx');
        $this->zipArchiveMock->expects($this->once())->method('open')->with('test.docx')->willReturn(true);
        $this->zipArchiveMock->expects($this->once())->method('close')->willReturn(true);
        $docXHandler->read();
    }

    function testInvalidXMLFileNameThrowsExpception(){
        $docXHandler = $this->createDocXHandlerTest();
        $this->setExpectedException('InvalidArgumentException');
        $docXHandler->getXMLFile('Thisonedoesntexist');
    }

    function testSetXMLAndGetXMLWork(){
        $docXHandler = $this->createDocXHandlerTest();
        $sampleXMLFilename = 'test.xml';
        $sampleXMLContent = 'Some XML Content';
        $docXHandler->setXMLFile($sampleXMLFilename,$sampleXMLContent);
        $this->assertEquals($sampleXMLContent,$docXHandler->getXMLFile($sampleXMLFilename));
    }

    function testSaveAsCreatesNewDocXFile(){
        $sampleXML = 'sampleXMlContent';
        $docXHandler = $this->createDocXHandlerTest();
        $this->zipArchiveMock->expects($this->once())->method('open')->with('test.docx', ZipArchive::CREATE)->willReturn(true);
        $this->zipArchiveMock->expects($this->once())->method('addFromString')->with('test.xml',$sampleXML)->willReturn(true);
        $this->zipArchiveMock->expects($this->once())->method('close')->willReturn(true);
        $docXHandler->setXMLFile('test.xml',$sampleXML);
        $docXHandler->saveAs('test.docx');
    }

    function testOverwriteTemplateSavesZipFile(){
        $sampleXML = 'sampleXMlContent';
        $docXHandler = $this->createDocXHandlerTest();
        $this->zipArchiveMock->expects($this->once())->method('addFromString')->with('test.xml',$sampleXML)->willReturn(true);
        $this->zipArchiveMock->expects($this->once())->method('close')->willReturn(true);
        $docXHandler->setXMLFile('test.xml',$sampleXML);
        $docXHandler->overwriteTemplate('test.docx');
    }

}
