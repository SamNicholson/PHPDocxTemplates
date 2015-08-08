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
class TemplateFile implements TemplateFileInterface
{

    /**
     * The filePath of the Template
     * @var
     */
    private $filePath;

    /**
     * Get the filePath
     * @return mixed
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * Set the filePath
     *
     * @param mixed $filename
     */
    public function setFilePath($filename)
    {
        if ($this->validateFilename($filename)) {
            $this->filePath = $filename;
        }
    }

    /**
     * Validates whether a filePath meets our requirements - format and structure and existence!
     *
     * @param $filePath
     *
     * @return bool
     * @throws InvalidFilenameException
     */
    private function validateFilename($filePath)
    {
        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException("File Path specified did not exist");
        }
        return true;
    }

}