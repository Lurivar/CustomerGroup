<?php

namespace CustomerGroup\Tests\Event;

use CustomerGroup\Event\CustomerGroupEvents;
use CustomerGroup\Tests\AbstractCustomerGroupTest;

/**
 * Tests for CustomerGroupEvents.
 */
class CustomerGroupEventsTest extends AbstractCustomerGroupTest
{
    /**
     * Assert that a class has a constant.
     * @param string $constantName Expected constant name.
     * @param string $className Name of the class to assert
     * @param string $message Optional assertion message.
     */
    protected function assertClassHasConstant($constantName, $className, $message = "")
    {
        $reflectedClass = new \ReflectionClass($className);
        $this->assertTrue($reflectedClass->hasConstant($constantName), $message);
    }

    /**
     * @covers CustomerGroupEvents
     */
    public function testDefinesAllModuleEvents()
    {
        $this->assertClassHasConstant("CREATE_CUSTOMER_GROUP", CustomerGroupEvents::class);
        $this->assertClassHasConstant("ADD_CUSTOMER_TO_CUSTOMER_GROUP", CustomerGroupEvents::class);
    }
}
