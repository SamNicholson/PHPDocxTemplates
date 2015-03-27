<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 26/03/2015
 * Time: 23:49
 */

namespace SNicholson\PHPDocxTemplates;


class ZipArchive extends \ZipArchive{

    public function getNumFiles(){
        return $this->numFiles;
    }

    public function getFileContents($filename){
        $fp = $this->getStream($filename);
        $contents = '';
        while (!feof($fp)) {
            $contents .= fread($fp, 8192);
        }
        fclose($fp);
        return $contents;
    }

}