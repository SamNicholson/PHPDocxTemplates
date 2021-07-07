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
// Accented Latin Characters
        $mergedContent = str_replace('&Aacute;', 'Á', $mergedContent);
        $mergedContent = str_replace('&aacute;', 'á', $mergedContent);
        $mergedContent = str_replace('&Abreve;', 'Ă', $mergedContent);
        $mergedContent = str_replace('&abreve;', 'ă', $mergedContent);
        $mergedContent = str_replace('&Acirc;', 'Â', $mergedContent);
        $mergedContent = str_replace('&acirc;', 'â', $mergedContent);
        $mergedContent = str_replace('&AElig;', 'Æ', $mergedContent);
        $mergedContent = str_replace('&aelig;', 'æ', $mergedContent);
        $mergedContent = str_replace('&Agrave;', 'À', $mergedContent);
        $mergedContent = str_replace('&agrave;', 'à', $mergedContent);
        $mergedContent = str_replace('&Amacr;', 'Ā', $mergedContent);
        $mergedContent = str_replace('&amacr;', 'ā', $mergedContent);
        $mergedContent = str_replace('&Aogon;', 'Ą', $mergedContent);
        $mergedContent = str_replace('&aogon;', 'ą', $mergedContent);
        $mergedContent = str_replace('&Aring;', 'Å', $mergedContent);
        $mergedContent = str_replace('&aring;', 'å', $mergedContent);
        $mergedContent = str_replace('&Atilde;', 'Ã', $mergedContent);
        $mergedContent = str_replace('&atilde;', 'ã', $mergedContent);
        $mergedContent = str_replace('&Auml;', 'Ä', $mergedContent);
        $mergedContent = str_replace('&auml;', 'ä', $mergedContent);
        $mergedContent = str_replace('&Cacute;', 'Ć', $mergedContent);
        $mergedContent = str_replace('&cacute;', 'ć', $mergedContent);
        $mergedContent = str_replace('&Ccaron;', 'Č', $mergedContent);
        $mergedContent = str_replace('&ccaron;', 'č', $mergedContent);
        $mergedContent = str_replace('&Ccedil;', 'Ç', $mergedContent);
        $mergedContent = str_replace('&ccedil;', 'ç', $mergedContent);
        $mergedContent = str_replace('&Ccirc;', 'Ĉ', $mergedContent);
        $mergedContent = str_replace('&ccirc;', 'ĉ', $mergedContent);
        $mergedContent = str_replace('&Cdot;', 'Ċ', $mergedContent);
        $mergedContent = str_replace('&cdot;', 'ċ', $mergedContent);
        $mergedContent = str_replace('&Dcaron;', 'Ď', $mergedContent);
        $mergedContent = str_replace('&dcaron;', 'ď', $mergedContent);
        $mergedContent = str_replace('&DownBreve;', '̑', $mergedContent);
        $mergedContent = str_replace('&Dstrok;', 'Đ', $mergedContent);
        $mergedContent = str_replace('&dstrok;', 'đ', $mergedContent);
        $mergedContent = str_replace('&Eacute;', 'É', $mergedContent);
        $mergedContent = str_replace('&eacute;', 'é', $mergedContent);
        $mergedContent = str_replace('&Ecaron;', 'Ě', $mergedContent);
        $mergedContent = str_replace('&ecaron;', 'ě', $mergedContent);
        $mergedContent = str_replace('&Ecirc;', 'Ê', $mergedContent);
        $mergedContent = str_replace('&ecirc;', 'ê', $mergedContent);
        $mergedContent = str_replace('&Edot;', 'Ė', $mergedContent);
        $mergedContent = str_replace('&edot;', 'ė', $mergedContent);
        $mergedContent = str_replace('&Egrave;', 'È', $mergedContent);
        $mergedContent = str_replace('&egrave;', 'è', $mergedContent);
        $mergedContent = str_replace('&Emacr;', 'Ē', $mergedContent);
        $mergedContent = str_replace('&emacr;', 'ē', $mergedContent);
        $mergedContent = str_replace('&ENG;', 'Ŋ', $mergedContent);
        $mergedContent = str_replace('&eng;', 'ŋ', $mergedContent);
        $mergedContent = str_replace('&Eogon;', 'Ę', $mergedContent);
        $mergedContent = str_replace('&eogon;', 'ę', $mergedContent);
        $mergedContent = str_replace('&ETH;', 'Ð', $mergedContent);
        $mergedContent = str_replace('&eth;', 'ð', $mergedContent);
        $mergedContent = str_replace('&Euml;', 'Ë', $mergedContent);
        $mergedContent = str_replace('&euml;', 'ë', $mergedContent);
        $mergedContent = str_replace('&Gbreve;', 'Ğ', $mergedContent);
        $mergedContent = str_replace('&gbreve;', 'ğ', $mergedContent);
        $mergedContent = str_replace('&Gcedil;', 'Ģ', $mergedContent);
        $mergedContent = str_replace('&Gcirc;', 'Ĝ', $mergedContent);
        $mergedContent = str_replace('&gcirc;', 'ĝ', $mergedContent);
        $mergedContent = str_replace('&Gdot;', 'Ġ', $mergedContent);
        $mergedContent = str_replace('&gdot;', 'ġ', $mergedContent);
        $mergedContent = str_replace('&Hcirc;', 'Ĥ', $mergedContent);
        $mergedContent = str_replace('&hcirc;', 'ĥ', $mergedContent);
        $mergedContent = str_replace('&Hstrok;', 'Ħ', $mergedContent);
        $mergedContent = str_replace('&hstrok;', 'ħ', $mergedContent);
        $mergedContent = str_replace('&Iacute;', 'Í', $mergedContent);
        $mergedContent = str_replace('&iacute;', 'í', $mergedContent);
        $mergedContent = str_replace('&icirc;', 'î', $mergedContent);
        $mergedContent = str_replace('&Icirc;', 'Î', $mergedContent);
        $mergedContent = str_replace('&Idot;', 'İ', $mergedContent);
        $mergedContent = str_replace('&igrave;', 'ì', $mergedContent);
        $mergedContent = str_replace('&Igrave;', 'Ì', $mergedContent);
        $mergedContent = str_replace('&IJlig;', 'Ĳ', $mergedContent);
        $mergedContent = str_replace('&ijlig;', 'ĳ', $mergedContent);
        $mergedContent = str_replace('&Imacr;', 'Ī', $mergedContent);
        $mergedContent = str_replace('&imacr;', 'ī', $mergedContent);
        $mergedContent = str_replace('&imath;', 'ı', $mergedContent);
        $mergedContent = str_replace('&Iogon;', 'Į', $mergedContent);
        $mergedContent = str_replace('&iogon;', 'į', $mergedContent);
        $mergedContent = str_replace('&Itilde;', 'Ĩ', $mergedContent);
        $mergedContent = str_replace('&itilde;', 'ĩ', $mergedContent);
        $mergedContent = str_replace('&iuml;', 'ï', $mergedContent);
        $mergedContent = str_replace('&Iuml;', 'Ï', $mergedContent);
        $mergedContent = str_replace('&Jcirc;', 'Ĵ', $mergedContent);
        $mergedContent = str_replace('&jcirc;', 'ĵ', $mergedContent);
        $mergedContent = str_replace('&Kcedil;', 'Ķ', $mergedContent);
        $mergedContent = str_replace('&kcedil;', 'ķ', $mergedContent);
        $mergedContent = str_replace('&kgreen;', 'ĸ', $mergedContent);
        $mergedContent = str_replace('&lacute;', 'ĺ', $mergedContent);
        $mergedContent = str_replace('&Lacute;', 'Ĺ', $mergedContent);
        $mergedContent = str_replace('&lcaron;', 'ľ', $mergedContent);
        $mergedContent = str_replace('&Lcaron;', 'Ľ', $mergedContent);
        $mergedContent = str_replace('&lcedil;', 'ļ', $mergedContent);
        $mergedContent = str_replace('&Lcedil;', 'Ļ', $mergedContent);
        $mergedContent = str_replace('&Lmidot;', 'Ŀ', $mergedContent);
        $mergedContent = str_replace('&lmidot;', 'ŀ', $mergedContent);
        $mergedContent = str_replace('&Lstrok;', 'Ł', $mergedContent);
        $mergedContent = str_replace('&lstrok;', 'ł', $mergedContent);
        $mergedContent = str_replace('&Nacute;', 'Ń', $mergedContent);
        $mergedContent = str_replace('&nacute;', 'ń', $mergedContent);
        $mergedContent = str_replace('&napos;', 'ŉ', $mergedContent);
        $mergedContent = str_replace('&Ncaron;', 'Ň', $mergedContent);
        $mergedContent = str_replace('&ncaron;', 'ň', $mergedContent);
        $mergedContent = str_replace('&Ncedil;', 'Ņ', $mergedContent);
        $mergedContent = str_replace('&ncedil;', 'ņ', $mergedContent);
        $mergedContent = str_replace('&Ntilde;', 'Ñ', $mergedContent);
        $mergedContent = str_replace('&ntilde;', 'ñ', $mergedContent);
        $mergedContent = str_replace('&Oacute;', 'Ó', $mergedContent);
        $mergedContent = str_replace('&oacute;', 'ó', $mergedContent);
        $mergedContent = str_replace('&Ocirc;', 'Ô', $mergedContent);
        $mergedContent = str_replace('&ocirc;', 'ô', $mergedContent);
        $mergedContent = str_replace('&Odblac;', 'Ő', $mergedContent);
        $mergedContent = str_replace('&odblac;', 'ő', $mergedContent);
        $mergedContent = str_replace('&OElig;', 'Œ', $mergedContent);
        $mergedContent = str_replace('&oelig;', 'œ', $mergedContent);
        $mergedContent = str_replace('&Ograve;', 'Ò', $mergedContent);
        $mergedContent = str_replace('&ograve;', 'ò', $mergedContent);
        $mergedContent = str_replace('&Omacr;', 'Ō', $mergedContent);
        $mergedContent = str_replace('&omacr;', 'ō', $mergedContent);
        $mergedContent = str_replace('&Oslash;', 'Ø', $mergedContent);
        $mergedContent = str_replace('&oslash;', 'ø', $mergedContent);
        $mergedContent = str_replace('&Otilde;', 'Õ', $mergedContent);
        $mergedContent = str_replace('&otilde;', 'õ', $mergedContent);
        $mergedContent = str_replace('&Ouml;', 'Ö', $mergedContent);
        $mergedContent = str_replace('&ouml;', 'ö', $mergedContent);
        $mergedContent = str_replace('&Racute;', 'Ŕ', $mergedContent);
        $mergedContent = str_replace('&racute;', 'ŕ', $mergedContent);
        $mergedContent = str_replace('&Rcaron;', 'Ř', $mergedContent);
        $mergedContent = str_replace('&rcaron;', 'ř', $mergedContent);
        $mergedContent = str_replace('&Rcedil;', 'Ŗ', $mergedContent);
        $mergedContent = str_replace('&rcedil;', 'ŗ', $mergedContent);
        $mergedContent = str_replace('&Sacute;', 'Ś', $mergedContent);
        $mergedContent = str_replace('&sacute;', 'ś', $mergedContent);
        $mergedContent = str_replace('&Scaron;', 'Š', $mergedContent);
        $mergedContent = str_replace('&scaron;', 'š', $mergedContent);
        $mergedContent = str_replace('&Scedil;', 'Ş', $mergedContent);
        $mergedContent = str_replace('&scedil;', 'ş', $mergedContent);
        $mergedContent = str_replace('&Scirc;', 'Ŝ', $mergedContent);
        $mergedContent = str_replace('&scirc;', 'ŝ', $mergedContent);
        $mergedContent = str_replace('&szlig;', 'ß', $mergedContent);
        $mergedContent = str_replace('&Tcaron;', 'Ť', $mergedContent);
        $mergedContent = str_replace('&tcaron;', 'ť', $mergedContent);
        $mergedContent = str_replace('&Tcedil;', 'Ţ', $mergedContent);
        $mergedContent = str_replace('&tcedil;', 'ţ', $mergedContent);
        $mergedContent = str_replace('&THORN;', 'Þ', $mergedContent);
        $mergedContent = str_replace('&thorn;', 'þ', $mergedContent);
        $mergedContent = str_replace('&Tstrok;', 'Ŧ', $mergedContent);
        $mergedContent = str_replace('&tstrok;', 'ŧ', $mergedContent);
        $mergedContent = str_replace('&Uacute;', 'Ú', $mergedContent);
        $mergedContent = str_replace('&uacute;', 'ú', $mergedContent);
        $mergedContent = str_replace('&Ubreve;', 'Ŭ', $mergedContent);
        $mergedContent = str_replace('&ubreve;', 'ŭ', $mergedContent);
        $mergedContent = str_replace('&Ucirc;', 'Û', $mergedContent);
        $mergedContent = str_replace('&ucirc;', 'û', $mergedContent);
        $mergedContent = str_replace('&Udblac;', 'Ű', $mergedContent);
        $mergedContent = str_replace('&udblac;', 'ű', $mergedContent);
        $mergedContent = str_replace('&Ugrave;', 'Ù', $mergedContent);
        $mergedContent = str_replace('&ugrave;', 'ù', $mergedContent);
        $mergedContent = str_replace('&Umacr;', 'Ū', $mergedContent);
        $mergedContent = str_replace('&umacr;', 'ū', $mergedContent);
        $mergedContent = str_replace('&Uogon;', 'Ų', $mergedContent);
        $mergedContent = str_replace('&uogon;', 'ų', $mergedContent);
        $mergedContent = str_replace('&Uring;', 'Ů', $mergedContent);
        $mergedContent = str_replace('&uring;', 'ů', $mergedContent);
        $mergedContent = str_replace('&Utilde;', 'Ũ', $mergedContent);
        $mergedContent = str_replace('&utilde;', 'ũ', $mergedContent);
        $mergedContent = str_replace('&Uuml;', 'Ü', $mergedContent);
        $mergedContent = str_replace('&uuml;', 'ü', $mergedContent);
        $mergedContent = str_replace('&Wcirc;', 'Ŵ', $mergedContent);
        $mergedContent = str_replace('&wcirc;', 'ŵ', $mergedContent);
        $mergedContent = str_replace('&Yacute;', 'Ý', $mergedContent);
        $mergedContent = str_replace('&yacute;', 'ý', $mergedContent);
        $mergedContent = str_replace('&Ycirc;', 'Ŷ', $mergedContent);
        $mergedContent = str_replace('&ycirc;', 'ŷ', $mergedContent);
        $mergedContent = str_replace('&yuml;', 'ÿ', $mergedContent);
        $mergedContent = str_replace('&Yuml;', 'Ÿ', $mergedContent);
        $mergedContent = str_replace('&Zacute;', 'Ź', $mergedContent);
        $mergedContent = str_replace('&zacute;', 'ź', $mergedContent);
        $mergedContent = str_replace('&Zcaron;', 'Ž', $mergedContent);
        $mergedContent = str_replace('&zcaron;', 'ž', $mergedContent);
        $mergedContent = str_replace('&Zdot;', 'Ż', $mergedContent);
        $mergedContent = str_replace('&zdot;', 'ż', $mergedContent);
