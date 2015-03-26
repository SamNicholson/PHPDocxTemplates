<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 24/03/2015
 * Time: 00:12
 */

namespace SNicholson\PHPDocxTemplates;


use SNicholson\PHPDocxTemplates\TemplateFile;
use ZipArchive;

class SimpleMerge {

    static function perform($inputFile, $outputFile, RuleCollection $ruleCollection){
        $template = new TemplateFile();
        $template->setFilename($inputFile);
        $merger = new Merger(new DocXHandler(new ZipArchive()));
        $merger->setTemplateFile($template);
        $merger->setRuleCollection($ruleCollection);
        $merger->merge();
        $merger->saveMergedDocument($outputFile);
    }

}