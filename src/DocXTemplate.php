<?php

namespace SNicholson\PHPDocxTemplates;

class DocXTemplate
{

    private static $enableHTMLFormatting = false;

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
        $template->setFilePath($inputFile);
        $merger = new Merger(new DocXHandler(new ZipArchive()));
        $merger->setTemplateFile($template);
        $merger->setRuleCollection($ruleCollection);
        $merger->merge();
        $merger->saveMergedDocument($outputFile);
    }

    /**
     * Returns a new instance of RuleCollection
     *
     * @return RuleCollection
     */
    static function ruleCollection()
    {
        return new RuleCollection();
    }

    /**
     * @return bool
     */
    public static function isHTMLFormattingEnabled()
    {
        return self::$enableHTMLFormatting;
    }

    /**
     *
     */
    public static function enableHTMLFormatting()
    {
        self::$enableHTMLFormatting = true;
    }
}