//Greek
        $mergedContent = str_replace('&Alpha;', 'Α', $mergedContent);
        $mergedContent = str_replace('&Beta;', 'Β', $mergedContent);
        $mergedContent = str_replace('&Chi;', 'Χ', $mergedContent);
        $mergedContent = str_replace('&Delta;', 'Δ', $mergedContent);
        $mergedContent = str_replace('&Epsilon;', 'Ε', $mergedContent);
        $mergedContent = str_replace('&Eta;', 'Η', $mergedContent);
        $mergedContent = str_replace('&Gamma;', 'Γ', $mergedContent);
        $mergedContent = str_replace('&Iota;', 'Ι', $mergedContent);
        $mergedContent = str_replace('&Kappa;', 'Κ', $mergedContent);
        $mergedContent = str_replace('&Lambda;', 'Λ', $mergedContent);
        $mergedContent = str_replace('&Mu;', 'Μ', $mergedContent);
        $mergedContent = str_replace('&Nu;', 'Ν', $mergedContent);
        $mergedContent = str_replace('&Omega;', 'Ω', $mergedContent);
        $mergedContent = str_replace('&Omicron;', 'Ο', $mergedContent);
        $mergedContent = str_replace('&Phi;', 'Φ', $mergedContent);
        $mergedContent = str_replace('&Pi;', 'Π', $mergedContent);
        $mergedContent = str_replace('&Psi;', 'Ψ', $mergedContent);
        $mergedContent = str_replace('&Rho;', 'Ρ', $mergedContent);
        $mergedContent = str_replace('&Sigma;', 'Σ', $mergedContent);
        $mergedContent = str_replace('&Tau;', 'Τ', $mergedContent);
        $mergedContent = str_replace('&Theta;', 'Θ', $mergedContent);
        $mergedContent = str_replace('&Upsilon;', 'Υ', $mergedContent);
        $mergedContent = str_replace('&Xi;', 'Ξ', $mergedContent);
        $mergedContent = str_replace('&Zeta;', 'Ζ', $mergedContent);
        $mergedContent = str_replace('&alpha;', 'α', $mergedContent);
        $mergedContent = str_replace('&beta;', 'β', $mergedContent);
        $mergedContent = str_replace('&chi;', 'χ', $mergedContent);
        $mergedContent = str_replace('&delta;', 'δ', $mergedContent);
        $mergedContent = str_replace('&epsilon;', 'ε', $mergedContent);
        $mergedContent = str_replace('&eta;', 'η', $mergedContent);
        $mergedContent = str_replace('&gamma;', 'γ', $mergedContent);
        $mergedContent = str_replace('&iota;', 'ι', $mergedContent);
        $mergedContent = str_replace('&kappa;', 'κ', $mergedContent);
        $mergedContent = str_replace('&lambda;', 'λ', $mergedContent);
        $mergedContent = str_replace('&mu;', 'μ', $mergedContent);
        $mergedContent = str_replace('&nu;', 'ν', $mergedContent);
        $mergedContent = str_replace('&omega;', 'ω', $mergedContent);
        $mergedContent = str_replace('&omicron;', 'ο', $mergedContent);
        $mergedContent = str_replace('&phi;', 'φ', $mergedContent);
        $mergedContent = str_replace('&pi;', 'π', $mergedContent);
        $mergedContent = str_replace('&piv;', 'ϖ', $mergedContent);
        $mergedContent = str_replace('&psi;', 'ψ', $mergedContent);
        $mergedContent = str_replace('&rho;', 'ρ', $mergedContent);
        $mergedContent = str_replace('&sigma;', 'σ', $mergedContent);
        $mergedContent = str_replace('&sigmaf;', 'ς', $mergedContent);
        $mergedContent = str_replace('&tau;', 'τ', $mergedContent);
        $mergedContent = str_replace('&theta;', 'θ', $mergedContent);
        $mergedContent = str_replace('&thetasym;', 'ϑ', $mergedContent);
        $mergedContent = str_replace('&upsih;', 'ϒ', $mergedContent);
        $mergedContent = str_replace('&upsilon;', 'υ', $mergedContent);
        $mergedContent = str_replace('&xi;', 'ξ', $mergedContent);
        $mergedContent = str_replace('&zeta;', 'ζ', $mergedContent);
        $mergedContent = str_replace('&straightepsilon;', 'ϵ', $mergedContent);
        $mergedContent = str_replace('&backepsilon;', '϶', $mergedContent);
        $mergedContent = str_replace('&varkappa;', 'ϰ', $mergedContent);
        $mergedContent = str_replace('&varrho;', 'ϱ', $mergedContent);
        $mergedContent = str_replace('&Gammad;', 'Ϝ', $mergedContent);
        $mergedContent = str_replace('&gammad;', 'ϝ', $mergedContent);
        $mergedContent = str_replace('&straightphi;', 'ϕ', $mergedContent);
