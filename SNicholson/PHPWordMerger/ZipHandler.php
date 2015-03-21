<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 21/03/2015
 * Time: 05:48
 */

namespace SNicholson\PHPWordMerger;

use ZipArchive;

class ZipHandler {

    public function __construct(){
        $this->zipRead = new ZipArchive();
        $this->zipWrite = new ZipArchive();
        $this->zipWrite->open('temp/zip/newDocument.docx', ZipArchive::CREATE);
    }

    public function test($wordFile){
        $this->zipRead->open($wordFile);
        for($i = 0; $i < $this->zipRead->numFiles; $i++)
        {
            $filename = $this->zipRead->getNameIndex($i);
            $fp = $this->zipRead->getStream($filename);
            $contents = '';
            while (!feof($fp)) {
                $contents .= fread($fp, 8192);
            }
            $contents = str_replace('#error#',' This was a mergecode with an error in it! ',$contents);
            $contents = str_replace('#ordidi#',' OH YES I DID !!!!! ',$contents);
            $contents = str_replace('#I copied the whole word document without editing it#',' Long ones work too!!!!!! ',$contents);
            $contents = str_replace('#testme#',' Long ones work too!!!!!! ',$contents);
            $this->zipWrite->addFromString($filename,$contents);
                echo('found document!!');
                $this->zipRead->deleteIndex($i);
            fclose($fp);
        }
        $this->zipRead->close();
        $this->zipWrite->close();
        echo('run');
    }

}