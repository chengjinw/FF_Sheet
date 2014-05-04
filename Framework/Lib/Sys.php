<?php
namespace Core\Lib;

/**
 * Provides common methods for getting event system information and inspectings.
 *
 * @author
 */
class Sys {
    /**
     * Get the time consumed in the script.
     *
     * @uses \SCRIPT_START_TIME
     */
    public static function uptime() {
        return sprintf ( '%.6f', microtime ( true ) - microtime(true) );
    }

    /**
     * get ip(v4) address of the first ethernet card
     */
    public static function getOsIp()
    {
        putenv('LANGUAGE=en_US:en');
        putenv('LANG=en_US:en');
        switch(PHP_OS)
        {
            case 'Linux':
                $inet_info = `/sbin/ifconfig eth0`;
                continue;
            case 'Darwin':
                $inet_info = `/sbin/ifconfig en0`;
                continue;
            default:
                return null;
        }
        $matches = array();
        $pattern = '@.*inet.*?(?<ip>[0-9\.]{7,}) .*@';
        preg_match_all($pattern, $inet_info, $matches);
        if(isset($matches['ip'])){
            return $matches['ip'][0];
        }else{
            return false;
        }
    }

    /**
     * get configs
     * @param string $name configuration name
     * @param string $type options are "Core", "App", default: "Core"
     * @return Object
     * @uses \SERVICE_NAME
     */
    public static function getCfg($name, $type='Core', $namespace = null)
    {
        static $cfgInstances=array();
        if($type == 'App')
        {
            $cfgClassStr = $namespace . '\\' . 'Config\\' . $name;
        }
        else
        {
             $cfgClassStr = 'Core\\' . 'Config\\' . $name;
        }
        if(!isset($cfgInstances[$cfgClassStr]))
        {
            if(class_exists($cfgClassStr))
            {
                $cfgInstances[$cfgClassStr] = new $cfgClassStr;
            }
            else
            {
                throw new SysException('Config class "'.$cfgClassStr.'" not found.', 1001);
            }
        }
        return $cfgInstances[$cfgClassStr];
    }

    public static function getAppCfg($namespace, $name)
    {
        return self::getCfg($name, 'App', $namespace);
    }
}