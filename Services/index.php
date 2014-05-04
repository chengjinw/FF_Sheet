<?php
if (!defined('DEBUG')) {
    define('DEBUG', false);
}
error_reporting(E_ALL);
ini_set('display_errors', '1');

if(!defined('FRAMEWORK_ROOT'))
{
    /**
     * webservice framework
     * @var string
     */
    define('FRAMEWORK_ROOT', $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'Framework' . DIRECTORY_SEPARATOR);
}

require FRAMEWORK_ROOT . 'init.php';

$request = $_REQUEST;
// for test
$request = array(
    'class' => 'User_UserInfo',
    'method' => 'test',
    'user' => 'App',
    'params' => array(
        'test11' => 'a',
        'test22' => 'b'
    ),
);

$main = new Core\Lib\Main();
$main->run($request);
