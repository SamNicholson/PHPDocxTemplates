<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 08/08/2015
 * Time: 10:28
 */

namespace SNicholson\PHPDocxTemplates;


class TestHelper
{
    /**
     * This method compares the XML file contents of 2 docX files to check whether a merge has worked on them!
     *
     * @param $referenceDocXFilePath
     * @param $producedDocXFilePath
     *
     * @return bool
     * @throws \SNicholson\PHPDocxTemplates\Exceptions\InvalidFilenameException
     */
    public static function compare2DocXFiles($referenceDocXFilePath, $producedDocXFilePath)
    {
        $refTempFile = new TemplateFile();
        $refTempFile->setFilePath($referenceDocXFilePath);
        $refDocX = new DocXHandler(new ZipArchive());
        $refDocX->setTemplateFile($refTempFile);
        $refDocX->read();

        $prodDocXFile = new TemplateFile();
        $prodDocXFile->setFilePath($producedDocXFilePath);
        $prodDocX = new DocXHandler(new ZipArchive());
        $prodDocX->setTemplateFile($prodDocXFile);
        $prodDocX->read();

        return $refDocX->getXMLFilesToBeSearched() == $prodDocX->getXMLFilesToBeSearched();
    }

}