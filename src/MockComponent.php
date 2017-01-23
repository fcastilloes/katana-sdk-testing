<?php

namespace Katana\SdkTesting;

/**
 * Replacement for Component classes.
 *
 * Stores callbacks to be able to return them on demand.
 *
 * Class MockComponent
 * @package Katana\SdkTesting
 */
class MockComponent {
    /**
     * @var callable[]
     */
    private $callbacks = [];

    public function startup()
    {
        // Empty
    }

    /**
     * Stores actions by name.
     *
     * @param string $name
     * @param callable $callback
     */
    public function action($name, $callback)
    {
        $this->callbacks[$name] = $callback;
    }

    public function run()
    {
        // Empty
    }

    public function setResource($name, $resource)
    {
        // Empty
    }

    /**
     * Returns a callable by name.
     *
     * @param string $name
     * @return callable
     */
    public function getCallback($name)
    {
        return $this->callbacks[$name];
    }
}
