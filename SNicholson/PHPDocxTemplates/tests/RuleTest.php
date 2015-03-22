<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 22/03/2015
 * Time: 12:51
 */

namespace SNicholson\PHPDocxTemplates\Tests;


use SNicholson\PHPDocxTemplates\Rule;

class RuleTest extends \PHPUnit_Framework_TestCase {

    function testSetRuleEscapesInvalidCharacters(){
        $rule = new Rule();
        $escapeMe = '&Target123123';
        $escaped = htmlentities($escapeMe);
        $rule->setTarget($escapeMe);
        $this->assertEquals($escaped,$rule->getTarget());
    }

    function testStringDataTypeAllowed(){
        $rule = new Rule();
        $data = 'astring';
        $rule->setData($data);
        $this->assertEquals($data,$rule->getData());
    }

    function testFunctionDataTypeAllowed(){
        $rule = new Rule();
        $data = function(){
            return 'thisIsTheReturn';
        };
        $rule->setData($data);
        $this->assertEquals($data,$rule->getData());
    }

}
