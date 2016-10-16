<?php

namespace rokorolov\parus\admin\base;

use rokorolov\parus\admin\exceptions\PresenterException;

/**
 * BasePresenter
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
abstract class BasePresenter
{
    /**
     * The wrappedObject to present
     *
     * @var mixed
     */
    protected $wrappedObject;

    /**
     * Inject the wrappedObject to be presented
     *
     * @param mixed
     */
    public function __construct($wrappedObject)
    {
        $this->wrappedObject = $wrappedObject;
    }
    
    /**
     * Get the wrapped object.
     *
     * @return object
     */
    public function getWrappedObject()
    {
        return $this->wrappedObject;
    }

    /**
     * Check to see if there is a presenter
     * method. If not pass to the wrappedObject
     *
     * @return mixed
     * @param string $key
     */
    public function __get($key)
    {
        if (method_exists($this, $key)){
          return $this->{$key}();
        }
        return $this->wrappedObject->{$key};
    }

    /**
     * Magic Method access for methods called against the presenter looks for a
     * method on the resource, or throws an exception if none is found.
     *
     * @param string $key
     * @param array $args
     * @return mixed
     * @throws UnknownMethodException
     */
    public function __call($key, $args)
    {
        if (method_exists($this->wrappedObject, $key)) {
            return call_user_func_array([$this->wrappedObject, $key], $args);
        }
        throw new PresenterException(get_called_class(), "Not found - $key");
    }

    /**
     * Is the key set on either the presenter or the wrapped wrappedObject?
     *
     * @param string $key
     * @return bool
     */
    public function __isset($key)
    {
        if (method_exists($this, $key)) {
            return true;
        }
        return isset($this->wrappedObject->$key);
    }
}
