<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 22/03/2015
 * Time: 12:24
 */

namespace SNicholson\PHPDocxTemplates\Tests;


use SNicholson\PHPDocxTemplates\Exceptions\InvalidFilenameException;

class InvalidFilenameExceptionTest extends \PHPUnit_Framework_TestCase {

    function testExceptionThrown(){
        $this->setExpectedException('SNicholson\PHPDocxTemplates\Exceptions\InvalidFilenameException');
        throw new InvalidFilenameException("test");
    }

}
