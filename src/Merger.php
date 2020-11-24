<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 21/03/2015
 * Time: 21:36
 */

namespace SNicholson\PHPDocxTemplates;


use Closure;
use SNicholson\PHPDocxTemplates\Exceptions\MergeException;
use SNicholson\PHPDocxTemplates\Rules\SimpleRule;

/**
 * Class Merger
 * @package SNicholson\PHPDocxTemplates
 */
class Merger {

    /** @var  RuleCollection $ruleCollection*/
    private $ruleCollection;
    /** @var  TemplateFile $templateFile */
    private $templateFile;
    /** @var  DocXHandler $docXHandler */
    private $docXHandler;
    /**
     * @var bool
     */
    private $merged = false;

    /**
     * @param DocXHandler $docXHandler
     */
    public function __construct(DocXHandler $docXHandler){
        $this->docXHandler = $docXHandler;
    }

    /**
     * @return mixed
     */
    public function getRuleCollection() {
        return $this->ruleCollection;
    }

    /**
     * @param mixed $ruleCollection
     */
    public function setRuleCollection(RuleCollection $ruleCollection) {
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
    public function setTemplateFile(TemplateFile $templateFile) {
        $this->templateFile = $templateFile;
    }

    /**
     * Merges the rules into the TemplateFile in question
     */
    public function merge(){
        //Read the assigned template
        $this->docXHandler->setTemplateFile($this->templateFile);
        $this->docXHandler->read();

        //Iterate over each of the XML Files to be searched for replacing
        foreach($this->docXHandler->getXMLFilesToBeSearched() AS $filename => $content){
            /**
             * Iterate over each rule and action depending on the type
             * @var  SimpleRule $rule
             */
            foreach($this->ruleCollection->getRules() AS $rule){
                $re = "/(?P<class>[a-zA-Z]{1,})$/";
                preg_match($re, get_class($rule), $matches);
                switch($matches['class']){
                    case 'SimpleRule':
                        $content = $this->mergeSimpleRule($content,$rule->getData(),$rule->getTarget());
                        break;
                    case 'RegexpRule':
                        $content = $this->mergeRegexpRule($content,$rule->getData(),$rule->getTarget());
                        break;
                }
                $this->docXHandler->setXMLFile($filename,$content);
            }
        }
    }

    /**
     * Handles merging a regexp rule
     * @param $content
     * @param $data
     * @param $target
     *
     * @return mixed
     */
    public function mergeRegexpRule($content,$data,$target){
        $content = preg_replace_callback(
            $target,$data,$content
        );
        return $content;
    }

    /**
     * Handles merging simple rules
     * @param $content
     * @param $data
     * @param $target
     *
     * @return mixed
     */
    private function mergeSimpleRule($content,$data,$target){
        //If this is a closure gets it value
        if(is_object($data) && ($data instanceof Closure)){
            $data = $data();
        }
        //Run a simple string replace
        $mergedContent = str_replace($target,htmlentities($data),$content);

        if (DocXTemplate::isHTMLFormattingEnabled()) {
            foreach (DXF::getAsArrayWithReplacements() as $key => $value)
            {
                $mergedContent = str_replace(htmlentities($key), $value, $mergedContent);
            }
        }

        return $mergedContent;

    }

    /**
     * @param $mergedFilename
     */
    public function saveMergedDocument($mergedFilename){
        if(!$this->merged){
            $this->merge();
        }
        $this->docXHandler->saveAs($mergedFilename);
    }


}