<?php

namespace SNicholson\PHPDocxTemplates;

use SNicholson\PHPDocxTemplates\Interfaces\RuleCollectionInterface;
use SNicholson\PHPDocxTemplates\Rules\SimpleRule;

class RuleCollection implements RuleCollectionInterface {

    private $rules = [];

    public function addSimpleRule($target,$data){
        $simpleRule = new SimpleRule();
        $simpleRule->setData($data);
        $simpleRule->setTarget($target);
        $this->rules[] = $simpleRule;
    }

    public function getRules(){
        return $this->rules;
    }

}