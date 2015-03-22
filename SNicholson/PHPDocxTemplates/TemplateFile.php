<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 21/03/2015
 * Time: 21:34
 */

namespace SNicholson\PHPDocxTemplates;


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
        if(!$this->validateFilename($filename)){
//            throw new
        }
        $this->filename = $filename;
    }

    private function validateFilename($filename){
        $re = "/^(?P<title>[^#$%&*|{}\\/@=+><!\\\\\\s\\-_~,;:\\[\\]\\(\\).'\"]{1,})\\.(?P<extensions>[a-z]{1,5})$/";
        $str = "valid.docx\n";

        preg_match($re, $filename, $matches);

        var_dump($matches);

        return true;
    }

}