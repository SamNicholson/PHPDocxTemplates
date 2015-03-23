<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 21/03/2015
 * Time: 21:36
 */

namespace SNicholson\PHPDocxTemplates;


class Merger {

    private $ruleCollection;
    private $templateFile;

    /**
     * @return mixed
     */
    public function getRuleCollection() {
        return $this->ruleCollection;
    }

    /**
     * @param mixed $ruleCollection
     */
    public function setRuleCollection($ruleCollection) {
        $this->ruleCollection = $ruleCollection;
    }

    /**
     * @return mixed
     */
    public function getTemplateFile() {
        return $this->templateFile;
    }

    /**
     * @param mixed $templateFile
     */
    public function setTemplateFile($templateFile) {
        $this->templateFile = $templateFile;
    }

    public function merge(){

    }

    public function getMergedDocument($mergedFilename){

    }


}