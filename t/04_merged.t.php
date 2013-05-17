<?php
require_once 'Util.php';

class MyConfig extends Config_ENV { }

class MergedTest extends PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass ( )
    {
        MyConfig::envname('FOO_ENV');
        MyConfig::common( array('name' => 'foobar') );
        $_SERVER['FOO_ENV'] = 'development';
    }

    public function testSimple ( )
    {
        $this->assertEquals('foobar', MyConfig::param('name'));
    }

    public function testOverride ( )
    {
        MyConfig::config('development', array('name' => 'override'));
        $this->assertEquals('override', MyConfig::param('name'));
    }
}
