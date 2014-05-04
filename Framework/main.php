<?php
namespace Core\Lib;

class Main
{
    protected $runBeginTime;
    protected $requestedNameSpace;
    protected $requestedClass;
    protected $requestedMethod;
    protected $requestedData;
    
    public function run($data = null)
    {
        $this->runBeginTime = microtime(true);
        if (empty($data) || !isset($data['class']) || !isset($data['method'])) {
            // @TODO throw Exception
        }
        $splitData = explode('_', $data['class']);
        $nameSpace = $splitData[0];
        unset($splitData[0]);
        $className = implode('\\', $splitData);
        $this->requestedNameSpace = $nameSpace;
        $this->requestedClass = $className;
        $this->requestedMethod = $data['method'];
        unset($data['class']);
        unset($data['method']);
        $this->requestedData = $data;
        
        try {
            $result = $this->_internalRun();
        } catch (\Exception $e) {
            throw $e;
        }
        return $result;
    }
    
    protected function _internalRun()
    {
        if (!empty($this->requestedData['sessionId'])) {
            session_id($this->requestedData['sessionId']);
            session_start();
        }
        
        try {
            $this->verify();
            $result = $this->dispatchCall();
        } catch (SysException $e) {
            $result = array(
                'error' => $e->getMessage(),
                'code'  => $e->getCode()
            );
        } catch (\Exception $e) {
            $result['exception'] = array(
                    'class' => get_class($e),
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'traceAsString' => $e->getTraceAsString(),
            );
        }
        
        return $result;
    }
    
    protected function verify($data = null)
    {
        if (empty($this->requestedData['user'])) {
            throw new SysException('Empty client user name');
        }
    }
    
    /**
     * dispatch request to sub service layer
     * @return multitype
     */
    protected function dispatchCall()
    {
        // 呼叫标准类
        $classStr = "{$this->requestedNameSpace}\\Service\\{$this->requestedClass}";
        
        if (class_exists($classStr))
        {
            $obj = new $classStr;
            if (!($obj instanceof \Core\Lib\ServiceBase)) {
                // TODO: throw Exception
            }

            if (method_exists($obj, $this->requestedMethod))
            {
                if(method_exists($obj, 'setRequestInfo'))
                {
                    $obj->setRequestInfo(array('user'=>$this->requestedData['user'], 'class'=>$this->requestedClass, 'method'=>$this->requestedMethod, 'params'=>$this->requestedData['params']));
                }
                return call_user_func_array(array($obj, $this->requestedMethod), $this->requestedData['params']);
            }
        }

        // 呼叫方法类
        $requestedMethodClass = ucfirst($this->requestedMethod);
        $classStr = "{$this->requestedNameSpace}\\Service\\{$this->requestedClass}\\{$requestedMethodClass}";
        if (class_exists($classStr)) {
            $obj = new $classStr;
            if (!($obj instanceof \Core\Lib\ServiceBase)) {
                // TODO: throw Exception
            }
            if(method_exists($obj, 'setRequestInfo'))
            {
                $obj->setRequestInfo(array('user'=>$this->requestedData['user'], 'class'=>$this->requestedClass, 'method'=>$this->requestedMethod, 'params'=>$this->requestedData['params']));
            }
            return call_user_func_array(array($obj, $this->requestedMethod), $this->requestedData['params']);
        }

        throw new SysException("no such method: {$this->requestedClass}::{$this->requestedMethod}");
    }
    
}

