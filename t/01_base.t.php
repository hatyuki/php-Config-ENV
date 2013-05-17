<?php
require_once 'Util.php';

class MyConfig extends Config_ENV { }

class BaseTest extends PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass ( )
    {
        MyConfig::envname('FOO_ENV');
        MyConfig::common( array('name' => 'foobar') );
        MyConfig::config('development', array('env' => 'devel') );
        MyConfig::config('test', array('env' => 't') );
        MyConfig::config('production', array('env' => 'prod') );
        MyConfig::config('production_bot', MyConfig::merge(
            MyConfig::parent('production'),
            array('bot' => 1)
        ) );

        unset($_SERVER['FOO_ENV']);
    }

    public function testBase ( )
    {
        $this->assertEquals('default', MyConfig::env( ));
        $this->assertEquals('foobar', MyConfig::param('name'));
        $this->assertNull(MyConfig::param('env'));
    }

    public function testDevel ( )
    {
        $_SERVER['FOO_ENV'] = 'development';

        $this->assertEquals('devel', MyConfig::param('env'));
        $this->assertEquals(
            array('env' => 'devel', 'name' => 'foobar'),
            MyConfig::current( )
        );
    }

    public function testProd ( )
    {
        $_SERVER['FOO_ENV'] = 'production';

        $this->assertEquals('prod', MyConfig::param('env'));
        $this->assertEquals(
            array('env' => 'prod', 'name' => 'foobar'),
            MyConfig::current( )
        );
    }

    public function testProdBot ( )
    {
        $_SERVER['FOO_ENV'] = 'production_bot';

        $this->assertEquals('prod', MyConfig::param('env'));
        $this->assertEquals(1, MyConfig::param('bot'));
        $this->assertEquals(
            array('env' => 'prod', 'name' => 'foobar', 'bot' => 1),
            MyConfig::current( )
        );
    }
}
