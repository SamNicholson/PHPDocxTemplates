<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 21/03/2015
 * Time: 21:34
 */

namespace SNicholson\PHPDocxTemplates;


use SNicholson\PHPDocxTemplates\Exceptions\InvalidFilenameException;
use SNicholson\PHPDocxTemplates\Interfaces\TemplateFileInterface;

class TemplateFile implements TemplateFileInterface {

    private $filename;
    private $supportedExtensions = [
        'docx'
    ];

    /**
     * @return mixed
     */
    public function getFilename() {
        return $this->filename;
    }

    /**
     * @param mixed $filename
     */
    public function setFilename($filename) {
        if($this->validateFilename($filename)) {
            $this->filename = $filename;
        }
    }

    private function validateFilename($filename){
        $re = "/^(?P<title>[^#$%&*|{}\\/@=+><!\\\\\\s\\-_~,;:\\[\\]\\(\\).'\"]{1,})\\.(?P<extension>[a-z]{1,5})$/";

        preg_match($re, $filename, $match);

        if(empty($match)){
            throw new InvalidFilenameException("Invalid filename provided - $filename");
        }

        if(!in_array($match['extension'],$this->supportedExtensions)){
            throw new InvalidFilenameException("Document provided is of an unsupported extension");
        }

        return true;
    }

}