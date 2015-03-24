<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 21/03/2015
 * Time: 21:37
 */

namespace SNicholson\PHPDocxTemplates\Interfaces;


interface RuleInterface {

    /**
     * @return mixed
     */
    public function getTarget();

    /**
     * @param mixed $target
     */
    public function setTarget($target);

    /**
     * @return mixed
     */
    public function getData();

    /**
     * @param mixed $data
     */
    public function setData($data);
}