// Fancy Text
        $mergedContent = str_replace('&Ascr;', '𝒜', $mergedContent);
        $mergedContent = str_replace('&Bscr;', 'ℬ', $mergedContent);
        $mergedContent = str_replace('&Cscr;', '𝒞', $mergedContent);
        $mergedContent = str_replace('&Dscr;', '𝒟', $mergedContent);
        $mergedContent = str_replace('&Escr;', 'ℰ', $mergedContent);
        $mergedContent = str_replace('&Fscr;', 'ℱ', $mergedContent);
        $mergedContent = str_replace('&Gscr;', '𝒢', $mergedContent);
        $mergedContent = str_replace('&Hscr;', 'ℋ', $mergedContent);
        $mergedContent = str_replace('&Iscr;', 'ℐ', $mergedContent);
        $mergedContent = str_replace('&Jscr;', '𝒥', $mergedContent);
        $mergedContent = str_replace('&Kscr;', '𝒦', $mergedContent);
        $mergedContent = str_replace('&Lscr;', 'ℒ', $mergedContent);
        $mergedContent = str_replace('&Mscr;', 'ℳ', $mergedContent);
        $mergedContent = str_replace('&Nscr;', '𝒩', $mergedContent);
        $mergedContent = str_replace('&Oscr;', '𝒪', $mergedContent);
        $mergedContent = str_replace('&Pscr;', '𝒫', $mergedContent);
        $mergedContent = str_replace('&Qscr;', '𝒬', $mergedContent);
        $mergedContent = str_replace('&Rscr;', 'ℛ', $mergedContent);
        $mergedContent = str_replace('&Sscr;', '𝒮', $mergedContent);
        $mergedContent = str_replace('&Tscr;', '𝒯', $mergedContent);
        $mergedContent = str_replace('&Uscr;', '𝒰', $mergedContent);
        $mergedContent = str_replace('&Vscr;', '𝒱', $mergedContent);
        $mergedContent = str_replace('&Wscr;', '𝒲', $mergedContent);
        $mergedContent = str_replace('&Xscr;', '𝒳', $mergedContent);
        $mergedContent = str_replace('&Yscr;', '𝒴', $mergedContent);
        $mergedContent = str_replace('&Zscr;', '𝒵', $mergedContent);
        $mergedContent = str_replace('&ascr;', '𝒶', $mergedContent);
        $mergedContent = str_replace('&bscr;', '𝒷', $mergedContent);
        $mergedContent = str_replace('&cscr;', '𝒸', $mergedContent);
        $mergedContent = str_replace('&dscr;', '𝒹', $mergedContent);
        $mergedContent = str_replace('&escr;', 'ℯ', $mergedContent);
        $mergedContent = str_replace('&fscr;', '𝒻', $mergedContent);
        $mergedContent = str_replace('&gscr;', 'ℊ', $mergedContent);
        $mergedContent = str_replace('&hscr;', '𝒽', $mergedContent);
        $mergedContent = str_replace('&iscr;', '𝒾', $mergedContent);
        $mergedContent = str_replace('&jscr;', '𝒿', $mergedContent);
        $mergedContent = str_replace('&kscr;', '𝓀', $mergedContent);
        $mergedContent = str_replace('&lscr;', '𝓁', $mergedContent);
        $mergedContent = str_replace('&mscr;', '𝓂', $mergedContent);
        $mergedContent = str_replace('&nscr;', '𝓃', $mergedContent);
        $mergedContent = str_replace('&oscr;', 'ℴ', $mergedContent);
        $mergedContent = str_replace('&pscr;', '𝓅', $mergedContent);
        $mergedContent = str_replace('&qscr;', '𝓆', $mergedContent);
        $mergedContent = str_replace('&rscr;', '𝓇', $mergedContent);
        $mergedContent = str_replace('&sscr;', '𝓈', $mergedContent);
        $mergedContent = str_replace('&tscr;', '𝓉', $mergedContent);
        $mergedContent = str_replace('&uscr;', '𝓊', $mergedContent);
        $mergedContent = str_replace('&vscr;', '𝓋', $mergedContent);
        $mergedContent = str_replace('&wscr;', '𝓌', $mergedContent);
        $mergedContent = str_replace('&xscr;', '𝓍', $mergedContent);
        $mergedContent = str_replace('&yscr;', '𝓎', $mergedContent);
        $mergedContent = str_replace('&zscr;', '𝓏', $mergedContent);
        $mergedContent = str_replace('&Afr;', '𝔄', $mergedContent);
        $mergedContent = str_replace('&Bfr;', '𝔅', $mergedContent);
        $mergedContent = str_replace('&Cfr;', 'ℭ', $mergedContent);
        $mergedContent = str_replace('&Dfr;', '𝔇', $mergedContent);
        $mergedContent = str_replace('&Efr;', '𝔈', $mergedContent);
        $mergedContent = str_replace('&Ffr;', '𝔉', $mergedContent);
        $mergedContent = str_replace('&Gfr;', '𝔊', $mergedContent);
        $mergedContent = str_replace('&Hfr;', 'ℌ', $mergedContent);
        $mergedContent = str_replace('&Ifr;', 'ℑ', $mergedContent);
        $mergedContent = str_replace('&Jfr;', '𝔍', $mergedContent);
        $mergedContent = str_replace('&Kfr;', '𝔎', $mergedContent);
        $mergedContent = str_replace('&Lfr;', '𝔏', $mergedContent);
        $mergedContent = str_replace('&Mfr;', '𝔐', $mergedContent);
        $mergedContent = str_replace('&Nfr;', '𝔑', $mergedContent);
        $mergedContent = str_replace('&Ofr;', '𝔒', $mergedContent);
        $mergedContent = str_replace('&Pfr;', '𝔓', $mergedContent);
        $mergedContent = str_replace('&Qfr;', '𝔔', $mergedContent);
        $mergedContent = str_replace('&Rfr;', 'ℜ', $mergedContent);
        $mergedContent = str_replace('&Sfr;', '𝔖', $mergedContent);
        $mergedContent = str_replace('&Tfr;', '𝔗', $mergedContent);
        $mergedContent = str_replace('&Ufr;', '𝔘', $mergedContent);
        $mergedContent = str_replace('&Vfr;', '𝔙', $mergedContent);
        $mergedContent = str_replace('&Wfr;', '𝔚', $mergedContent);
        $mergedContent = str_replace('&Xfr;', '𝔛', $mergedContent);
        $mergedContent = str_replace('&Yfr;', '𝔜', $mergedContent);
        $mergedContent = str_replace('&Zfr;', 'ℨ', $mergedContent);
        $mergedContent = str_replace('&afr;', '𝔞', $mergedContent);
        $mergedContent = str_replace('&bfr;', '𝔟', $mergedContent);
        $mergedContent = str_replace('&cfr;', '𝔠', $mergedContent);
        $mergedContent = str_replace('&dfr;', '𝔡', $mergedContent);
        $mergedContent = str_replace('&efr;', '𝔢', $mergedContent);
        $mergedContent = str_replace('&ffr;', '𝔣', $mergedContent);
        $mergedContent = str_replace('&gfr;', '𝔤', $mergedContent);
        $mergedContent = str_replace('&hfr;', '𝔥', $mergedContent);
        $mergedContent = str_replace('&ifr;', '𝔦', $mergedContent);
        $mergedContent = str_replace('&jfr;', '𝔧', $mergedContent);
        $mergedContent = str_replace('&kfr;', '𝔨', $mergedContent);
        $mergedContent = str_replace('&lfr;', '𝔩', $mergedContent);
        $mergedContent = str_replace('&mfr;', '𝔪', $mergedContent);
        $mergedContent = str_replace('&nfr;', '𝔫', $mergedContent);
        $mergedContent = str_replace('&ofr;', '𝔬', $mergedContent);
        $mergedContent = str_replace('&pfr;', '𝔭', $mergedContent);
        $mergedContent = str_replace('&qfr;', '𝔮', $mergedContent);
        $mergedContent = str_replace('&rfr;', '𝔯', $mergedContent);
        $mergedContent = str_replace('&sfr;', '𝔰', $mergedContent);
        $mergedContent = str_replace('&tfr;', '𝔱', $mergedContent);
        $mergedContent = str_replace('&ufr;', '𝔲', $mergedContent);
        $mergedContent = str_replace('&vfr;', '𝔳', $mergedContent);
        $mergedContent = str_replace('&wfr;', '𝔴', $mergedContent);
        $mergedContent = str_replace('&xfr;', '𝔵', $mergedContent);
        $mergedContent = str_replace('&yfr;', '𝔶', $mergedContent);
        $mergedContent = str_replace('&zfr;', '𝔷', $mergedContent);
        $mergedContent = str_replace('&Aopf;', '𝔸', $mergedContent);
        $mergedContent = str_replace('&Bopf;', '𝔹', $mergedContent);
        $mergedContent = str_replace('&Copf;', 'ℂ', $mergedContent);
        $mergedContent = str_replace('&Dopf;', '𝔻', $mergedContent);
        $mergedContent = str_replace('&Eopf;', '𝔼', $mergedContent);
        $mergedContent = str_replace('&Fopf;', '𝔽', $mergedContent);
        $mergedContent = str_replace('&Gopf;', '𝔾', $mergedContent);
        $mergedContent = str_replace('&Hopf;', 'ℍ', $mergedContent);
        $mergedContent = str_replace('&Iopf;', '𝕀', $mergedContent);
        $mergedContent = str_replace('&Jopf;', '𝕁', $mergedContent);
        $mergedContent = str_replace('&Kopf;', '𝕂', $mergedContent);
        $mergedContent = str_replace('&Lopf;', '𝕃', $mergedContent);
        $mergedContent = str_replace('&Mopf;', '𝕄', $mergedContent);
        $mergedContent = str_replace('&Nopf;', 'ℕ', $mergedContent);
        $mergedContent = str_replace('&Oopf;', '𝕆', $mergedContent);
        $mergedContent = str_replace('&Popf;', 'ℙ', $mergedContent);
        $mergedContent = str_replace('&Qopf;', 'ℚ', $mergedContent);
        $mergedContent = str_replace('&Ropf;', 'ℝ', $mergedContent);
        $mergedContent = str_replace('&Sopf;', '𝕊', $mergedContent);
        $mergedContent = str_replace('&Topf;', '𝕋', $mergedContent);
        $mergedContent = str_replace('&Uopf;', '𝕌', $mergedContent);
        $mergedContent = str_replace('&Vopf;', '𝕍', $mergedContent);
        $mergedContent = str_replace('&Wopf;', '𝕎', $mergedContent);
        $mergedContent = str_replace('&Xopf;', '𝕏', $mergedContent);
        $mergedContent = str_replace('&Yopf;', '𝕐', $mergedContent);
        $mergedContent = str_replace('&Zopf;', 'ℤ', $mergedContent);
        $mergedContent = str_replace('&aopf;', '𝕒', $mergedContent);
        $mergedContent = str_replace('&bopf;', '𝕓', $mergedContent);
        $mergedContent = str_replace('&copf;', '𝕔', $mergedContent);
        $mergedContent = str_replace('&dopf;', '𝕕', $mergedContent);
        $mergedContent = str_replace('&eopf;', '𝕖', $mergedContent);
        $mergedContent = str_replace('&fopf;', '𝕗', $mergedContent);
        $mergedContent = str_replace('&gopf;', '𝕘', $mergedContent);
        $mergedContent = str_replace('&hopf;', '𝕙', $mergedContent);
        $mergedContent = str_replace('&iopf;', '𝕚', $mergedContent);
        $mergedContent = str_replace('&jopf;', '𝕛', $mergedContent);
        $mergedContent = str_replace('&kopf;', '𝕜', $mergedContent);
        $mergedContent = str_replace('&lopf;', '𝕝', $mergedContent);
        $mergedContent = str_replace('&mopf;', '𝕞', $mergedContent);
        $mergedContent = str_replace('&nopf;', '𝕟', $mergedContent);
        $mergedContent = str_replace('&oopf;', '𝕠', $mergedContent);
        $mergedContent = str_replace('&popf;', '𝕡', $mergedContent);
        $mergedContent = str_replace('&qopf;', '𝕢', $mergedContent);
        $mergedContent = str_replace('&ropf;', '𝕣', $mergedContent);
        $mergedContent = str_replace('&sopf;', '𝕤', $mergedContent);
        $mergedContent = str_replace('&topf;', '𝕥', $mergedContent);
        $mergedContent = str_replace('&uopf;', '𝕦', $mergedContent);
        $mergedContent = str_replace('&vopf;', '𝕧', $mergedContent);
        $mergedContent = str_replace('&wopf;', '𝕨', $mergedContent);
        $mergedContent = str_replace('&xopf;', '𝕩', $mergedContent);
        $mergedContent = str_replace('&yopf;', '𝕪', $mergedContent);
        $mergedContent = str_replace('&zopf;', '𝕫', $mergedContent);
