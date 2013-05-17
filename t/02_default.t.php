<?php
require_once 'Util.php';

class MyConfig extends Config_ENV { }

class DefaultTest extends PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass ( )
    {
        MyConfig::envname('FOO_ENV');
        MyConfig::defaultenv('development');
        unset($_SERVER['FOO_ENV']);
    }

    public function testDefault ( )
    {
        $this->assertEquals('development', MyConfig::env( ));

        $_SERVER['FOO_ENV'] = 'production';

        $this->assertEquals('production', MyConfig::env( ));
    }
}
