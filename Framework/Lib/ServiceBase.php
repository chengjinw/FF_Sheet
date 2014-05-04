<?php
namespace Core\Lib;

use Core\Lib\Log as Log;

/**
 *
 * @author 
 */
class ServiceBase
{
    private $requestInfo = array();

    protected $currentService;

    /**
     * Store objects that are to be deleted after each request.
     * @var array
     */
    protected static $cachedObjects = array();

    public function execute()
    {
        return $this->return;
    }

    public function setRequestInfo($info)
    {
        $this->requestInfo = $info;
    }

    public function getRequestInfo()
    {
        return $this->requestInfo;
    }

    /**
     * Return an external service instance.
     *
     * @param string $name external service name
     * @return Core\Lib\ServiceBase
     */
    public function ext($name)
    {
        return ExtService::instance($name);
    }

    /**
     * Log service messages.
     *
     * @param array $data
     * @param string $endpoint  $endpoint indicates which log configuration to use. refers to Config\Log
     */
    public function log($data, $endpoint)
    {
        Log::instance($endpoint)->log($data);
    }

    public static function clearCachedObjects()
    {
        static::$cachedObjects = array();
        gc_collect_cycles();
    }

}