// Cyrilic
        $mergedContent = str_replace('&IOcy;', 'Ё', $mergedContent);
        $mergedContent = str_replace('&YIcy;', 'Ї', $mergedContent);
        $mergedContent = str_replace('&Acy;', 'А', $mergedContent);
        $mergedContent = str_replace('&Bcy;', 'Б', $mergedContent);
        $mergedContent = str_replace('&Vcy;', 'В', $mergedContent);
        $mergedContent = str_replace('&Gcy;', 'Г', $mergedContent);
        $mergedContent = str_replace('&Dcy;', 'Д', $mergedContent);
        $mergedContent = str_replace('&Zcy;', 'З', $mergedContent);
        $mergedContent = str_replace('&Icy;', 'И', $mergedContent);
        $mergedContent = str_replace('&Jcy;', 'Й', $mergedContent);
        $mergedContent = str_replace('&Kcy;', 'К', $mergedContent);
        $mergedContent = str_replace('&Lcy;', 'Л', $mergedContent);
        $mergedContent = str_replace('&Mcy;', 'М', $mergedContent);
        $mergedContent = str_replace('&Ncy;', 'Н', $mergedContent);
        $mergedContent = str_replace('&Ocy;', 'О', $mergedContent);
        $mergedContent = str_replace('&Pcy;', 'П', $mergedContent);
        $mergedContent = str_replace('&Rcy;', 'Р', $mergedContent);
        $mergedContent = str_replace('&Scy;', 'С', $mergedContent);
        $mergedContent = str_replace('&Tcy;', 'Т', $mergedContent);
        $mergedContent = str_replace('&Ucy;', 'У', $mergedContent);
        $mergedContent = str_replace('&Fcy;', 'Ф', $mergedContent);
        $mergedContent = str_replace('&Ycy;', 'Ы', $mergedContent);
        $mergedContent = str_replace('&Ecy;', 'Э', $mergedContent);
        $mergedContent = str_replace('&DJcy;', 'Ђ', $mergedContent);
        $mergedContent = str_replace('&GJcy;', 'Ѓ', $mergedContent);
        $mergedContent = str_replace('&Jukcy;', 'Є', $mergedContent);
        $mergedContent = str_replace('&DScy;', 'Ѕ', $mergedContent);
        $mergedContent = str_replace('&Iukcy;', 'І', $mergedContent);
        $mergedContent = str_replace('&Jsercy;', 'Ј', $mergedContent);
        $mergedContent = str_replace('&LJcy;', 'Љ', $mergedContent);
        $mergedContent = str_replace('&NJcy;', 'Њ', $mergedContent);
        $mergedContent = str_replace('&TSHcy;', 'Ћ', $mergedContent);
        $mergedContent = str_replace('&KJcy;', 'Ќ', $mergedContent);
        $mergedContent = str_replace('&Ubrcy;', 'Ў', $mergedContent);
        $mergedContent = str_replace('&DZcy;', 'Џ', $mergedContent);
        $mergedContent = str_replace('&IEcy;', 'Е', $mergedContent);
        $mergedContent = str_replace('&ZHcy;', 'Ж', $mergedContent);
        $mergedContent = str_replace('&KHcy;', 'Х', $mergedContent);
        $mergedContent = str_replace('&TScy;', 'Ц', $mergedContent);
        $mergedContent = str_replace('&CHcy;', 'Ч', $mergedContent);
        $mergedContent = str_replace('&SHcy;', 'Ш', $mergedContent);
        $mergedContent = str_replace('&SHCHcy;', 'Щ', $mergedContent);
        $mergedContent = str_replace('&HARDcy;', 'Ъ', $mergedContent);
        $mergedContent = str_replace('&SOFTcy;', 'Ь', $mergedContent);
        $mergedContent = str_replace('&YUcy;', 'Ю', $mergedContent);
        $mergedContent = str_replace('&YAcy;', 'Я', $mergedContent);
// Spaces
        $mergedContent = str_replace('&ensp;', ' ', $mergedContent);
        $mergedContent = str_replace('&emsp;', ' ', $mergedContent);
        $mergedContent = str_replace('&thinsp;', ' ', $mergedContent);
        $mergedContent = str_replace('&hairsp;', ' ', $mergedContent);
        $mergedContent = str_replace('&puncsp;', ' ', $mergedContent);
        $mergedContent = str_replace('&numsp;', ' ', $mergedContent);
        $mergedContent = str_replace('&emsp13;', ' ', $mergedContent);
        $mergedContent = str_replace('&emsp14;', ' ', $mergedContent);
// Fractions
        $mergedContent = str_replace('&frac14;', '¼', $mergedContent);
        $mergedContent = str_replace('&frac12;', '½', $mergedContent);
        $mergedContent = str_replace('&frac34;', '¾', $mergedContent);
        $mergedContent = str_replace('&frac13;', '⅓', $mergedContent);
        $mergedContent = str_replace('&frac23;', '⅔', $mergedContent);
        $mergedContent = str_replace('&frac15;', '⅕', $mergedContent);
        $mergedContent = str_replace('&frac25;', '⅖', $mergedContent);
        $mergedContent = str_replace('&frac35;', '⅗', $mergedContent);
        $mergedContent = str_replace('&frac45;', '⅘', $mergedContent);
        $mergedContent = str_replace('&frac16;', '⅙', $mergedContent);
        $mergedContent = str_replace('&frac56;', '⅚', $mergedContent);
        $mergedContent = str_replace('&frac18;', '⅛', $mergedContent);
        $mergedContent = str_replace('&frac38;', '⅜', $mergedContent);
        $mergedContent = str_replace('&frac58;', '⅝', $mergedContent);
        $mergedContent = str_replace('&frac78;', '⅞', $mergedContent);
