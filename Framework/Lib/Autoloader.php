<?php
namespace Core\Lib;

/**
 * 自动加载类.
 *
 * @author chengjinw
 * @uses FRAMEWORK_ROOT,SERVICE_ROOT
 */
class Autoloader{
    protected static $thirdPartyPrefixes = array(''=>null);

    /**
     * Register all autoloaders.
     */
    public static function loadAll()
    {
        spl_autoload_register(array('Core\Lib\Autoloader','loadByNamespace'));
        spl_autoload_register(array('Core\Lib\Autoloader','loadThirdParty'));
    }

    /**
     * 按命名空间自动加载相应的类
     * @param string $name 命名空间及类名
     */
    public static function loadByNamespace($name)
    {
        $classPath = str_replace('\\', DIRECTORY_SEPARATOR ,$name);
        $portion = explode(DIRECTORY_SEPARATOR, $classPath);
        if($portion[0] == 'Core')
        {
            $classFile = FRAMEWORK_ROOT;
        }
        else
        {
            $classFile = SERVICE_ROOT . 'ws.'.$portion[0] . '/';
        }
        unset($portion[0]);
        $classFile .= implode(DIRECTORY_SEPARATOR, $portion).'.php';
        if(is_file($classFile))
        {
            require($classFile);
            return true;
        }
        return false;
    }

    /**
     * Register a prefix for path of thirdparty libs. All thirpart libs should be placed under Framwork/ThirdParty.
     * @param string|array $prefix
     */
    public static function registerThirPartyPrefix($prefix)
    {
        if(!is_array($prefix))
        {
            $prefix = array($prefix);
        }
        foreach ($prefix as $v)
        {
            if(!isset(static::$thirdPartyPrefixes[$v]))
            {
                static::$thirdPartyPrefixes[$v] = null;
            }
        }
    }

    public static function loadThirdParty($name)
    {
        foreach (self::$thirdPartyPrefixes as $k => $v)
        {
            $classFile = FRAMEWORK_ROOT.'ThirdParty'.
                         DIRECTORY_SEPARATOR.$k.DIRECTORY_SEPARATOR.
                         str_replace('\\', DIRECTORY_SEPARATOR ,$name).'.php';
            if(is_file($classFile))
            {
                require($classFile);
                return true;
            }
        }
        return false;
    }
}