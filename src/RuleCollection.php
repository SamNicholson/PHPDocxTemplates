<?php

namespace SNicholson\PHPDocxTemplates;

use SNicholson\PHPDocxTemplates\Interfaces\RuleCollectionInterface;
use SNicholson\PHPDocxTemplates\Rules\RegexpRule;
use SNicholson\PHPDocxTemplates\Rules\SimpleRule;

/**
 * Class RuleCollection
 * @package SNicholson\PHPDocxTemplates
 */
class RuleCollection implements RuleCollectionInterface {

    /**
     * The rules that are in this Rule collection
     * @var array
     */
    private $rules = [];

    /**
     * Allows the addition of a simple rule to the rule collection
     * @param $target
     * @param $data
     */
    public function addSimpleRule($target,$data){
        $simpleRule = new SimpleRule();
        $simpleRule->setData($data);
        $simpleRule->setTarget($target);
        $this->rules[] = $simpleRule;
    }

    /**
     * Allows the addition of a regexp rule to the rule collection.
     * @param $target
     * @param $data
     */
    public function addRegexpRule($target,$data){
        $regExpRule = new RegexpRule();
        $regExpRule->setTarget($target);
        $regExpRule->setData($data);
        $this->rules[] = $regExpRule;
    }

    /**
     * Get the rules in this rule collection
     * @return array
     */
    public function getRules(){
        return $this->rules;
    }

}