// Symbols
        $mergedContent = str_replace('&frasl;', '⁄', $mergedContent);
        $mergedContent = str_replace('&weierp;', '℘', $mergedContent);
        $mergedContent = str_replace('&image;', 'ℑ', $mergedContent);
        $mergedContent = str_replace('&real;', 'ℜ', $mergedContent);
        $mergedContent = str_replace('&alefsym;', 'ℵ', $mergedContent);
        $mergedContent = str_replace('&forall;', '∀', $mergedContent);
        $mergedContent = str_replace('&part;', '∂', $mergedContent);
        $mergedContent = str_replace('&exist;', '∃', $mergedContent);
        $mergedContent = str_replace('&empty;', '∅', $mergedContent);
        $mergedContent = str_replace('&nabla;', '∇', $mergedContent);
        $mergedContent = str_replace('&isin;', '∈', $mergedContent);
        $mergedContent = str_replace('&notin;', '∉', $mergedContent);
        $mergedContent = str_replace('&ni;', '∋', $mergedContent);
        $mergedContent = str_replace('&prod;', '∏', $mergedContent);
        $mergedContent = str_replace('&sum;', '∑', $mergedContent);
        $mergedContent = str_replace('&minus;', '−', $mergedContent);
        $mergedContent = str_replace('&lowast;', '∗', $mergedContent);
        $mergedContent = str_replace('&radic;', '√', $mergedContent);
        $mergedContent = str_replace('&prop;', '∝', $mergedContent);
        $mergedContent = str_replace('&infin;', '∞', $mergedContent);
        $mergedContent = str_replace('&ang;', '∠', $mergedContent);
        $mergedContent = str_replace('&and;', '∧', $mergedContent);
        $mergedContent = str_replace('&or;', '∨', $mergedContent);
        $mergedContent = str_replace('&cap;', '∩', $mergedContent);
        $mergedContent = str_replace('&cup;', '∪', $mergedContent);
        $mergedContent = str_replace('&int;', '∫', $mergedContent);
        $mergedContent = str_replace('&there4;', '∴', $mergedContent);
        $mergedContent = str_replace('&sim;', '∼', $mergedContent);
        $mergedContent = str_replace('&cong;', '≅', $mergedContent);
        $mergedContent = str_replace('&asymp;', '≈', $mergedContent);
        $mergedContent = str_replace('&ne;', '≠', $mergedContent);
        $mergedContent = str_replace('&equiv;', '≡', $mergedContent);
        $mergedContent = str_replace('&le;', '≤', $mergedContent);
        $mergedContent = str_replace('&ge;', '≥', $mergedContent);
        $mergedContent = str_replace('&sub;', '⊂', $mergedContent);
        $mergedContent = str_replace('&sup;', '⊃', $mergedContent);
        $mergedContent = str_replace('&nsub;', '⊄', $mergedContent);
        $mergedContent = str_replace('&sube;', '⊆', $mergedContent);
        $mergedContent = str_replace('&supe;', '⊇', $mergedContent);
        $mergedContent = str_replace('&oplus;', '⊕', $mergedContent);
        $mergedContent = str_replace('&otimes;', '⊗', $mergedContent);
        $mergedContent = str_replace('&perp;', '⊥', $mergedContent);
        $mergedContent = str_replace('&sdot;', '⋅', $mergedContent);
        $mergedContent = str_replace('&lceil;', '⌈', $mergedContent);
        $mergedContent = str_replace('&rceil;', '⌉', $mergedContent);
        $mergedContent = str_replace('&lfloor;', '⌊', $mergedContent);
        $mergedContent = str_replace('&rfloor;', '⌋', $mergedContent);
        $mergedContent = str_replace('&lang;', '⟨', $mergedContent);
        $mergedContent = str_replace('&rang;', '⟩', $mergedContent);
        $mergedContent = str_replace('&loz;', '◊', $mergedContent);
        $mergedContent = str_replace('&spades;', '♠', $mergedContent);
        $mergedContent = str_replace('&clubs;', '♣', $mergedContent);
        $mergedContent = str_replace('&hearts;', '♥', $mergedContent);
        $mergedContent = str_replace('&diams;', '♦', $mergedContent);
        $mergedContent = str_replace('&incare;', '℅', $mergedContent);
        $mergedContent = str_replace('&hamilt;', 'ℋ', $mergedContent);
        $mergedContent = str_replace('&planckh;', 'ℎ', $mergedContent);
        $mergedContent = str_replace('&planck;', 'ℏ', $mergedContent);
        $mergedContent = str_replace('&ell;', 'ℓ', $mergedContent);
        $mergedContent = str_replace('&numero;', '№', $mergedContent);
        $mergedContent = str_replace('&copysr;', '℗', $mergedContent);
        $mergedContent = str_replace('&rx;', '℞', $mergedContent);
        $mergedContent = str_replace('&mho;', '℧', $mergedContent);
        $mergedContent = str_replace('&iiota;', '℩', $mergedContent);
        $mergedContent = str_replace('&bernou;', 'ℬ', $mergedContent);
        $mergedContent = str_replace('&beth;', 'ℶ', $mergedContent);
        $mergedContent = str_replace('&gimel;', 'ℷ', $mergedContent);
        $mergedContent = str_replace('&daleth;', 'ℸ', $mergedContent);
        $mergedContent = str_replace('&DD;', 'ⅅ', $mergedContent);
        $mergedContent = str_replace('&dd;', 'ⅆ', $mergedContent);
        $mergedContent = str_replace('&ee;', 'ⅇ', $mergedContent);
        $mergedContent = str_replace('&ii;', 'ⅈ', $mergedContent);
        $mergedContent = str_replace('&starf;', '★', $mergedContent);
        $mergedContent = str_replace('&star;', '☆', $mergedContent);
        $mergedContent = str_replace('&phone;', '☎', $mergedContent);
        $mergedContent = str_replace('&female;', '♀', $mergedContent);
        $mergedContent = str_replace('&male;', '♂', $mergedContent);
        $mergedContent = str_replace('&sung;', '♪', $mergedContent);
        $mergedContent = str_replace('&flat;', '♭', $mergedContent);
        $mergedContent = str_replace('&natural;', '♮', $mergedContent);
        $mergedContent = str_replace('&sharp;', '♯', $mergedContent);
        $mergedContent = str_replace('&check;', '✓', $mergedContent);
        $mergedContent = str_replace('&cross;', '✗', $mergedContent);
        $mergedContent = str_replace('&malt;', '✠', $mergedContent);
        $mergedContent = str_replace('&sext;', '✶', $mergedContent);
        $mergedContent = str_replace('&VerticalSeparator;', '❘', $mergedContent);
        $mergedContent = str_replace('&lbbrk;', '❲', $mergedContent);
        $mergedContent = str_replace('&rbbrk;', '❳', $mergedContent);
        $mergedContent = str_replace('&iexcl;', '¡', $mergedContent);
        $mergedContent = str_replace('&brvbar;', '¦', $mergedContent);
        $mergedContent = str_replace('&sect;', '§', $mergedContent);
        $mergedContent = str_replace('&uml;', '¨', $mergedContent);
        $mergedContent = str_replace('&ordf;', 'ª', $mergedContent);
        $mergedContent = str_replace('&not;', '¬', $mergedContent);
        $mergedContent = str_replace('&shy;', '­', $mergedContent);
        $mergedContent = str_replace('&macr;', '¯', $mergedContent);
        $mergedContent = str_replace('&sup2;', '²', $mergedContent);
        $mergedContent = str_replace('&sup3;', '³', $mergedContent);
        $mergedContent = str_replace('&acute;', '´', $mergedContent);
        $mergedContent = str_replace('&micro;', 'µ', $mergedContent);
        $mergedContent = str_replace('&para;', '¶', $mergedContent);
        $mergedContent = str_replace('&middot;', '·', $mergedContent);
        $mergedContent = str_replace('&cedil;', '¸', $mergedContent);
        $mergedContent = str_replace('&sup1;', '¹', $mergedContent);
        $mergedContent = str_replace('&ordm;', 'º', $mergedContent);
        $mergedContent = str_replace('&iquest;', '¿', $mergedContent);
        $mergedContent = str_replace('&hyphen;', '‐', $mergedContent);
        $mergedContent = str_replace('&ndash;', '–', $mergedContent);
        $mergedContent = str_replace('&mdash;', '—', $mergedContent);
        $mergedContent = str_replace('&horbar;', '―', $mergedContent);
        $mergedContent = str_replace('&Vert;', '‖', $mergedContent);
        $mergedContent = str_replace('&dagger;', '†', $mergedContent);
        $mergedContent = str_replace('&Dagger;', '‡', $mergedContent);
        $mergedContent = str_replace('&bull;', '•', $mergedContent);
        $mergedContent = str_replace('&nldr;', '‥', $mergedContent);
        $mergedContent = str_replace('&hellip;', '…', $mergedContent);
        $mergedContent = str_replace('&;', '‰', $mergedContent);
        $mergedContent = str_replace('&pertenk;', '‱', $mergedContent);
        $mergedContent = str_replace('&prime;', '′', $mergedContent);
        $mergedContent = str_replace('&Prime;', '″', $mergedContent);
        $mergedContent = str_replace('&tprime;', '‴', $mergedContent);
        $mergedContent = str_replace('&bprime;', '‵', $mergedContent);
        $mergedContent = str_replace('&oline;', '‾', $mergedContent);
        $mergedContent = str_replace('&caret;', '⁁', $mergedContent);
        $mergedContent = str_replace('&hybull;', '⁃', $mergedContent);
        $mergedContent = str_replace('&bsemi;', '⁏', $mergedContent);
        $mergedContent = str_replace('&qprime;', '⁗', $mergedContent);
        $mergedContent = str_replace('&plus;', '+', $mergedContent);
        $mergedContent = str_replace('&times;', '×', $mergedContent);
        $mergedContent = str_replace('&divide;', '÷', $mergedContent);
        $mergedContent = str_replace('&equals;', '=', $mergedContent);
        $mergedContent = str_replace('&plusmn;', '±', $mergedContent);
        $mergedContent = str_replace('&lt;', '<', $mergedContent);
        $mergedContent = str_replace('&gt;', '>', $mergedContent);
        $mergedContent = str_replace('&deg;', '°', $mergedContent);
        $mergedContent = str_replace('&fnof;', 'ƒ', $mergedContent);
        $mergedContent = str_replace('&percnt;', '%', $mergedContent);
        $mergedContent = str_replace('&permil;', '‰', $mergedContent);
        $mergedContent = str_replace('&comp;', '∁', $mergedContent);
        $mergedContent = str_replace('&nexist;', '∄', $mergedContent);
        $mergedContent = str_replace('&notni;', '∌', $mergedContent);
        $mergedContent = str_replace('&coprod;', '∐', $mergedContent);
        $mergedContent = str_replace('&mnplus;', '∓', $mergedContent);
        $mergedContent = str_replace('&plusdo;', '∔', $mergedContent);
        $mergedContent = str_replace('&setminus;', '∖', $mergedContent);
        $mergedContent = str_replace('&compfn;', '∘', $mergedContent);
        $mergedContent = str_replace('&angrt;', '∟', $mergedContent);
        $mergedContent = str_replace('&angmsd;', '∡', $mergedContent);
        $mergedContent = str_replace('&angsph;', '∢', $mergedContent);
        $mergedContent = str_replace('&mid;', '∣', $mergedContent);
        $mergedContent = str_replace('&nmid;', '∤', $mergedContent);
        $mergedContent = str_replace('&parallel;', '∥', $mergedContent);
        $mergedContent = str_replace('&npar;', '∦', $mergedContent);
        $mergedContent = str_replace('&Int;', '∬', $mergedContent);
        $mergedContent = str_replace('&iiint;', '∭', $mergedContent);
        $mergedContent = str_replace('&conint;', '∮', $mergedContent);
        $mergedContent = str_replace('&Conint;', '∯', $mergedContent);
        $mergedContent = str_replace('&Cconint;', '∰', $mergedContent);
        $mergedContent = str_replace('&cwint;', '∱', $mergedContent);
        $mergedContent = str_replace('&cwconint;', '∲', $mergedContent);
        $mergedContent = str_replace('&awconint;', '∳', $mergedContent);
        $mergedContent = str_replace('&because;', '∵', $mergedContent);
        $mergedContent = str_replace('&ratio;', '∶', $mergedContent);
        $mergedContent = str_replace('&Colon;', '∷', $mergedContent);
        $mergedContent = str_replace('&minusd;', '∸', $mergedContent);
        $mergedContent = str_replace('&mDDot;', '∺', $mergedContent);
        $mergedContent = str_replace('&homtht;', '∻', $mergedContent);
        $mergedContent = str_replace('&bsim;', '∽', $mergedContent);
        $mergedContent = str_replace('&ac;', '∾', $mergedContent);
        $mergedContent = str_replace('&acd;', '∿', $mergedContent);
        $mergedContent = str_replace('&wreath;', '≀', $mergedContent);
        $mergedContent = str_replace('&nsim;', '≁', $mergedContent);
        $mergedContent = str_replace('&esim;', '≂', $mergedContent);
        $mergedContent = str_replace('&sime;', '≃', $mergedContent);
        $mergedContent = str_replace('&nsime;', '≄', $mergedContent);
        $mergedContent = str_replace('&simne;', '≆', $mergedContent);
        $mergedContent = str_replace('&ncong;', '≇', $mergedContent);
        $mergedContent = str_replace('&nap;', '≉', $mergedContent);
        $mergedContent = str_replace('&approxeq;', '≊', $mergedContent);
        $mergedContent = str_replace('&apid;', '≋', $mergedContent);
        $mergedContent = str_replace('&bcong;', '≌', $mergedContent);
        $mergedContent = str_replace('&asympeq;', '≍', $mergedContent);
        $mergedContent = str_replace('&bump;', '≎', $mergedContent);
        $mergedContent = str_replace('&bumpe;', '≏', $mergedContent);
        $mergedContent = str_replace('&esdot;', '≐', $mergedContent);
        $mergedContent = str_replace('&eDot;', '≑', $mergedContent);
        $mergedContent = str_replace('&efDot;', '≒', $mergedContent);
        $mergedContent = str_replace('&erDot;', '≓', $mergedContent);
        $mergedContent = str_replace('&colone;', '≔', $mergedContent);
        $mergedContent = str_replace('&ecolon;', '≕', $mergedContent);
        $mergedContent = str_replace('&ecir;', '≖', $mergedContent);
        $mergedContent = str_replace('&cire;', '≗', $mergedContent);
        $mergedContent = str_replace('&wedgeq;', '≙', $mergedContent);
        $mergedContent = str_replace('&veeeq;', '≚', $mergedContent);
        $mergedContent = str_replace('&trie;', '≜', $mergedContent);
        $mergedContent = str_replace('&equest;', '≟', $mergedContent);
        $mergedContent = str_replace('&nequiv;', '≢', $mergedContent);
        $mergedContent = str_replace('&lE;', '≦', $mergedContent);
        $mergedContent = str_replace('&gE;', '≧', $mergedContent);
        $mergedContent = str_replace('&lnE;', '≨', $mergedContent);
        $mergedContent = str_replace('&gnE;', '≩', $mergedContent);
        $mergedContent = str_replace('&Lt;', '≪', $mergedContent);
        $mergedContent = str_replace('&Gt;', '≫', $mergedContent);
        $mergedContent = str_replace('&between;', '≬', $mergedContent);
        $mergedContent = str_replace('&NotCupCap;', '≭', $mergedContent);
        $mergedContent = str_replace('&nlt;', '≮', $mergedContent);
        $mergedContent = str_replace('&ngt;', '≯', $mergedContent);
        $mergedContent = str_replace('&nle;', '≰', $mergedContent);
        $mergedContent = str_replace('&nge;', '≱', $mergedContent);
        $mergedContent = str_replace('&lsim;', '≲', $mergedContent);
        $mergedContent = str_replace('&gsim;', '≳', $mergedContent);
        $mergedContent = str_replace('&nlsim;', '≴', $mergedContent);
        $mergedContent = str_replace('&ngsim;', '≵', $mergedContent);
        $mergedContent = str_replace('&lg;', '≶', $mergedContent);
        $mergedContent = str_replace('&gl;', '≷', $mergedContent);
        $mergedContent = str_replace('&ntlg;', '≸', $mergedContent);
        $mergedContent = str_replace('&ntgl;', '≹', $mergedContent);
        $mergedContent = str_replace('&pr;', '≺', $mergedContent);
        $mergedContent = str_replace('&sc;', '≻', $mergedContent);
        $mergedContent = str_replace('&prcue;', '≼', $mergedContent);
        $mergedContent = str_replace('&sccue;', '≽', $mergedContent);
        $mergedContent = str_replace('&prsim;', '≾', $mergedContent);
        $mergedContent = str_replace('&scsim;', '≿', $mergedContent);
        $mergedContent = str_replace('&npr;', '⊀', $mergedContent);
        $mergedContent = str_replace('&nsc;', '⊁', $mergedContent);
        $mergedContent = str_replace('&nsup;', '⊅', $mergedContent);
        $mergedContent = str_replace('&nsube;', '⊈', $mergedContent);
        $mergedContent = str_replace('&nsupe;', '⊉', $mergedContent);
        $mergedContent = str_replace('&subne;', '⊊', $mergedContent);
        $mergedContent = str_replace('&supne;', '⊋', $mergedContent);
        $mergedContent = str_replace('&cupdot;', '⊍', $mergedContent);
        $mergedContent = str_replace('&uplus;', '⊎', $mergedContent);
        $mergedContent = str_replace('&sqsub;', '⊏', $mergedContent);
        $mergedContent = str_replace('&sqsup;', '⊐', $mergedContent);
        $mergedContent = str_replace('&sqsube;', '⊑', $mergedContent);
        $mergedContent = str_replace('&sqsupe;', '⊒', $mergedContent);
        $mergedContent = str_replace('&sqcap;', '⊓', $mergedContent);
        $mergedContent = str_replace('&sqcup;', '⊔', $mergedContent);
        $mergedContent = str_replace('&ominus;', '⊖', $mergedContent);
        $mergedContent = str_replace('&osol;', '⊘', $mergedContent);
        $mergedContent = str_replace('&odot;', '⊙', $mergedContent);
        $mergedContent = str_replace('&ocir;', '⊚', $mergedContent);
        $mergedContent = str_replace('&oast;', '⊛', $mergedContent);
        $mergedContent = str_replace('&odash;', '⊝', $mergedContent);
        $mergedContent = str_replace('&plusb;', '⊞', $mergedContent);
        $mergedContent = str_replace('&minusb;', '⊟', $mergedContent);
        $mergedContent = str_replace('&timesb;', '⊠', $mergedContent);
        $mergedContent = str_replace('&sdotb;', '⊡', $mergedContent);
        $mergedContent = str_replace('&vdash;', '⊢', $mergedContent);
        $mergedContent = str_replace('&dashv;', '⊣', $mergedContent);
        $mergedContent = str_replace('&top;', '⊤', $mergedContent);
        $mergedContent = str_replace('&models;', '⊧', $mergedContent);
        $mergedContent = str_replace('&vDash;', '⊨', $mergedContent);
        $mergedContent = str_replace('&Vdash;', '⊩', $mergedContent);
        $mergedContent = str_replace('&Vvdash;', '⊪', $mergedContent);
        $mergedContent = str_replace('&VDash;', '⊫', $mergedContent);
        $mergedContent = str_replace('&nvdash;', '⊬', $mergedContent);
        $mergedContent = str_replace('&nvDash;', '⊭', $mergedContent);
        $mergedContent = str_replace('&nVdash;', '⊮', $mergedContent);
        $mergedContent = str_replace('&nVDash;', '⊯', $mergedContent);
        $mergedContent = str_replace('&prurel;', '⊰', $mergedContent);
        $mergedContent = str_replace('&vltri;', '⊲', $mergedContent);
        $mergedContent = str_replace('&vrtri;', '⊳', $mergedContent);
        $mergedContent = str_replace('&ltrie;', '⊴', $mergedContent);
        $mergedContent = str_replace('&rtrie;', '⊵', $mergedContent);
        $mergedContent = str_replace('&origof;', '⊶', $mergedContent);
        $mergedContent = str_replace('&imof;', '⊷', $mergedContent);
        $mergedContent = str_replace('&mumap;', '⊸', $mergedContent);
        $mergedContent = str_replace('&hercon;', '⊹', $mergedContent);
        $mergedContent = str_replace('&intcal;', '⊺', $mergedContent);
        $mergedContent = str_replace('&veebar;', '⊻', $mergedContent);
        $mergedContent = str_replace('&barvee;', '⊽', $mergedContent);
        $mergedContent = str_replace('&angrtvb;', '⊾', $mergedContent);
        $mergedContent = str_replace('&lrtri;', '⊿', $mergedContent);
        $mergedContent = str_replace('&xwedge;', '⋀', $mergedContent);
        $mergedContent = str_replace('&xvee;', '⋁', $mergedContent);
        $mergedContent = str_replace('&xcap;', '⋂', $mergedContent);
        $mergedContent = str_replace('&xcup;', '⋃', $mergedContent);
        $mergedContent = str_replace('&diamond;', '⋄', $mergedContent);
        $mergedContent = str_replace('&Star;', '⋆', $mergedContent);
        $mergedContent = str_replace('&divonx;', '⋇', $mergedContent);
        $mergedContent = str_replace('&bowtie;', '⋈', $mergedContent);
        $mergedContent = str_replace('&ltimes;', '⋉', $mergedContent);
        $mergedContent = str_replace('&rtimes;', '⋊', $mergedContent);
        $mergedContent = str_replace('&lthree;', '⋋', $mergedContent);
        $mergedContent = str_replace('&rthree;', '⋌', $mergedContent);
        $mergedContent = str_replace('&bsime;', '⋍', $mergedContent);
        $mergedContent = str_replace('&cuvee;', '⋎', $mergedContent);
        $mergedContent = str_replace('&cuwed;', '⋏', $mergedContent);
        $mergedContent = str_replace('&Sub;', '⋐', $mergedContent);
        $mergedContent = str_replace('&Sup;', '⋑', $mergedContent);
        $mergedContent = str_replace('&Cap;', '⋒', $mergedContent);
        $mergedContent = str_replace('&Cup;', '⋓', $mergedContent);
        $mergedContent = str_replace('&fork;', '⋔', $mergedContent);
        $mergedContent = str_replace('&epar;', '⋕', $mergedContent);
        $mergedContent = str_replace('&ltdot;', '⋖', $mergedContent);
        $mergedContent = str_replace('&gtdot;', '⋗', $mergedContent);
        $mergedContent = str_replace('&Ll;', '⋘', $mergedContent);
        $mergedContent = str_replace('&Gg;', '⋙', $mergedContent);
        $mergedContent = str_replace('&leg;', '⋚', $mergedContent);
        $mergedContent = str_replace('&gel;', '⋛', $mergedContent);
        $mergedContent = str_replace('&cuepr;', '⋞', $mergedContent);
        $mergedContent = str_replace('&cuesc;', '⋟', $mergedContent);
        $mergedContent = str_replace('&nprcue;', '⋠', $mergedContent);
        $mergedContent = str_replace('&nsccue;', '⋡', $mergedContent);
        $mergedContent = str_replace('&nsqsube;', '⋢', $mergedContent);
        $mergedContent = str_replace('&nsqsupe;', '⋣', $mergedContent);
        $mergedContent = str_replace('&lnsim;', '⋦', $mergedContent);
        $mergedContent = str_replace('&gnsim;', '⋧', $mergedContent);
        $mergedContent = str_replace('&prnsim;', '⋨', $mergedContent);
        $mergedContent = str_replace('&scnsim;', '⋩', $mergedContent);
        $mergedContent = str_replace('&nltri;', '⋪', $mergedContent);
        $mergedContent = str_replace('&nrtri;', '⋫', $mergedContent);
        $mergedContent = str_replace('&nltrie;', '⋬', $mergedContent);
        $mergedContent = str_replace('&nrtrie;', '⋭', $mergedContent);
        $mergedContent = str_replace('&vellip;', '⋮', $mergedContent);
        $mergedContent = str_replace('&ctdot;', '⋯', $mergedContent);
        $mergedContent = str_replace('&utdot;', '⋰', $mergedContent);
        $mergedContent = str_replace('&dtdot;', '⋱', $mergedContent);
        $mergedContent = str_replace('&disin;', '⋲', $mergedContent);
        $mergedContent = str_replace('&isinsv;', '⋳', $mergedContent);
        $mergedContent = str_replace('&isins;', '⋴', $mergedContent);
        $mergedContent = str_replace('&isindot;', '⋵', $mergedContent);
        $mergedContent = str_replace('&notinvc;', '⋶', $mergedContent);
        $mergedContent = str_replace('&notinvb;', '⋷', $mergedContent);
        $mergedContent = str_replace('&isinE;', '⋹', $mergedContent);
        $mergedContent = str_replace('&nisd;', '⋺', $mergedContent);
        $mergedContent = str_replace('&xnis;', '⋻', $mergedContent);
        $mergedContent = str_replace('&nis;', '⋼', $mergedContent);
        $mergedContent = str_replace('&notnivc;', '⋽', $mergedContent);
        $mergedContent = str_replace('&notnivb;', '⋾', $mergedContent);
