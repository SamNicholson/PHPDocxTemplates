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

/**
 * Class TemplateFile
 * @package SNicholson\PHPDocxTemplates
 */
class TemplateFile implements TemplateFileInterface {

    /**
     * The filename of the Template
     * @var
     */
    private $filename;
    /**
     * The extends that the merging library supports
     * @var array
     */
    private $supportedExtensions = [
        'docx'
    ];

    /**
     * Get the filename
     * @return mixed
     */
    public function getFilename() {
        return $this->filename;
    }

    /**
     * Set the filename
     * @param mixed $filename
     */
    public function setFilename($filename) {
        if($this->validateFilename($filename)) {
            $this->filename = $filename;
        }
    }

    /**
     * Validates whether a filename meets our requirements - format and structure
     * @param $filename
     *
     * @return bool
     * @throws InvalidFilenameException
     */
    private function validateFilename($filename){
        $extension = explode('.', $filename)[count(explode('.', $filename)) -1];
        if(!in_array($extension,$this->supportedExtensions)){
            throw new InvalidFilenameException("Document provided is of an unsupported extension");
        }

        return true;
    }

}