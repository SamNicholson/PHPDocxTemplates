<?php

namespace SNicholson\PHPDocxTemplates;

class PHPDocX
{

    /**
     * Performs a simple merge using the library
     *
     * @param                $inputFile
     * @param                $outputFile
     * @param RuleCollection $ruleCollection
     */
    static function merge($inputFile, $outputFile, RuleCollection $ruleCollection)
    {
        $template = new TemplateFile();
        $template->setFilename($inputFile);
        $merger = new Merger(new DocXHandler(new ZipArchive()));
        $merger->setTemplateFile($template);
        $merger->setRuleCollection($ruleCollection);
        $merger->merge();
        $merger->saveMergedDocument($outputFile);
    }

    static function ruleCollection()
    {

    }

}