// Arrows
        $mergedContent = str_replace('&larr;', '←', $mergedContent);
        $mergedContent = str_replace('&uarr;', '↑', $mergedContent);
        $mergedContent = str_replace('&rarr;', '→', $mergedContent);
        $mergedContent = str_replace('&darr;', '↓', $mergedContent);
        $mergedContent = str_replace('&harr;', '↔', $mergedContent);
        $mergedContent = str_replace('&crarr;', '↵', $mergedContent);
        $mergedContent = str_replace('&lArr;', '⇐', $mergedContent);
        $mergedContent = str_replace('&uArr;', '⇑', $mergedContent);
        $mergedContent = str_replace('&rArr;', '⇒', $mergedContent);
        $mergedContent = str_replace('&dArr;', '⇓', $mergedContent);
        $mergedContent = str_replace('&hArr;', '⇔', $mergedContent);
        $mergedContent = str_replace('&olarr;', '↺', $mergedContent);
        $mergedContent = str_replace('&orarr;', '↻', $mergedContent);
        $mergedContent = str_replace('&lharu;', '↼', $mergedContent);
        $mergedContent = str_replace('&lhard;', '↽', $mergedContent);
        $mergedContent = str_replace('&uharr;', '↾', $mergedContent);
        $mergedContent = str_replace('&uharl;', '↿', $mergedContent);
        $mergedContent = str_replace('&rharu;', '⇀', $mergedContent);
        $mergedContent = str_replace('&rhard;', '⇁', $mergedContent);
        $mergedContent = str_replace('&dharr;', '⇂', $mergedContent);
        $mergedContent = str_replace('&dharl;', '⇃', $mergedContent);
        $mergedContent = str_replace('&rlarr;', '⇄', $mergedContent);
        $mergedContent = str_replace('&udarr;', '⇅', $mergedContent);
        $mergedContent = str_replace('&lrarr;', '⇆', $mergedContent);
        $mergedContent = str_replace('&llarr;', '⇇', $mergedContent);
        $mergedContent = str_replace('&uuarr;', '⇈', $mergedContent);
        $mergedContent = str_replace('&rrarr;', '⇉', $mergedContent);
        $mergedContent = str_replace('&ddarr;', '⇊', $mergedContent);
        $mergedContent = str_replace('&lrhar;', '⇋', $mergedContent);
        $mergedContent = str_replace('&rlhar;', '⇌', $mergedContent);
        $mergedContent = str_replace('&nlArr;', '⇍', $mergedContent);
        $mergedContent = str_replace('&nhArr;', '⇎', $mergedContent);
        $mergedContent = str_replace('&nrArr;', '⇏', $mergedContent);
        $mergedContent = str_replace('&vArr;', '⇕', $mergedContent);
        $mergedContent = str_replace('&nwArr;', '⇖', $mergedContent);
        $mergedContent = str_replace('&neArr;', '⇗', $mergedContent);
        $mergedContent = str_replace('&seArr;', '⇘', $mergedContent);
        $mergedContent = str_replace('&swArr;', '⇙', $mergedContent);
        $mergedContent = str_replace('&lAarr;', '⇚', $mergedContent);
        $mergedContent = str_replace('&rAarr;', '⇛', $mergedContent);
        $mergedContent = str_replace('&ziglarr;', '⇜', $mergedContent);
        $mergedContent = str_replace('&zigrarr;', '⇝', $mergedContent);
        $mergedContent = str_replace('&larrb;', '⇤', $mergedContent);
        $mergedContent = str_replace('&rarrb;', '⇥', $mergedContent);
        $mergedContent = str_replace('&duarr;', '⇵', $mergedContent);
        $mergedContent = str_replace('&hoarr;', '⇿', $mergedContent);
        $mergedContent = str_replace('&loarr;', '⇽', $mergedContent);
        $mergedContent = str_replace('&roarr;', '⇾', $mergedContent);
        $mergedContent = str_replace('&xlarr;', '⟵', $mergedContent);
        $mergedContent = str_replace('&xrarr;', '⟶', $mergedContent);
        $mergedContent = str_replace('&xharr;', '⟷', $mergedContent);
        $mergedContent = str_replace('&xlArr;', '⟸', $mergedContent);
        $mergedContent = str_replace('&xrArr;', '⟹', $mergedContent);
        $mergedContent = str_replace('&xhArr;', '⟺', $mergedContent);
        $mergedContent = str_replace('&dzigrarr;', '⟿', $mergedContent);
        $mergedContent = str_replace('&xmap;', '⟼', $mergedContent);
        $mergedContent = str_replace('&nvlArr;', '⤂', $mergedContent);
        $mergedContent = str_replace('&nvrArr;', '⤃', $mergedContent);
        $mergedContent = str_replace('&nvHarr;', '⤄', $mergedContent);
        $mergedContent = str_replace('&Map;', '⤅', $mergedContent);
        $mergedContent = str_replace('&lbarr;', '⤌', $mergedContent);
        $mergedContent = str_replace('&rbarr;', '⤍', $mergedContent);
        $mergedContent = str_replace('&lBarr;', '⤎', $mergedContent);
        $mergedContent = str_replace('&rBarr;', '⤏', $mergedContent);
        $mergedContent = str_replace('&RBarr;', '⤐', $mergedContent);
        $mergedContent = str_replace('&DDotrahd;', '⤑', $mergedContent);
        $mergedContent = str_replace('&UpArrowBar;', '⤒', $mergedContent);
        $mergedContent = str_replace('&DownArrowBar;', '⤓', $mergedContent);
        $mergedContent = str_replace('&Rarrtl;', '⤖', $mergedContent);
        $mergedContent = str_replace('&latail;', '⤙', $mergedContent);
        $mergedContent = str_replace('&ratail;', '⤚', $mergedContent);
        $mergedContent = str_replace('&lAtail;', '⤛', $mergedContent);
        $mergedContent = str_replace('&rAtail;', '⤜', $mergedContent);
        $mergedContent = str_replace('&larrfs;', '⤝', $mergedContent);
        $mergedContent = str_replace('&rarrfs;', '⤞', $mergedContent);
        $mergedContent = str_replace('&larrbfs;', '⤟', $mergedContent);
        $mergedContent = str_replace('&rarrbfs;', '⤠', $mergedContent);
        $mergedContent = str_replace('&nwarhk;', '⤣', $mergedContent);
        $mergedContent = str_replace('&nearhk;', '⤤', $mergedContent);
        $mergedContent = str_replace('&searhk;', '⤥', $mergedContent);
        $mergedContent = str_replace('&swarhk;', '⤦', $mergedContent);
        $mergedContent = str_replace('&nwnear;', '⤧', $mergedContent);
        $mergedContent = str_replace('&nesear;', '⤨', $mergedContent);
        $mergedContent = str_replace('&seswar;', '⤩', $mergedContent);
        $mergedContent = str_replace('&swnwar;', '⤪', $mergedContent);
        $mergedContent = str_replace('&cudarrr;', '', $mergedContent);
        $mergedContent = str_replace('&ldca;', '⤶', $mergedContent);
        $mergedContent = str_replace('&rdca;', '⤷', $mergedContent);
        $mergedContent = str_replace('&cudarrl;', '⤸', $mergedContent);
        $mergedContent = str_replace('&larrpl;', '⤹', $mergedContent);
        $mergedContent = str_replace('&curarrm;', '⤼', $mergedContent);
        $mergedContent = str_replace('&cularrp;', '⤽', $mergedContent);
        $mergedContent = str_replace('&rarrpl;', '⥅', $mergedContent);
        $mergedContent = str_replace('&harrcir;', '⥈', $mergedContent);
        $mergedContent = str_replace('&Uarrocir;', '⥉', $mergedContent);
        $mergedContent = str_replace('&lurdshar;', '⥊', $mergedContent);
        $mergedContent = str_replace('&ldrushar;', '⥋', $mergedContent);
        $mergedContent = str_replace('&RightUpDownVector;', '⥏', $mergedContent);
        $mergedContent = str_replace('&DownLeftRightVector;', '⥐', $mergedContent);
        $mergedContent = str_replace('&LeftUpDownVector;', '⥑', $mergedContent);
        $mergedContent = str_replace('&LeftVectorBar;', '⥒', $mergedContent);
        $mergedContent = str_replace('&RightVectorBar;', '⥓', $mergedContent);
        $mergedContent = str_replace('&RightUpVectorBar;', '⥔', $mergedContent);
        $mergedContent = str_replace('&RightDownVectorBar;', '⥕', $mergedContent);
        $mergedContent = str_replace('&DownLeftVectorBar;', '⥖', $mergedContent);
        $mergedContent = str_replace('&DownRightVectorBar;', '⥗', $mergedContent);
        $mergedContent = str_replace('&LeftUpVectorBar;', '⥘', $mergedContent);
        $mergedContent = str_replace('&LeftDownVectorBar;', '⥙', $mergedContent);
        $mergedContent = str_replace('&LeftTeeVector;', '⥚', $mergedContent);
        $mergedContent = str_replace('&RightTeeVector;', '⥛', $mergedContent);
        $mergedContent = str_replace('&RightUpTeeVector;', '⥜', $mergedContent);
        $mergedContent = str_replace('&RightDownTeeVector;', '⥝', $mergedContent);
        $mergedContent = str_replace('&DownLeftTeeVector;', '⥞', $mergedContent);
        $mergedContent = str_replace('&DownRightTeeVector;', '⥟', $mergedContent);
        $mergedContent = str_replace('&LeftUpTeeVector;', '⥠', $mergedContent);
        $mergedContent = str_replace('&LeftDownTeeVector;', '⥡', $mergedContent);
        $mergedContent = str_replace('&lHar;', '⥢', $mergedContent);
        $mergedContent = str_replace('&uHar;', '⥣', $mergedContent);
        $mergedContent = str_replace('&rHar;', '⥤', $mergedContent);
        $mergedContent = str_replace('&dHar;', '⥥', $mergedContent);
        $mergedContent = str_replace('&luruhar;', '⥦', $mergedContent);
        $mergedContent = str_replace('&ldrdhar;', '⥧', $mergedContent);
        $mergedContent = str_replace('&ruluhar;', '⥨', $mergedContent);
        $mergedContent = str_replace('&rdldhar;', '⥩', $mergedContent);
        $mergedContent = str_replace('&lharul;', '⥪', $mergedContent);
        $mergedContent = str_replace('&llhard;', '⥫', $mergedContent);
        $mergedContent = str_replace('&rharul;', '⥬', $mergedContent);
        $mergedContent = str_replace('&lrhard;', '⥭', $mergedContent);
        $mergedContent = str_replace('&udhar;', '⥮', $mergedContent);
        $mergedContent = str_replace('&duhar;', '⥯', $mergedContent);
        $mergedContent = str_replace('&RoundImplies;', '⥰', $mergedContent);
        $mergedContent = str_replace('&erarr;', '⥱', $mergedContent);
        $mergedContent = str_replace('&simrarr;', '⥲', $mergedContent);
        $mergedContent = str_replace('&larrsim;', '⥳', $mergedContent);
        $mergedContent = str_replace('&rarrsim;', '⥴', $mergedContent);
        $mergedContent = str_replace('&rarrap;', '⥵', $mergedContent);
        $mergedContent = str_replace('&ltlarr;', '⥶', $mergedContent);
        $mergedContent = str_replace('&gtrarr;', '⥸', $mergedContent);
        $mergedContent = str_replace('&subrarr;', '⥹', $mergedContent);
        $mergedContent = str_replace('&suplarr;', '⥻', $mergedContent);
        $mergedContent = str_replace('&lfisht;', '⥼', $mergedContent);
        $mergedContent = str_replace('&rfisht;', '⥽', $mergedContent);
        $mergedContent = str_replace('&ufisht;', '⥾', $mergedContent);
        $mergedContent = str_replace('&dfisht;', '⥿', $mergedContent);
