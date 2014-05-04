<?php
namespace User\Service;

use Core\Lib\ServiceBase;

class UserInfo extends ServiceBase
{
    public function test()
    {
        /**
         * @uses \User\Service\Report
         */
        $s = $this->ext('User_Report')->op();
        echo $s;
    }
}