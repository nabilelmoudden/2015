<?php  
namespace Business;

abstract class AbstractProduct 
{
    protected $_inheritances = array();

    public function __isset($class_name)
    {
        return array_key_exists($class_name, $this->_inheritances);   
    }

    public function __get($class_name)
    {
        try {
            if (!isset($this->$class_name)) {
                throw new Exception($class_name
                    . ' does not exists!');
            }

            return $this->_inheritances[$class_name];

        } catch (Exception $e) {
            trigger_error($e->getMessage(), E_USER_WARNING);
            return false;
        } 
    }

    public function __set($class_name, $instance)
    {
        try {
            if (isset($this->$class_name)) {
                throw new Exception(get_class($this)
                    . ' already extends '
                    . $class_name);
            }

            if (!$instance instanceof $class_name) {
                throw new Exception('Unexpected instance of '
                    . get_class($instance)
                    . ' instead of '
                    . $class_name);
            }

            $this->_inheritances[$class_name] = $instance;

        } catch (Exception $e) {
            trigger_error($e->getMessage(), E_USER_WARNING);
        }
    }

    public function __unset($class_name)
    {
        unset($this->_inheritances[$class_name]);
    }

    public function call($method_name, $args = null)
    {
        try {
            foreach ($this->_inheritances as $obj) {
                if (!is_callable(array($obj, $method_name))) {
                    throw new Exception('Undefined method '
                        . get_class($obj)
                        . '::'
                        . $method_name
                        . '()');
                }
                $obj->$method_name($args);
            }
        } catch (Exception $e) {
            trigger_error($e->getMessage(), E_USER_WARNING);
        }
    }
    


}
?>