// Shapes
        $mergedContent = str_replace('&uhblk;', '▀', $mergedContent);
        $mergedContent = str_replace('&lhblk;', '▄', $mergedContent);
        $mergedContent = str_replace('&block;', '█', $mergedContent);
        $mergedContent = str_replace('&blk14;', '░', $mergedContent);
        $mergedContent = str_replace('&blk12;', '▒', $mergedContent);
        $mergedContent = str_replace('&blk34;', '▓', $mergedContent);
        $mergedContent = str_replace('&boxh;', '─', $mergedContent);
        $mergedContent = str_replace('&boxv;', '│', $mergedContent);
        $mergedContent = str_replace('&boxdr;', '┌', $mergedContent);
        $mergedContent = str_replace('&boxdl;', '┐', $mergedContent);
        $mergedContent = str_replace('&boxur;', '└', $mergedContent);
        $mergedContent = str_replace('&boxul;', '┘', $mergedContent);
        $mergedContent = str_replace('&boxvr;', '├', $mergedContent);
        $mergedContent = str_replace('&boxvl;', '┤', $mergedContent);
        $mergedContent = str_replace('&boxhd;', '┬', $mergedContent);
        $mergedContent = str_replace('&boxhu;', '┴', $mergedContent);
        $mergedContent = str_replace('&boxvh;', '┼', $mergedContent);
        $mergedContent = str_replace('&boxH;', '═', $mergedContent);
        $mergedContent = str_replace('&boxV;', '║', $mergedContent);
        $mergedContent = str_replace('&boxdR;', '╒', $mergedContent);
        $mergedContent = str_replace('&boxDr;', '╓', $mergedContent);
        $mergedContent = str_replace('&boxDR;', '╔', $mergedContent);
        $mergedContent = str_replace('&boxdL;', '╕', $mergedContent);
        $mergedContent = str_replace('&boxDl;', '╖', $mergedContent);
        $mergedContent = str_replace('&boxDL;', '╗', $mergedContent);
        $mergedContent = str_replace('&boxuR;', '╘', $mergedContent);
        $mergedContent = str_replace('&boxUr;', '╙', $mergedContent);
        $mergedContent = str_replace('&boxUR;', '╚', $mergedContent);
        $mergedContent = str_replace('&boxuL;', '╛', $mergedContent);
        $mergedContent = str_replace('&boxUl;', '╜', $mergedContent);
        $mergedContent = str_replace('&boxUL;', '╝', $mergedContent);
        $mergedContent = str_replace('&boxvR;', '╞', $mergedContent);
        $mergedContent = str_replace('&boxVr;', '╟', $mergedContent);
        $mergedContent = str_replace('&boxVR;', '╠', $mergedContent);
        $mergedContent = str_replace('&boxvL;', '╡', $mergedContent);
        $mergedContent = str_replace('&boxVl;', '╢', $mergedContent);
        $mergedContent = str_replace('&boxVL;', '╣', $mergedContent);
        $mergedContent = str_replace('&boxHd;', '╤', $mergedContent);
        $mergedContent = str_replace('&boxhD;', '╥', $mergedContent);
        $mergedContent = str_replace('&boxHD;', '╦', $mergedContent);
        $mergedContent = str_replace('&boxHu;', '╧', $mergedContent);
        $mergedContent = str_replace('&boxhU;', '╨', $mergedContent);
        $mergedContent = str_replace('&boxHU;', '╩', $mergedContent);
        $mergedContent = str_replace('&boxvH;', '╪', $mergedContent);
        $mergedContent = str_replace('&boxVh;', '╫', $mergedContent);
        $mergedContent = str_replace('&boxVH;', '╬', $mergedContent);
// Punctuation
//$mergedContent = str_replace('&amp;', '&', $mergedContent);
        $mergedContent = str_replace('&excl;', '!', $mergedContent);
        $mergedContent = str_replace('&num;', '#', $mergedContent);
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
        $mergedContent = str_replace('&copy;', '©', $mergedContent);
        $mergedContent = str_replace('&laquo;', '«', $mergedContent);
        $mergedContent = str_replace('&reg;', '®', $mergedContent);
        $mergedContent = str_replace('&raquo;', '»', $mergedContent);
        $mergedContent = str_replace('&circ;', 'ˆ', $mergedContent);
        $mergedContent = str_replace('&tilde;', '˜', $mergedContent);
        $mergedContent = str_replace('&lsaquo;', '‹', $mergedContent);
        $mergedContent = str_replace('&rsaquo;', '›', $mergedContent);
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