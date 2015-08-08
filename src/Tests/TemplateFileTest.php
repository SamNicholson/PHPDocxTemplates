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
            $this->setExpectedException('\InvalidArgumentException');
            $templateFile->setFilePath($filename);
        }
    }
}
