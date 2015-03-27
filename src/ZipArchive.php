<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 26/03/2015
 * Time: 23:49
 */

namespace SNicholson\PHPDocxTemplates;


class ZipArchive extends \ZipArchive{

    public function getNumFile(){
        return $this->numFiles;
    }

}