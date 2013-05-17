<?php
require_once 'Util.php';

class MyConfig extends Config_ENV { }

class LoadTest extends PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass ( )
    {
        MyConfig::envname('FOO_ENV');
        MyConfig::common( array('name' => 'foobar') );
        MyConfig::config('development', MyConfig::load('t/data/development.php'));
        MyConfig::config('production', MyConfig::merge(
            MyConfig::load('t/data/production.php'),
            array('bar' => 'YYY')
        ) );
    }

    public function testLoadDevel ( )
    {
        $_SERVER['FOO_ENV'] = 'development';
        $this->assertEquals('development', MyConfig::param('test'));
    }

    public function testLoadProd ( )
    {
        $_SERVER['FOO_ENV'] = 'production';
        $this->assertEquals('production', MyConfig::param('test'));
        $this->assertEquals('foo',  MyConfig::param('foo'));
        $this->assertEquals('YYY',  MyConfig::param('bar'));
    }
}
