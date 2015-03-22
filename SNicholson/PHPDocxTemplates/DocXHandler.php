<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 21/03/2015
 * Time: 05:48
 */

namespace SNicholson\PHPDocxTemplates;

use ZipArchive;
use SNicholson\PHPDocxTemplates\Interfaces\ZipHandlerInterface;

class DocXHandler implements ZipHandlerInterface {

    private $zipRead;
    private $zipWrite;
    private $templateFile;
    private $XMLFiles;

    public function __construct(TemplateFile $templateFile, ZipArchive $zipArchive){
        $this->zipRead = $zipArchive;
        $this->zipWrite = $zipArchive;
        $this->templateFile = $templateFile;
    }

    public function read(){
        $this->zipRead->open($this->templateFile->getFilename());
        for($i = 0; $i < $this->zipRead->numFiles; $i++) {
            $filename = $this->zipRead->getNameIndex($i);
            $fp = $this->zipRead->getStream($filename);
            $contents = '';
            while (!feof($fp)) {
                $contents .= fread($fp, 8192);
            }
            $this->XMLFiles[$filename] = $contents;
            fclose($fp);
        }
        $this->zipRead->close();
    }

    public function saveAs($fileName){
        $this->zipWrite->open($fileName, ZipArchive::CREATE);
        foreach($this->XMLFiles AS $filename => $contents){
            $this->zipWrite->addFromString($filename,$contents);
        }
        $this->zipWrite->close();
    }

    public function overwriteTemplate(){
        foreach($this->XMLFiles AS $filename => $contents){
            $this->zipRead->addFromString($filename,$contents);
        }
        $this->zipRead->close();
    }

    public function getXMLFile($XMLFile){
        if(empty($this->XMLFiles[$XMLFile])){
            throw new \InvalidArgumentException('XML File Specified Does not exist');
        }
        return $this->XMLFiles[$XMLFile];
    }

    public function setXMLFile($XMLFile,$content){
        $this->XMLFiles[$XMLFile] = $content;
    }

    public function test($wordFile){
        //        $this->zipWrite->open('temp/zip/newDocument.docx', ZipArchive::CREATE);
    }

}