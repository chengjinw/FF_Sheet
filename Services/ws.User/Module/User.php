<?php
namespace User\Module;

use Core\Lib\ModuleBase;

class User extends ModuleBase
{
    
    /**
     * 获取对象实例的方法.
     *
     * @return \User\Module\User
     */
    public static function instance()
    {
        return parent::instance();
    }
    
    public function getUserInfo()
    {
        return 'u123';
    }
}