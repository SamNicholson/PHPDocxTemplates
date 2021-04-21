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
                $mergedContent = $this->sanitizeSpecialCharacters($mergedContent);
            }
        }

        return $mergedContent;

    }

    private function sanitizeSpecialCharacters($mergedContent)
    {
        $mergedContent = str_replace('&Agrave;', 'À', $mergedContent);
        $mergedContent = str_replace('&Aacute;', 'Á', $mergedContent);
        $mergedContent = str_replace('&Acirc;', 'Â', $mergedContent);
        $mergedContent = str_replace('&Atilde;', 'Ã', $mergedContent);
        $mergedContent = str_replace('&Auml;', 'Ä', $mergedContent);
        $mergedContent = str_replace('&Aring;', 'Å', $mergedContent);
        $mergedContent = str_replace('&AElig;', 'Æ', $mergedContent);
        $mergedContent = str_replace('&Ccedil;', 'Ç', $mergedContent);
        $mergedContent = str_replace('&Egrave;', 'È', $mergedContent);
        $mergedContent = str_replace('&Eacute;', 'É', $mergedContent);
        $mergedContent = str_replace('&Ecirc;', 'Ê', $mergedContent);
        $mergedContent = str_replace('&Euml;', 'Ë', $mergedContent);
        $mergedContent = str_replace('&Igrave;', 'Ì', $mergedContent);
        $mergedContent = str_replace('&Iacute;', 'Í', $mergedContent);
        $mergedContent = str_replace('&Icirc;', 'Î', $mergedContent);
        $mergedContent = str_replace('&Iuml;', 'Ï', $mergedContent);
        $mergedContent = str_replace('&ETH;', 'Ð', $mergedContent);
        $mergedContent = str_replace('&Ntilde;', 'Ñ', $mergedContent);
        $mergedContent = str_replace('&Ograve;', 'Ò', $mergedContent);
        $mergedContent = str_replace('&Oacute;', 'Ó', $mergedContent);
        $mergedContent = str_replace('&Ocirc;', 'Ô', $mergedContent);
        $mergedContent = str_replace('&Otilde;', 'Õ', $mergedContent);
        $mergedContent = str_replace('&Ouml;', 'Ö', $mergedContent);
        $mergedContent = str_replace('&Oslash;', 'Ø', $mergedContent);
        $mergedContent = str_replace('&Ugrave;', 'Ù', $mergedContent);
        $mergedContent = str_replace('&Uacute;', 'Ú', $mergedContent);
        $mergedContent = str_replace('&Ucirc;', 'Û', $mergedContent);
        $mergedContent = str_replace('&Uuml;', 'Ü', $mergedContent);
        $mergedContent = str_replace('&Yacute;', 'Ý', $mergedContent);
        $mergedContent = str_replace('&THORN;', 'Þ', $mergedContent);
        $mergedContent = str_replace('&szlig;', 'ß', $mergedContent);
// Lowercase
        $mergedContent = str_replace('&agrave;', 'à', $mergedContent);
        $mergedContent = str_replace('&aacute;', 'á', $mergedContent);
        $mergedContent = str_replace('&acirc;', 'â', $mergedContent);
        $mergedContent = str_replace('&atilde;', 'ã', $mergedContent);
        $mergedContent = str_replace('&auml;', 'ä', $mergedContent);
        $mergedContent = str_replace('&aring;', 'å', $mergedContent);
        $mergedContent = str_replace('&aelig;', 'æ', $mergedContent);
        $mergedContent = str_replace('&ccedil;', 'ç', $mergedContent);
        $mergedContent = str_replace('&egrave;', 'è', $mergedContent);
        $mergedContent = str_replace('&eacute;', 'é', $mergedContent);
        $mergedContent = str_replace('&ecirc;', 'ê', $mergedContent);
        $mergedContent = str_replace('&euml;', 'ë', $mergedContent);
        $mergedContent = str_replace('&igrave;', 'ì', $mergedContent);
        $mergedContent = str_replace('&iacute;', 'í', $mergedContent);
        $mergedContent = str_replace('&icirc;', 'î', $mergedContent);
        $mergedContent = str_replace('&iuml;', 'ï', $mergedContent);
        $mergedContent = str_replace('&eth;', 'ð', $mergedContent);
        $mergedContent = str_replace('&ntilde;', 'ñ', $mergedContent);
        $mergedContent = str_replace('&ograve;', 'ò', $mergedContent);
        $mergedContent = str_replace('&oacute;', 'ó', $mergedContent);
        $mergedContent = str_replace('&ocirc;', 'ô', $mergedContent);
        $mergedContent = str_replace('&otilde;', 'õ', $mergedContent);
        $mergedContent = str_replace('&ouml;', 'ö', $mergedContent);
        $mergedContent = str_replace('&oslash;', 'ø', $mergedContent);
        $mergedContent = str_replace('&ugrave;', 'ù', $mergedContent);
        $mergedContent = str_replace('&uacute;', 'ú', $mergedContent);
        $mergedContent = str_replace('&ucirc;', 'û', $mergedContent);
        $mergedContent = str_replace('&uuml;', 'ü', $mergedContent);
        $mergedContent = str_replace('&yacute;', 'ý', $mergedContent);
// Additional language characters
        $mergedContent = str_replace('&thorn;', 'þ', $mergedContent);
        $mergedContent = str_replace('&yuml;', 'ÿ', $mergedContent);
        $mergedContent = str_replace('&beta;', 'β', $mergedContent);
// Punctuation
//$mergedContent = str_replace('&amp;', '&', $mergedContent);
        $mergedContent = str_replace('&excl;', '!', $mergedContent);
        $mergedContent = str_replace('&num;', '#', $mergedContent);
        $mergedContent = str_replace('&percnt;', '%', $mergedContent);
        $mergedContent = str_replace('&lpar;', '(', $mergedContent);
        $mergedContent = str_replace('&rpar;', ')', $mergedContent);
        $mergedContent = str_replace('&ast;', '*', $mergedContent);
        $mergedContent = str_replace('&comma;', ',', $mergedContent);
        $mergedContent = str_replace('&period;', '.', $mergedContent);
        $mergedContent = str_replace('&sol;', '/', $mergedContent);
        $mergedContent = str_replace('&bsol;', '\\', $mergedContent);
        $mergedContent = str_replace('&colon;', ':', $mergedContent);
        $mergedContent = str_replace('&semi;', ';', $mergedContent);
        $mergedContent = str_replace('&quest;', '?', $mergedContent);
        $mergedContent = str_replace('&commat;', '@', $mergedContent);
        $mergedContent = str_replace('&lbrack;', '[', $mergedContent);
        $mergedContent = str_replace('&rbrack;', ']', $mergedContent);
        $mergedContent = str_replace('&Hat;', '^', $mergedContent);
        $mergedContent = str_replace('&lowbar;', '_', $mergedContent);
        $mergedContent = str_replace('&grave;', '`', $mergedContent);
        $mergedContent = str_replace('&lbrace;', '{', $mergedContent);
        $mergedContent = str_replace('&rbrace;', '}', $mergedContent);
        $mergedContent = str_replace('&vert;', '|', $mergedContent);
// Symbols
        $mergedContent = str_replace('&iexcl;', '¡', $mergedContent);
        $mergedContent = str_replace('&brvbar;', '¦', $mergedContent);
        $mergedContent = str_replace('&sect;', '§', $mergedContent);
        $mergedContent = str_replace('&uml;', '¨', $mergedContent);
        $mergedContent = str_replace('&copy;', '©', $mergedContent);
        $mergedContent = str_replace('&ordf;', 'ª', $mergedContent);
        $mergedContent = str_replace('&laquo;', '«', $mergedContent);
        $mergedContent = str_replace('&not;', '¬', $mergedContent);
        $mergedContent = str_replace('&reg;', '®', $mergedContent);
        $mergedContent = str_replace('&macr;', '¯', $mergedContent);
        $mergedContent = str_replace('&deg;', '°', $mergedContent);
        $mergedContent = str_replace('&plusmn;', '±', $mergedContent);
        $mergedContent = str_replace('&sup2;', '²', $mergedContent);
        $mergedContent = str_replace('&sup3;', '³', $mergedContent);
        $mergedContent = str_replace('&acute;', '´', $mergedContent);
        $mergedContent = str_replace('&micro;', 'µ', $mergedContent);
        $mergedContent = str_replace('&para;', '¶', $mergedContent);
        $mergedContent = str_replace('&cedil;', '¸', $mergedContent);
        $mergedContent = str_replace('&sup1;', '¹', $mergedContent);
        $mergedContent = str_replace('&ordm;', 'º', $mergedContent);
        $mergedContent = str_replace('&raquo;', '»', $mergedContent);
        $mergedContent = str_replace('&frac14;', '¼', $mergedContent);
        $mergedContent = str_replace('&frac12;', '½', $mergedContent);
        $mergedContent = str_replace('&frac34;', '¾', $mergedContent);
        $mergedContent = str_replace('&iquest;', '¿', $mergedContent);
        $mergedContent = str_replace('&times;', '×', $mergedContent);
        $mergedContent = str_replace('&divide;', '÷', $mergedContent);
        $mergedContent = str_replace('&OElig;', 'Œ', $mergedContent);
        $mergedContent = str_replace('&oelig;', 'œ', $mergedContent);
        $mergedContent = str_replace('&Scaron;', 'Š', $mergedContent);
        $mergedContent = str_replace('&scaron;', 'š', $mergedContent);
        $mergedContent = str_replace('&Yuml;', 'Ÿ', $mergedContent);
        $mergedContent = str_replace('&fnof;', 'ƒ', $mergedContent);
        $mergedContent = str_replace('&circ;', 'ˆ', $mergedContent);
        $mergedContent = str_replace('&tilde;', '˜', $mergedContent);
        $mergedContent = str_replace('&ndash;', '–', $mergedContent);
        $mergedContent = str_replace('&mdash;', '—', $mergedContent);
        $mergedContent = str_replace('&dagger;', '†', $mergedContent);
        $mergedContent = str_replace('&Dagger;', '‡', $mergedContent);
        $mergedContent = str_replace('&bull;', '•', $mergedContent);
        $mergedContent = str_replace('&hellip;', '…', $mergedContent);
        $mergedContent = str_replace('&permil;', '‰', $mergedContent);
        $mergedContent = str_replace('&prime;', '′', $mergedContent);
        $mergedContent = str_replace('&Prime;', '″', $mergedContent);
        $mergedContent = str_replace('&lsaquo;', '‹', $mergedContent);
        $mergedContent = str_replace('&rsaquo;', '›', $mergedContent);
        $mergedContent = str_replace('&oline;', '‾', $mergedContent);
        $mergedContent = str_replace('&trade;', '™', $mergedContent);
// Quotes
        $mergedContent = str_replace('&lsquo;', '‘', $mergedContent);
        $mergedContent = str_replace('&rsquo;', '’', $mergedContent);
        $mergedContent = str_replace('&sbquo;', '‚', $mergedContent);
        $mergedContent = str_replace('&ldquo;', '“', $mergedContent);
        $mergedContent = str_replace('&rdquo;', '”', $mergedContent);
        $mergedContent = str_replace('&bdquo;', '„', $mergedContent);
// Currency
        $mergedContent = str_replace('&cent;', '¢', $mergedContent);
        $mergedContent = str_replace('&pound;', '£', $mergedContent);
        $mergedContent = str_replace('&euro;', '€', $mergedContent);
        $mergedContent = str_replace('&dollar;', '$', $mergedContent);
        $mergedContent = str_replace('&yen;', '¥', $mergedContent);
        $mergedContent = str_replace('&curren;', '¤', $mergedContent);
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