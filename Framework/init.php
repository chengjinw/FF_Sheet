<?php
if(!defined('SCRIPT_START_TIME'))
{
    /**
     * script start time.This may lose some precision,since include this init script consumes time.
     */
    define('SCRIPT_START_TIME', microtime(true));
}

if(!defined('FRAMEWORK_ROOT'))
{
    /**
     * physic root path of webservice framework
     * @var string
     */
    define('FRAMEWORK_ROOT', __DIR__.DIRECTORY_SEPARATOR);
}
if(!defined('SERVICE_ROOT'))
{
    /**
     * Service项目所在目录.
     * @var string
     */
    define('SERVICE_ROOT', $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR. 'Services' . DIRECTORY_SEPARATOR);
}
require_once 'main.php';
require_once FRAMEWORK_ROOT.'Lib'.DIRECTORY_SEPARATOR.'Autoloader.php';
Core\Lib\Autoloader::loadAll();