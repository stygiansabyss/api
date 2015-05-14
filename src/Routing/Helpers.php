<?php

namespace Dingo\Api\Routing;

use ErrorException;

trait Helpers
{
    /**
     * Get the authenticated user.
     *
     * @return mixed
     */
    protected function user()
    {
        return app('Dingo\Api\Auth\Auth')->user();
    }

    /**
     * Get the auth instance.
     *
     * @return \Dingo\Api\Auth\Auth
     */
    protected function auth()
    {
        return app('Dingo\Api\Auth\Auth');
    }

    /**
     * Get the response factory instance.
     *
     * @return \Dingo\Api\Http\Response\Factory
     */
    protected function response()
    {
        return app('Dingo\Api\Http\Response\Factory');
    }

    /**
     * Magically handle calls to certain properties.
     *
     * @param string $key
     *
     * @throws \ErrorException
     *
     * @return mixed
     */
    public function __get($key)
    {
        $callable = [
            'user', 'auth', 'response'
        ];

        if (in_array($key, $callable) && method_exists($this, $key)) {
            return $this->$key();
        }

        throw new ErrorException('Undefined property '.get_class($this).'::'.$key);
    }

    /**
     * Magically handle calls to certain methods on the response factory.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @throws \ErrorException
     *
     * @return \Dingo\Api\Http\Response\Builder
     */
    public function __call($method, $parameters)
    {
        if (method_exists($this->response(), $method) || $method == 'array') {
            return call_user_func_array([$this->response(), $method], $parameters);
        }

        throw new ErrorException('Undefined method '.get_class($this).'::'.$method);
    }
}