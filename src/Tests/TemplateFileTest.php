<?php
use SNicholson\PHPDocxTemplates\TemplateFile;

/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 22/03/2015
 * Time: 11:07
 */

class TemplateFileTest extends PHPUnit_Framework_TestCase {

    public function testInvalidFilenamesThrowExceptions(){
        $templateFile = new TemplateFile();
        $invalidFileNames = [
            'invalid','invalid.',2
        ];
        foreach($invalidFileNames AS $filename){
            $this->setExpectedException('SNicholson\PHPDocxTemplates\Exceptions\InvalidFilenameException');
            $templateFile->setFilePath($filename);
        }
    }

    public function testDisallowedExtensionsThrowException(){
        $templateFile = new TemplateFile();
        $invalidFileTypes = [
            'invalid.doc','invalid.png','arandomfile.zip'
        ];
        foreach($invalidFileTypes AS $filename){
            $this->setExpectedException('SNicholson\PHPDocxTemplates\Exceptions\InvalidFilenameException');
            $templateFile->setFilePath($filename);
        }
    }

    public function testValidFileNameIsSet(){
        $templateFile = new TemplateFile();
        $filename = 'validFile.docx';
        $templateFile->setFilePath($filename);
        $this->assertEquals($filename,$templateFile->getFilePath());
    }

}
