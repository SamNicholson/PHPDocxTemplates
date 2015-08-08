<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 22/03/2015
 * Time: 17:18
 */

namespace SNicholson\PHPDocxTemplates\Tests;


use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;
use SNicholson\PHPDocxTemplates\DocXHandler;
use SNicholson\PHPDocxTemplates\ZipArchive;

class DocXHandlerTest extends PHPUnit_Framework_TestCase {

    /** @var  PHPUnit_Framework_MockObject_MockObject */
    private $templateFileMock;
    /** @var  PHPUnit_Framework_MockObject_MockObject */
    private $zipArchiveMock;

    function setUp(){
        $this->templateFileMock = $this->getMock('SNicholson\PHPDocxTemplates\TemplateFile');
        $this->zipArchiveMock = $this->getMock('SNicholson\PHPDocxTemplates\ZipArchive');
    }

    function createDocXHandlerTest(){
        $handler = new DocXHandler($this->zipArchiveMock);
        $handler->setTemplateFile($this->templateFileMock);
        return $handler;
    }

    function testCheckReadIteratesOverAllZipEntries(){
        $docXHandler = $this->createDocXHandlerTest();
        $this->templateFileMock->expects($this->once())->method('getFilePath')->willReturn('test.docx');
        $this->zipArchiveMock->expects($this->once())->method('open')->with('test.docx')->willReturn(true);
        $this->zipArchiveMock->expects($this->once())->method('getNumFiles')->willReturn(2);
        $this->zipArchiveMock->expects($this->exactly(2))->method('getNameIndex')->willReturn('test');
        $this->zipArchiveMock->expects($this->exactly(2))->method('getFileContents')->willReturn('test');

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
        $docXHandler->overwriteTemplate();
    }

    function testXMLFilesToBeSearchedSearchXMLs(){
        $sampleXMLContent = 'someXMLContent';
        $sampleXMLFilename = 'word/searchable.xml';
        $docXHandler = $this->createDocXHandlerTest();
        $docXHandler->setXMLFile($sampleXMLFilename,$sampleXMLContent);
        $expected = [$sampleXMLFilename => $sampleXMLContent];
        $this->assertEquals($expected,$docXHandler->getXMLFilesToBeSearched());
    }

}
