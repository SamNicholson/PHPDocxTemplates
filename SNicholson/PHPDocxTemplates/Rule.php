<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 21/03/2015
 * Time: 21:36
 */

namespace SNicholson\PHPDocxTemplates;


use SNicholson\PHPDocxTemplates\Interfaces\RuleInterface;

class Rule implements RuleInterface{

    private $target;
    private $data;

    /**
     * @return mixed
     */
    public function getTarget() {
        return $this->target;
    }

    /**
     * @param mixed $target
     */
    public function setTarget($target) {
        $this->target = htmlentities($target);
    }

    /**
     * @return mixed
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data) {
        $this->data = $data;
    }

}