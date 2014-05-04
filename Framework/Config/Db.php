<?php
/**
 * Database 配置
 */
namespace Core\Config;
class Db{
    public $DEBUG = TRUE;
    /**
     * available options are 1,2<br />
     * 1 log the SQL and time consumed;<br />
     * 2 logs including the traceback.<br />
     * <b>IMPORTANT</b><br />
     * please take care of option "confirm_link",when set as TRUE, each query will try to do an extra query to confirm that the link is still usable,this is mostly used in daemons.
     * @var INT
     */
    public  $DEBUG_LEVEL = 1;
    public $write = array('default'=>
                        array('dsn' => 'mysql:host=127.0.0.1;port=3306;dbname=test',
                              'user'     => 'root',
                              'password' => '',
                              'confirm_link' => true,//required to set to TRUE in daemons.
                              'options'  => array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'utf8\'',
                                                  \PDO::ATTR_TIMEOUT=>10000
                              )
                        ),
    );
}