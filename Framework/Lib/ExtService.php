<?php
namespace Core\Lib;

/**
 * External service wrapper
 *
 * @author chengjinw
 */
class ExtService
{
    protected $className;

    protected $methodName;

    protected $extServiceName;

    protected static $instances = array();
    /**
     * instance of a service class
     *
     * @var Core\Lib\ServiceBase
     */
    protected $class;

    /**
     * Get an instance of an external service.
     *
     * @param string $extServiceName external service class name. e.g. User_Info.
     *
     * @return Core\Lib\ServiceBase
     */
    public static function instance($extServiceName)
    {
        if(!isset(static::$instances[$extServiceName]))
        {
            static::$instances[$extServiceName] = new static($extServiceName);
        }
        return static::$instances[$extServiceName];
    }

    public function __construct($extServiceName)
    {
        $this->extServiceName = $extServiceName;
        $serviceClass = str_replace('_', '\\', $extServiceName);
        $serviceClassPortion = explode('\\', $serviceClass);
        $rootNs = array_shift($serviceClassPortion);
        array_unshift($serviceClassPortion, $rootNs, 'Service');
        $serviceClass = implode('\\', $serviceClassPortion);
        $this->className = $serviceClass;
        //service class is a regular class, otherwise it might be an alia of a namespace and it's methods may be mapped as a class
        if(class_exists($serviceClass))
        {
            $this->class = new $serviceClass;
        }
    }

    public function __call($name, $args)
    {
        if($this->class && method_exists($this->class, $name))
        {
            if(method_exists($this->class, 'setRequestInfo'))
            {
                $this->class->setRequestInfo(array('user'=>'internal', 'class'=>$this->className, 'method'=>$name, 'params'=>$args));
            }
            return call_user_func_array(array($this->class, $name), $args);
        }
        $methodClassName = $this->className.'\\'.ucfirst($name);
        if(class_exists($methodClassName))
        {
            $class = new $methodClassName;
            if(method_exists($class, 'setRequestInfo'))
            {
                $class->setRequestInfo(array('user'=>'internal', 'class'=>$this->className, 'method'=>$name, 'params'=>$args));
            }
            return call_user_func_array(array($class, $name), $args);
        }
        throw new \Exception('Service not found: '.$this->extServiceName.'::'.$name);
    }
}
