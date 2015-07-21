<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 21/03/2015
 * Time: 05:48
 */

namespace SNicholson\PHPDocxTemplates;

use SNicholson\PHPDocxTemplates\Exceptions\InvalidFilenameException;
use SNicholson\PHPDocxTemplates\Interfaces\ZipHandlerInterface;
use SNicholson\PHPDocxTemplates\ZipArchive;

/**
 * Class DocXHandler
 * @package SNicholson\PHPDocxTemplates
 */
class DocXHandler implements ZipHandlerInterface {

    /**
     * The zip archive used internally to read .docx file
     * @var ZipArchive
     */
    private $zipRead;
    /**
     * The zip archive used to write the new .docx
     * @var ZipArchive
     */
    private $zipWrite;
    /**
     * The template file used by this class
     * @var  TemplateFile $templateFile
     */
    private $templateFile;
    /**
     * The XML files which are inside of a .docx file
     * @var Array
     */
    private $XMLFiles;

    /**
     * @param ZipArchive $zipArchive
     */
    public function __construct(ZipArchive $zipArchive){
        $this->zipRead = $zipArchive;
        $this->zipWrite = $zipArchive;
    }

    /**
     * Sets the template file into the object
     * @param TemplateFile $templateFile
     */
    public function setTemplateFile(TemplateFile $templateFile){
        $this->templateFile = $templateFile;
    }

    /**
     * Reads the template file that has been set
     */
    public function read(){
        if ($errNo = $this->zipRead->open($this->templateFile->getFilename())  !== true) {
            throw new InvalidFilenameException("Failed to open ZIP file " . $this->templateFile->getFilename() .
                                               ", ZIP Archive gave error code: " . $errNo);
        }
        $fileCount = $this->zipRead->getNumFiles();
        for($i = 0; $i < $fileCount; $i++) {
            $filename = $this->zipRead->getNameIndex($i);
            $contents = $this->zipRead->getFileContents($filename);
            $this->XMLFiles[$filename] = $contents;
        }
        $this->zipRead->close();
    }

    /**
     * Saves the new .docx file with all the merged data in it
     * @param $fileName
     */
    public function saveAs($fileName){
        $this->zipWrite->open($fileName, ZipArchive::CREATE);
        foreach($this->XMLFiles AS $filename => $contents){
            $this->zipWrite->addFromString($filename,$contents);
        }
        $this->zipWrite->close();
    }

    /**
     * This function overwrites the original template
     */
    public function overwriteTemplate(){
        foreach($this->XMLFiles AS $filename => $contents){
            $this->zipRead->addFromString($filename,$contents);
        }
        $this->zipRead->close();
    }

    /**
     * This function gets a specific XML file from the .docx
     * @param $XMLFile
     *
     * @return mixed
     */
    public function getXMLFile($XMLFile){
        if(empty($this->XMLFiles[$XMLFile])){
            throw new \InvalidArgumentException('XML File Specified Does not exist');
        }
        return $this->XMLFiles[$XMLFile];
    }

    /**
     * This function returns the XML files which are to be searched and replaced for simple/regexp rules
     * @return array
     */
    public function getXMLFilesToBeSearched(){
        $XMLReturns = [];
        foreach($this->XMLFiles AS $filename => $contents){
            if(stristr($filename,'word/')){
                $XMLReturns[$filename] = $contents;
            }
        }
        return $XMLReturns;
    }

    /**
     * This function sets an XML file back into the handler
     * @param $XMLFile
     * @param $content
     */
    public function setXMLFile($XMLFile,$content){
        $this->XMLFiles[$XMLFile] = $content;
    }

}