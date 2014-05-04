<?php
namespace User\Service;

use Core\Lib\ServiceBase;

class UserInfo extends ServiceBase
{
    public function test()
    {
        /**
         * @var \User\Service\Report
         */
        $s = $this->ext('User_Report')->op();
        $t = \User\Module\User::instance()->getUserInfo();
        echo $s . '-' . $t;
    }
}