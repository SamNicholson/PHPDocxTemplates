<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 22/03/2015
 * Time: 15:44
 */

namespace SNicholson\PHPDocxTemplates\Tests;


use SNicholson\PHPDocxTemplates\RuleCollection;

class RuleCollectionTest extends \PHPUnit_Framework_TestCase {

    function testRouteCollectionAllowsAddingValidSimpleRules(){
        $ruleCollection = new RuleCollection();
        $ruleTarget = 'testText';
        $ruleData = function(){
            return 'thisisatest'.'test';
        };
        $ruleCollection->addSimpleRule($ruleTarget,$ruleData);
        $ruleCollectionRules = $ruleCollection->getRules();

        $this->assertEquals(
            'SNicholson\PHPDocxTemplates\Rules\SimpleRule',
            get_class($ruleCollectionRules[0])
        );

        $this->assertEquals($ruleTarget,$ruleCollectionRules[0]->getTarget());
        $this->assertEquals($ruleData,$ruleCollectionRules[0]->getData());
    }

}
