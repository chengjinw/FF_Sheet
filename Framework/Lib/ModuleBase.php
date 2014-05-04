<?php
/**
 * Module Base file.
 *
 * @author 
 */

namespace Core\Lib;

abstract class ModuleBase{
    /**
     *
     * Instances of the derived classes.
     * @var array
     */
    protected static $instances = array();

    /**
     * Get instance of the derived class.
     * @return \Core\Lib\ModuleBase
     */
    public static function instance()
    {
        $className = get_called_class();
        if (!isset(self::$instances[$className]))
        {
            self::$instances[$className] = new $className;
        }
        return self::$instances[$className];
    }
    
    /**
     * 组装Cache key,变长参数，可以传若干字符或数字型参数.
     *
     * @return string 对应的key字符串.
     */
    public function generateKey()
    {
        $args = func_get_args();
        if ( $args ) {
            return implode('_', $args);
        }
        return '';
    }
}