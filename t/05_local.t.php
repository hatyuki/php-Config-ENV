<?php
require_once 'Util.php';

class MyConfig extends Config_ENV { }

class LocalizeTest extends PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass ( )
    {
        MyConfig::envname('FOO_ENV');
        MyConfig::common( array('name' => 'foobar') );
        MyConfig::config('development',    array('env' => 'devel'));
        MyConfig::config('test',           array('env' => 'test'));
        MyConfig::config('production',     array('env' => 'prod'));

        $_SERVER['FOO_ENV'] = 'development';
    }

    public function testLocalize ( )
    {
        $guard = MyConfig::local( array('name' => 'localized') );
        $this->assertEquals('localized', MyConfig::param('name'));

        $guard = null;
        $this->assertEquals('foobar', MyConfig::param('name'));

    }

    public function testLocalizeMulti ( )
    {
        $guard1 = MyConfig::local( array('name' => 'localized1') );
        $this->assertEquals('localized1', MyConfig::param('name'));

        $guard2 = MyConfig::local( array('name' => 'localized2') );
        $this->assertEquals('localized2', MyConfig::param('name'));

        $guard2 = null;
        $this->assertEquals('localized1', MyConfig::param('name'));

        $guard3 = MyConfig::local( array('name' => 'localized3') );
        $this->assertEquals('localized3', MyConfig::param('name'));

        $guard1 = null;
        $guard3 = null;
        $this->assertEquals('foobar', MyConfig::param('name'));
    }
}
