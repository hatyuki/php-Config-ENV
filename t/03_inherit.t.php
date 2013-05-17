<?php
require_once 'Util.php';

class MyConfig1 extends Config_ENV { }
class MyConfig2 extends Config_ENV { }

class DefaultTest extends PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass ( )
    {
        MyConfig1::envname('FOO_ENV');
        MyConfig2::envname('FOO_ENV');
        MyConfig1::common( array('config' => 'config1') );
        MyConfig2::common( array('config' => 'config2') );
        unset($_SERVER['FOO_ENV']);
    }

    public function testInherit ( )
    { 
        $this->assertEquals('config1', MyConfig1::param('config'));
        $this->assertEquals('config2', MyConfig2::param('config'));
    }
}
