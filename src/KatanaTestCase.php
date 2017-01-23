<?php

namespace Katana\SdkTesting;

use Katana\Sdk\Action;
use Katana\Sdk\Api\Param;
use Katana\Sdk\Middleware;
use Katana\Sdk\Service;
use Prophecy\Argument;

/**
 * TestCase extension to test Katana Components.
 *
 * Class KatanaTestCase
 * @package Katana\SdkTesting
 */
abstract class KatanaTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Replaces Service and Middleware classes with a MockComponent.
     */
    public static function setUpBeforeClass()
    {
        if (!class_exists(Service::class, false)) {
            class_alias(MockComponent::class, Service::class);
        }

        if (!class_exists(Middleware::class, false)) {
            class_alias(MockComponent::class, Middleware::class);
        }
    }

    /**
     * Returns a named callable from a file.
     *
     * The file MUST return the component for this method to work.
     *
     * @param string $file
     * @param string $name
     * @return callable
     */
    protected function getCallback($file, $name)
    {
        /** @var MockComponent $component */
        $component = require $file;

        return $component->getCallback($name);
    }

    /**
     * Build an Action prophecy.
     *
     * Calls to Action::getParam() will return Param prophecies. For the given
     * params the prophecies will correspond to existing Params with the given
     * key and value. Other Params will be represented as non-existent.
     *
     * @param array $params
     * @return \Prophecy\Prophecy\ObjectProphecy
     */
    protected function getActionProphecy(array $params = [])
    {
        $action = $this->prophesize(Action::class);
        foreach ($params as $name => $value) {
            $param = $this->prophesize(Param::class);
            $param->getValue()->willReturn($value);
            $param->exists()->willReturn(true);
            $action->getParam($name)->willReturn($param);
        }

        $emptyParam = $this->prophesize(Param::class);
        $emptyParam->getValue()->willReturn('');
        $emptyParam->exists()->willReturn(false);
        $action->getParam(Argument::any())->willReturn($emptyParam);

        $action->log(Argument::any())->willReturn(true);

        return $action;
    }
}
