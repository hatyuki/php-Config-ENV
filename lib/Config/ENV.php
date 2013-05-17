<?php

class Config_ENV
{
    const VERSION = 0.01;
    private static $instance = array( );
    public  $envname    = 'CONFIG_ENV';
    public  $defaultenv = 'default';
    public  $config     = array( );
    public  $common     = array( );
    public  $local      = array( );
    public  $merged     = array( );

    public static function config ($env, $config)
    {
        $instance = self::instance( );
        $instance->config[$env] = $config;
        $instance->merged[$env] = null;
    }

    public static function common ($config)
    {
        $instance = self::instance( );
        $instance->common = $config;
    }

    public static function parent ($name)
    {
        $instance = self::instance( );

        return isset($instance->config[$name]) ? $instance->config[$name] : array( );
    }

    public static function local ($config)
    {
        $instance = self::instance( );
        $instance->local[ ] = $config;
        $instance->merged   = array( );

        return new Config_ENV_Guard($instance->local, $instance->merged);
    }

    public static function load ($file)
    {
        return function ( ) use ($file) {
            return require $file;
        };
    }

    public static function merge ( )
    {
        $args = func_get_args( );

        return function ( ) use ($args) {
            $config = array( );

            foreach ($args as $arg) {
                if ( is_callable($arg) ) {
                    $arg = $arg( );
                }

                if (!is_array($arg)) {
                    throw new RuntimeException('"$config" must be an array');
                }

                $config = array_merge($config, $arg);
            }

            return $config;
        };
    }

    public static function param ($name)
    {
        $instance = self::instance( );
        $config   = self::current( );

        return isset($config[$name]) ? $config[$name] : null;
    }

    public static function current ( )
    {
        $instance = self::instance( );
        $env      = self::env( );

        if ( !isset($instance->merged[$env]) ) {
            $common = $instance->common;
            $config = isset($instance->config[$env]) ? $instance->config[$env] : array( );

            $local = array( );
            foreach ($instance->local as $l) {
                $local = array_merge($local, $l);
            }

            $callback = self::merge($common, $config, $local);
            $instance->merged[$env] = $callback( );
        }

        return $instance->merged[$env];
    }

    public static function env ( )
    {
        $instance = self::instance( );
        $name     = $instance->envname;

        return isset($_SERVER[$name]) ? $_SERVER[$name] : $instance->defaultenv;
    }

    public static function envname ($name=null)
    {
        $instance = self::instance( );

        if (isset($name)) {
            $instance->envname = $name;
        }

        return $instance->envname;
    }

    public static function defaultenv ($name=null)
    {
        $instance = self::instance( );

        if (isset($name)) {
            $instance->defaultenv = $name;
        }

        return $instance->defaultenv;
    }

    /* -----------------------------------------------------
     * Internals
     */
    private static final function instance ( )
    {
        $class = get_called_class( );

        if (!isset(self::$instance[$class])) {
            self::$instance[$class] = new static( );
        }

        return self::$instance[$class];
    }

    private final function __construct ( ) { }
    private final function __clone     ( ) { }
}

class Config_ENV_Guard
{
    private $local;
    private $merged;

    public function __construct (&$local, &$merged)
    {
        $this->local  =& $local;
        $this->merged =& $merged;
    }

    public function __destruct ( )
    {
        array_pop($this->local);
        $this->merged = array( );
    }
}
